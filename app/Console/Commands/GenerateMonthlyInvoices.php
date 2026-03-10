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
            // Evaluamos todos los contratos activos del cliente que ya cumplen su fecha de corte
            $contractsToBill = [];
            
            // Para simplificar, el due_date del cliente podría ser el menor dia de pago de sus contratos
            // o se podría generar 1 factura por cada fecha de corte (si el cliente tiene contratos en distintas fechas).
            // Lo ideal es generar la factura del cliente cuando llega su contrato más temprano del mes.
            $earliestPaymentDay = 31;

            foreach ($client->contracts as $contract) {
                $daysInMonth = $today->daysInMonth;
                $paymentDay = min($contract->payment_day, $daysInMonth);
                
                // Si hoy es el dia de pago o después, el contrato entra en facturación
                if ($today->day >= $paymentDay) {
                    $contractsToBill[] = $contract;
                    if ($paymentDay < $earliestPaymentDay) {
                        $earliestPaymentDay = $paymentDay;
                    }
                }
            }

            if (empty($contractsToBill)) {
                continue; // Ningún contrato pendiente de facturar hoy para este cliente
            }

            // Validar que el cliente no tenga ya una factura para este periodo (si es una al mes)
            $existingInvoice = Invoice::where('client_id', $client->id)
                ->where('period', $currentPeriod)
                ->first();

            if ($existingInvoice) {
                // Si la factura mensual del cliente ya existe, no creamos otra.
                continue; 
            }

            // Calculamos due date basado en el earliest payment day de los contratos
            $dueDate = Carbon::create($today->year, $today->month, $earliestPaymentDay)->format('Y-m-d');
            
            DB::transaction(function () use ($client, $contractsToBill, $currentPeriod, $dueDate, &$invoicesCreated) {
                
                $invoice = Invoice::create([
                    'client_id' => $client->id,
                    'period' => $currentPeriod,
                    'total_amount' => 0, // Se calculará sumando los items
                    'paid_amount' => 0,
                    'due_date' => $dueDate,
                    'status' => 'pending', 
                ]);

                $totalAmount = 0;

                foreach ($contractsToBill as $contract) {
                    $premiseCode = $contract->premise?->code ?? 'Local';

                    // 1. Renta
                    if ($contract->rent_amount > 0) {
                        $invoice->items()->create([
                            'contract_id' => $contract->id,
                            'type' => 'rent',
                            'description' => "Renta {$premiseCode} - Periodo {$currentPeriod}",
                            'amount' => $contract->rent_amount
                        ]);
                        $totalAmount += $contract->rent_amount;
                    }

                    // 2. Mantenimiento
                    if ($contract->maintenance_pct > 0) {
                        $maintenanceAmount = ($contract->rent_amount * $contract->maintenance_pct) / 100;
                        $invoice->items()->create([
                            'contract_id' => $contract->id,
                            'type' => 'maintenance',
                            'description' => "Mantenimiento {$premiseCode} ({$contract->maintenance_pct}%)",
                            'amount' => $maintenanceAmount
                        ]);
                        $totalAmount += $maintenanceAmount;
                    }

                    // 3. Publicidad
                    if ($contract->advertising_pct > 0) {
                        $advertisingAmount = ($contract->rent_amount * $contract->advertising_pct) / 100;
                        $invoice->items()->create([
                            'contract_id' => $contract->id,
                            'type' => 'advertising',
                            'description' => "Publicidad {$premiseCode} ({$contract->advertising_pct}%)",
                            'amount' => $advertisingAmount
                        ]);
                        $totalAmount += $advertisingAmount;
                    }
                }

                $invoice->update(['total_amount' => $totalAmount]);
                $invoicesCreated++;
            });
        }

        $this->info("Proceso finalizado. Facturas creadas: {$invoicesCreated}");
    }
}
