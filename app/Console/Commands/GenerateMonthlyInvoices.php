<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate';

    protected $description = 'Genera las facturas correspondientes al periodo actual para los clientes con contratos activos';

    public function handle()
    {
        $today = Carbon::today();
        $currentPeriod = $today->format('Y-m'); // Ej: "2026-03"
        
        $this->info("Iniciando generación de facturas para el periodo: {$currentPeriod}");

        // Obtener todos los clientes que tienen al menos un contrato activo
        $clients = Client::whereHas('contracts', function($q) {
            $q->where('status', Contract::STATUS_ACTIVO);
        })->with(['contracts' => function($q) {
            $q->where('status', Contract::STATUS_ACTIVO)->with('premise');
        }])->get();

        $invoicesCreated = 0;

        foreach ($clients as $client) {
            foreach ($client->contracts as $contract) {
                $daysInMonth = $today->daysInMonth;
                $paymentDay = min($contract->payment_day, $daysInMonth);
                
                // Si hoy es el dia de pago o después, el contrato entra en facturación
                if ($today->day >= $paymentDay) {
                    
                    // Validar que el contrato no tenga ya una factura para este periodo
                    $existingInvoice = Invoice::where('contract_id', $contract->id)
                        ->where('period', $currentPeriod)
                        ->first();

                    if ($existingInvoice) {
                        continue; 
                    }

                    // Calculamos due date basado en el payment day del contrato
                    $dueDate = Carbon::create($today->year, $today->month, $paymentDay)->format('Y-m-d');
                    
                    DB::transaction(function () use ($client, $contract, $currentPeriod, $dueDate, &$invoicesCreated) {
                        
                        $invoice = Invoice::create([
                            'client_id' => $client->id,
                            'contract_id' => $contract->id,
                            'period' => $currentPeriod,
                            'total_amount' => 0, 
                            'paid_amount' => 0,
                            'due_date' => $dueDate,
                            'status' => Invoice::STATUS_PENDING, 
                        ]);

                        $totalAmount = $invoice->generateItemsFromContract($contract, $currentPeriod);

                        $invoice->update(['total_amount' => $totalAmount]);
                        $invoicesCreated++;
                    });
                }
            }
        }

        $this->info("Proceso finalizado. Facturas creadas: {$invoicesCreated}");
    }
}
