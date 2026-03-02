@extends('layouts.app')

@php
    $isEdit = isset($premise);
@endphp

@section('title', $isEdit ? __('Editar local') : __('Crear local'))
@section('content')
    <div class="grid gap-5 lg:gap-7.5 xl:w-[38.75rem] mx-auto">
        @if (session('status'))
            <div class="kt-alert kt-alert-success">
                <div class="flex items-start gap-3">
                    <i class="ki-filled ki-check"></i>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <form class="grid gap-5" id="premises_form" method="POST" action="{{ $isEdit ? route('premises.update', $premise) : route('premises.store') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="kt-card pb-2.5">
                <div class="kt-card-header" id="basic_settings">
                    <h3 class="kt-card-title">
                        {{ $isEdit ? __('Editar local') : __('Datos del local') }}
                    </h3>
                    <div class="flex items-center gap-2 text-sm text-secondary-foreground">
                        {{ $isEdit ? __('Actualiza la información del local.') : __('Registra la información básica del local.') }}
                    </div>
                </div>
                <div class="kt-card-content grid gap-5">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="code">
                            {{ __('Código') }}
                        </label>
                        <div class="grow">
                            <input class="kt-input" id="code" name="code" value="{{ old('code', $premise->code ?? '') }}" placeholder="{{ __('Ej. LOC-101') }}" type="text">
                            @error('code')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="square_meters">
                            {{ __('Metros cuadrados') }}
                        </label>
                        <div class="grow">
                            <input class="kt-input" id="square_meters" name="square_meters" value="{{ old('square_meters', $premise->square_meters ?? '') }}" placeholder="{{ __('Ej. 78.50') }}" type="number" step="0.01" min="0">
                            @error('square_meters')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="suggested_rent">
                            {{ __('Canon sugerido') }}
                        </label>
                        <div class="grow">
                            <input class="kt-input" id="suggested_rent" name="suggested_rent" value="{{ old('suggested_rent', $premise->suggested_rent ?? '') }}" placeholder="{{ __('Ej. 4500000') }}" type="number" step="0.01" min="0">
                            @error('suggested_rent')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-2.5">
                        <label class="kt-form-label max-w-56" for="status">
                            {{ __('Estado') }}
                        </label>
                        <div class="grow">
                            @php
                                $selectedStatus = old('status', $premise->status ?? 'available');
                            @endphp
                            <select class="kt-input" id="status" name="status">
                                <option value="available" {{ $selectedStatus === 'available' ? 'selected' : '' }}>{{ __('Disponible') }}</option>
                                <option value="rented" {{ $selectedStatus === 'rented' ? 'selected' : '' }}>{{ __('Arrendado') }}</option>
                                <option value="maintenance" {{ $selectedStatus === 'maintenance' ? 'selected' : '' }}>{{ __('Mantenimiento') }}</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a class="kt-btn kt-btn-outline" href="{{ route('premises.index') }}">
                    {{ __('Cancelar') }}
                </a>
                <button class="kt-btn kt-btn-primary" type="submit">
                    <span>{{ $isEdit ? __('Guardar cambios') : __('Guardar local') }}</span>
                </button>
            </div>
        </form>
    </div>
@endsection
