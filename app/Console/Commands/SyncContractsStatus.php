<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Premise;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncContractsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el estado de los contratos según la fecha actual (activa inicio o finaliza contratos pasados)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $this->info("Iniciando sincronización de contratos para la fecha: {$today}");

        DB::transaction(function () use ($today) {
            // 1. Activar contratos que empiezan hoy o antes y están pendientes
            $activatedCount = Contract::where('status', Contract::STATUS_PENDIENTE)
                ->whereDate('start_date', '<=', $today)
                ->update(['status' => Contract::STATUS_ACTIVO]);
                
            $this->info("Contratos activados (de pendiente a activo): {$activatedCount}");

            // 2. Finalizar contratos que ya pasaron su fecha de fin y están activos
            // Obtenemos los contratos a finalizar para poder actualizar sus locales
            $contractsToFinish = Contract::where('status', Contract::STATUS_ACTIVO)
                ->whereNotNull('end_date')
                ->whereDate('end_date', '<', $today)
                ->get();

            $finishedCount = 0;

            foreach ($contractsToFinish as $contract) {
                $contract->update([
                    'status' => Contract::STATUS_FINALIZADO,
                    'closed_at' => now(),
                    'closing_note' => 'Contrato finalizado automáticamente por cumplimiento de plazo.'
                ]);

                // Liberar el local si no tiene otros contratos activos o pendientes
                if ($contract->premise_id) {
                    $hasOtherActiveContracts = Contract::where('premise_id', $contract->premise_id)
                        ->whereIn('status', [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE])
                        ->where('id', '!=', $contract->id)
                        ->exists();

                    if (!$hasOtherActiveContracts) {
                        Premise::where('id', $contract->premise_id)->update(['status' => Premise::STATUS_AVAILABLE]);
                    }
                }

                $finishedCount++;
            }

            $this->info("Contratos finalizados (de activo a finalizado): {$finishedCount}");
        });
        
        $this->info("Sincronización completada exitosamente.");
    }
}
