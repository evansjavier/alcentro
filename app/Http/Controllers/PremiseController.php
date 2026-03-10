<?php

namespace App\Http\Controllers;

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
            'square_meters' => ['required', 'numeric', 'min:0'],
            'suggested_rent' => ['required', 'numeric', 'min:0'],
        ]);

        $data['status'] = Premise::STATUS_AVAILABLE;

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
            'square_meters' => ['required', 'numeric', 'min:0'],
            'suggested_rent' => ['required', 'numeric', 'min:0'],
        ]);

        $premise->update($data);

        return redirect()
            ->route('premises.index')
            ->with('status', 'Local actualizado correctamente.');
    }
}
