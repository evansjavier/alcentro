<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Concept;
use App\Models\Contract;
use App\Models\Premise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContractController extends Controller
{

    public const STATUSES = [
        Contract::STATUS_ACTIVO,
        Contract::STATUS_PENDIENTE,
        Contract::STATUS_FINALIZADO,
        Contract::STATUS_RESCINDIDO,
    ];

    public function create(Request $request): View
    {
        $selectedPremiseId = $request->integer('premise');

        $clients = Client::orderBy('name')->get(['id', 'name']);
        $premises = Premise::where('status', Premise::STATUS_AVAILABLE)
            ->orderBy('code')
            ->get(['id', 'code', 'square_meters']);
        $billableConcepts = Concept::billable()->orderBy('name')->get(['id', 'name', 'billing_period_months']);

        return view('contracts.create', [
            'clients' => $clients,
            'premises' => $premises,
            'billableConcepts' => $billableConcepts,
            'selectedPremiseId' => $selectedPremiseId,
        ]);
    }

    public function show(Contract $contract): View
    {
        $contract->load(['client', 'premise']);

        return view('contracts.show', compact('contract'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['required', Rule::exists('clients', 'id')],
            'premise_id' => [
                'required',
                Rule::exists('premises', 'id')->where(fn ($q) => $q->where('status', Premise::STATUS_AVAILABLE)),
            ],
            'rent_amount' => ['required', 'numeric', 'min:0'],
            'payment_day' => ['required', 'integer', 'min:1', 'max:28'],
            'maintenance_pct' => ['required', 'numeric', 'min:0', 'max:50'],
            'advertising_pct' => ['required', 'numeric', 'min:0', 'max:50'],
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->premise_id) return;

                    $endDate = $request->end_date;

                    $overlap = Contract::where('premise_id', $request->premise_id)
                        ->whereIn('status', [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE])
                        ->where(function ($query) use ($value, $endDate) {
                            $query->where('start_date', '<=', $endDate ?? '9999-12-31')
                                ->where(function ($q) use ($value) {
                                    $q->where('end_date', '>=', $value)
                                      ->orWhereNull('end_date');
                                });
                        })->exists();

                    if ($overlap) {
                        $fail('El local seleccionado ya tiene un contrato activo que se solapa con este rango de fechas.');
                    }
                },
            ],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(self::STATUSES)],
            'notes' => ['nullable', 'string'],
            'concepts' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $keys = array_keys($value);
                    if (Concept::whereIn('id', $keys)->count() !== count($keys)) {
                        $fail('Se seleccionó un concepto que no es válido o no existe.');
                    }
                }
            ],
            'concepts.*.selected' => ['nullable', 'boolean'],
            'concepts.*.amount' => ['nullable', 'numeric', 'min:0'],
            'concepts.*.billing_period_months' => ['required_with:concepts.*.selected', 'nullable', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($data) {
            $premise = Premise::lockForUpdate()->findOrFail($data['premise_id']);

            // Double-check availability in transaction
            if ($premise->status !== Premise::STATUS_AVAILABLE) {
                abort(422, 'El local ya no está disponible.');
            }

            $contract = Contract::create($data);

            if (in_array($data['status'], [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE], true)) {
                $premise->update(['status' => Premise::STATUS_RENTED]);
            }
            if (!empty($data['concepts'])) {
                $conceptsToAttach = collect($data['concepts'])
                    ->filter(fn($concept) => !empty($concept['selected']))
                    ->map(fn($concept) => [
                        'billing_period_months' => $concept['billing_period_months'],
                        'amount' => $concept['amount'] ?? null,
                    ]);

                if ($conceptsToAttach->isNotEmpty()) {
                    $contract->concepts()->attach($conceptsToAttach);
                }
            }

            if ($contract->status === Contract::STATUS_ACTIVO) {
                $today = \Carbon\Carbon::today();
                $currentPeriod = $today->format('Y-m');

                // 1. Crear factura borrador si hay conceptos
                if (isset($conceptsToAttach) && $conceptsToAttach->isNotEmpty()) {
                    $draftInvoice = \App\Models\Invoice::create([
                        'client_id' => $contract->client_id,
                        'contract_id' => $contract->id,
                        'period' => $currentPeriod,
                        'total_amount' => 0,
                        'paid_amount' => 0,
                        'due_date' => \Carbon\Carbon::create($today->year, $today->month, min($contract->payment_day, $today->daysInMonth))->format('Y-m-d'),
                        'status' => \App\Models\Invoice::STATUS_PENDING,
                        'document_status' => \App\Models\Invoice::DOC_STATUS_DRAFT,
                    ]);

                    $draftTotal = 0;
                    foreach ($data['concepts'] as $conceptId => $conceptData) {
                        if (empty($conceptData['selected'])) continue;
                        
                        $conceptModel = \App\Models\Concept::find($conceptId);
                        $amt = $conceptData['amount'] ?? null;
                        
                        $startDate = \Carbon\Carbon::parse($data['start_date']);
                        $months = (int) ($conceptData['billing_period_months'] ?? 1);
                        $endDate = $startDate->copy()->addMonths($months);
                        
                        $conceptName = $conceptModel ? $conceptModel->name : "Concepto Adicional";
                        $description = "{$conceptName}: {$startDate->format('d/m/Y')} al {$endDate->format('d/m/Y')}";
                        
                        $draftInvoice->items()->create([
                            'contract_id' => $contract->id,
                            'concept_id' => $conceptId,
                            'type' => \App\Models\InvoiceItem::TYPE_UTILITIES,
                            'description' => $description,
                            'amount' => $amt,
                        ]);
                        
                        if ($amt !== null) {
                            $draftTotal += $amt;
                        }
                    }
                    
                    $draftInvoice->total_amount = $draftTotal;
                    $draftInvoice->save();
                }

                // 2. Factura cerrada (regular) para las rentas/mantenimiento
                $invoice = \App\Models\Invoice::firstOrCreate(
                    [
                        'client_id' => $contract->client_id,
                        'contract_id' => $contract->id,
                        'period' => $currentPeriod,
                        'document_status' => \App\Models\Invoice::DOC_STATUS_ISSUED,
                    ],
                    [
                        'total_amount' => 0,
                        'paid_amount' => 0,
                        'due_date' => \Carbon\Carbon::create($today->year, $today->month, min($contract->payment_day, $today->daysInMonth))->format('Y-m-d'),
                        'status' => \App\Models\Invoice::STATUS_PENDING,
                    ]
                );

                $itemsTotal = $invoice->generateItemsFromContract($contract, $currentPeriod);

                $invoice->total_amount += $itemsTotal;
                $invoice->recalculateStatus();
            }
        });

        return redirect()
            ->route('contracts.index')
            ->with('status', 'Contrato creado y local asignado correctamente.');
    }

    public function terminate(Contract $contract): View
    {
        $contract->load(['client', 'premise']);

        return view('contracts.terminate', compact('contract'));
    }

    public function processTermination(Request $request, Contract $contract): RedirectResponse
    {
        $data = $request->validate([
            'end_date' => ['required', 'date', 'after_or_equal:' . $contract->start_date?->format('Y-m-d')],
            'closing_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($contract, $data) {
            $contract->update([
                'status' => Contract::STATUS_FINALIZADO,
                'end_date' => $data['end_date'],
                'closing_note' => $data['closing_note'],
                'closed_at' => now(),
            ]);

            if ($contract->premise_id) {
                // Return premise to available
                Premise::where('id', $contract->premise_id)->update(['status' => Premise::STATUS_AVAILABLE]);
            }
        });

        return redirect()
            ->route('contracts.show', $contract)
            ->with('status', 'Contrato finalizado correctamente.');
    }
}
