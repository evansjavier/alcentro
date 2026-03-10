<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Premise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PremiseController extends Controller
{
    public function create(): View
    {
        return view('premises.create');
    }

    public function edit(Premise $premise): View
    {
        return view('premises.create', compact('premise'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:premises,code'],
            'square_meters' => ['required', 'numeric', 'gt:0'],
            'suggested_rent' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in([Premise::STATUS_AVAILABLE, Premise::STATUS_MAINTENANCE])],
        ]);

        Premise::create($data);

        return redirect()
            ->route('premises.index')
            ->with('status', 'Local creado correctamente.');
    }

    public function update(Request $request, Premise $premise): RedirectResponse
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('premises', 'code')->ignore($premise->id)
            ],
            'square_meters' => ['required', 'numeric', 'gt:0'],
            'suggested_rent' => ['required', 'numeric', 'min:0'],
            'status' => [
                'sometimes',
                Rule::in([Premise::STATUS_AVAILABLE, Premise::STATUS_MAINTENANCE]),
                function ($attribute, $value, $fail) use ($premise) {
                    if ($value === Premise::STATUS_MAINTENANCE) {
                        $hasActiveContract = Contract::where('premise_id', $premise->id)
                            ->whereIn('status', [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE])
                            ->exists();

                        if ($hasActiveContract) {
                            $fail('No se puede cambiar a mantenimiento porque hay un contrato activo o pendiente asociado a este local.');
                        }
                    }
                }
            ],
        ]);

        // Evitar que quiten el status "rented" si no es intencional o solo enviaron disponible
        // (Aunque si no pueden enviarlo si está alquilado desde la vista es mejor, pero protegemos el backend)
        $hasActiveContract = Contract::where('premise_id', $premise->id)
            ->whereIn('status', [Contract::STATUS_ACTIVO, Contract::STATUS_PENDIENTE])
            ->exists();

        if ($hasActiveContract && isset($data['status'])) {
            unset($data['status']); // Ignorar el cambio de estado si tiene contrato activo
        }

        $premise->update($data);

        return redirect()
            ->route('premises.index')
            ->with('status', 'Local actualizado correctamente.');
    }
}
