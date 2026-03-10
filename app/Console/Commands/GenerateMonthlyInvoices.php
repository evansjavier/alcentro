<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera las facturas correspondientes al periodo actual para los contratos activos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $currentPeriod = $today->format('Y-m'); // Ej: "2026-03"

        $this->info("Iniciando generación de facturas para el periodo: {$currentPeriod}");

        $activeContracts = Contract::where('status', Contract::STATUS_ACTIVO)->get();
        $invoicesCreated = 0;

        foreach ($activeContracts as $contract) {
            // Determinar la fecha de vencimiento (due_date) según el día de pago del contrato
            // max() y min() ayudan a que si el dia de pago es 31, pero el mes trae 30, no de error.
            $daysInMonth = $today->daysInMonth;
            $paymentDay = min($contract->payment_day, $daysInMonth);

            // Validar si ya llegamos a (o pasamos) la fecha de corte de este mes
            if ($today->day < $paymentDay) {
                continue; // Todavía no es su fecha de corte en el mes actual
            }

            $dueDate = Carbon::create($today->year, $today->month, $paymentDay)->format('Y-m-d');

            // 1. Factura de Renta (Alquiler)
            if ($contract->rent_amount > 0) {
                $created = $this->createInvoiceIfMissing(
                    $contract->id,
                    $currentPeriod,
                    'rent',
                    $contract->rent_amount,
                    $dueDate
                );
                if ($created) $invoicesCreated++;
            }

            // 2. Factura de Mantenimiento
            if ($contract->maintenance_pct > 0) {
                $maintenanceAmount = ($contract->rent_amount * $contract->maintenance_pct) / 100;
                $created = $this->createInvoiceIfMissing(
                    $contract->id,
                    $currentPeriod,
                    'maintenance',
                    $maintenanceAmount,
                    $dueDate
                );
                if ($created) $invoicesCreated++;
            }

            // 3. Factura de Publicidad (Advertising)
            if ($contract->advertising_pct > 0) {
                $advertisingAmount = ($contract->rent_amount * $contract->advertising_pct) / 100;
                $created = $this->createInvoiceIfMissing(
                    $contract->id,
                    $currentPeriod,
                    'advertising',
                    $advertisingAmount,
                    $dueDate
                );
                if ($created) $invoicesCreated++;
            }
        }

        $this->info("Proceso finalizado. Facturas creadas: {$invoicesCreated}");
    }

    private function createInvoiceIfMissing($contractId, $period, $type, $amount, $dueDate): bool
    {
        // Evitar duplicados
        $exists = Invoice::where('contract_id', $contractId)
            ->where('period', $period)
            ->where('type', $type)
            ->exists();

        if (!$exists) {
            Invoice::create([
                'contract_id' => $contractId,
                'period' => $period,
                'type' => $type,
                'total_amount' => $amount,
                'paid_amount' => 0,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
            return true;
        }

        return false;
    }
}
