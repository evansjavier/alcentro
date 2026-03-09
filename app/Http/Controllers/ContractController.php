<?php

namespace App\Http\Controllers;

use App\Models\Client;
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
        $premises = Premise::where('status', 'available')
            ->orderBy('code')
            ->get(['id', 'code', 'square_meters']);

        return view('contracts.create', [
            'clients' => $clients,
            'premises' => $premises,
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
                Rule::exists('premises', 'id')->where(fn ($q) => $q->where('status', 'available')),
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
        ]);

        DB::transaction(function () use ($data) {
            $premise = Premise::lockForUpdate()->findOrFail($data['premise_id']);

            // Double-check availability in transaction
            if ($premise->status !== 'available') {
                abort(422, 'El local ya no está disponible.');
            }

            Contract::create($data);

            if (in_array($data['status'], [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE], true)) {
                $premise->update(['status' => 'rented']);
            }
        });

        return redirect()
            ->route('premises.index')
            ->with('status', 'Contrato creado y local asignado correctamente.');
    }
}
