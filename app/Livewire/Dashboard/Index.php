<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $selectedYear;

    public function mount()
    {
        $this->selectedYear = Carbon::now()->year;
    }

    public function updatedSelectedYear()
    {
        $monthlyPaymentsChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyPaymentsChart[] = (float) Payment::approved()
                    ->whereYear('payment_date', $this->selectedYear)
                    ->whereMonth('payment_date', $i)
                    ->sum('amount_received');
        }
        $this->dispatch('update-chart', data: $monthlyPaymentsChart);
    }

    public function render()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $currentPeriod = $currentDate->format('Y-m');

        $lastMonth = $currentDate->copy()->subMonth();
        $lastPeriod = $lastMonth->format('Y-m');

        // Pagos del mes (aprobados en el mes actual)
        $paidThisMonth = Payment::approved()
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount_received');

        // Esperados del mes (total facturado en el periodo actual)
        $expectedThisMonth = Invoice::where('period', $currentPeriod)->sum('total_amount');

        $progress = $expectedThisMonth > 0 ? min(100, round(($paidThisMonth / $expectedThisMonth) * 100, 1)) : 0;

        // Facturación del mes y mes anterior para diferencia
        $invoicedThisMonth = $expectedThisMonth; // Mismo concepto para 'periodo' vs 'facturación'
        $invoicedLastMonth = Invoice::where('period', $lastPeriod)->sum('total_amount');

        $invoicedDiff = $invoicedLastMonth > 0
            ? round((($invoicedThisMonth - $invoicedLastMonth) / $invoicedLastMonth) * 100, 1)
            : 0;

        // Pagos recibidos mes anterior
        $paymentsLastMonth = Payment::approved()
            ->whereMonth('payment_date', $lastMonth->month)
            ->whereYear('payment_date', $lastMonth->year)
            ->sum('amount_received');

        $paymentsDiff = $paymentsLastMonth > 0
            ? round((($paidThisMonth - $paymentsLastMonth) / $paymentsLastMonth) * 100, 1)
            : 0;

        // Pagos pendientes (Deuda generada este mes vs Deuda generada el mes anterior)
        $pendingThisMonth = Invoice::where('period', $currentPeriod)
            ->whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIAL])
            ->selectRaw('SUM(total_amount - paid_amount) as debt')
            ->value('debt') ?? 0;

        $pendingLastMonth = Invoice::where('period', $lastPeriod)
            ->whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIAL])
            ->selectRaw('SUM(total_amount - paid_amount) as debt')
            ->value('debt') ?? 0;

        $pendingDiff = $pendingLastMonth > 0
            ? round((($pendingThisMonth - $pendingLastMonth) / $pendingLastMonth) * 100, 1)
            : 0;

        // Chart Data (Pagos por mes en el año actual)
        $monthlyPaymentsChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyPaymentsChart[] = (float) Payment::approved()
                    ->whereYear('payment_date', $this->selectedYear)
                    ->whereMonth('payment_date', $i)
                    ->sum('amount_received');
        }

        // Últimas facturas generadas
        $latestInvoices = Invoice::with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Clientes con mayor deuda
        $clientsWithDebt = Client::with(['invoices' => function($q) {
            $q->whereIn('invoices.status', [Invoice::STATUS_PENDING, Invoice::STATUS_PARTIAL]);
        }])
        ->get()
        ->map(function ($client) {
            // Because invoices are accessed directly via relation
            $invoices = $client->invoices;

            $debt = $invoices->sum(function($invoice) {
                return $invoice->total_amount - $invoice->paid_amount;
            });

            $hasOverdue = $invoices->contains(function($invoice) {
                return Carbon::parse($invoice->due_date)->startOfDay()->isPast();
            });

            return (object) [
                'id' => $client->id,
                'name' => $client->name ?? 'Cliente Anónimo',
                'debt' => $debt,
                'status' => $hasOverdue ? 'Vencida' : 'Pendiente',
                'initial' => strtoupper(substr($client->name ?? 'C', 0, 1)),
            ];
        })
        ->where('debt', '>', 0)
        ->sortByDesc('debt')
        ->take(5)
        ->values();

        return view('livewire.dashboard.index', compact(
            'paidThisMonth',
            'expectedThisMonth',
            'progress',
            'invoicedThisMonth',
            'invoicedDiff',
            'paymentsDiff',
            'pendingThisMonth',
            'pendingDiff',
            'monthlyPaymentsChart',
            'latestInvoices',
            'clientsWithDebt'
        ))->layout('layouts.app');
    }
}
