<?php

namespace App\Http\Controllers;

use App\Models\Premise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PremiseController extends Controller
{
    public function create(): View
    {
        return view('premises.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:premises,code'],
            'square_meters' => ['required', 'numeric', 'min:0'],
            'suggested_rent' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:available,rented,maintenance'],
        ]);

        Premise::create($data);

        return redirect()
            ->route('premises.index')
            ->with('status', 'Local creado correctamente.');
    }
}
