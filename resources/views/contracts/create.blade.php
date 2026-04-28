@extends('layouts.app')

@section('title', __('Asignar contrato'))
@section('content')
    <div class="grid gap-5 lg:gap-7.5 xl:w-[44rem] mx-auto">
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

        @if ($errors->any())
            <div class="kt-alert kt-alert-destructive">
                <div class="flex items-start gap-3">
                    <i class="ki-filled ki-information"></i>
                    <div class="flex flex-col gap-1">
                        <span class="font-semibold">{{ __('Corrige los campos marcados para continuar.') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <form class="grid gap-5" id="contracts_form" method="POST" action="{{ route('contracts.store') }}">
            @csrf
            <div class="kt-card pb-2.5">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">{{ __('Asignar contrato') }}</h3>
                    <div class="flex items-center gap-2 text-sm text-secondary-foreground">
                        {{ __('Selecciona la empresa, el local y define los términos del contrato.') }}
                    </div>
                </div>
                <div class="kt-card-content grid gap-5">
                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="client_id">{{ __('Empresa') }}</label>
                        <div class="grow">
                            <select class="kt-input" id="client_id" name="client_id">
                                <option value="">{{ __('Selecciona empresa') }}</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="premise_id">{{ __('Local') }}</label>
                        <div class="grow">
                            <select class="kt-input" id="premise_id" name="premise_id">
                                <option value="">{{ __('Selecciona local disponible') }}</option>
                                @foreach ($premises as $premise)
                                    <option value="{{ $premise->id }}" {{ old('premise_id', $selectedPremiseId) == $premise->id ? 'selected' : '' }}>
                                        {{ $premise->code }} — {{ number_format((float) $premise->square_meters, 2, '.', ',') }} m²
                                    </option>
                                @endforeach
                            </select>
                            @error('premise_id')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="rent_amount">{{ __('Canon mensual') }}</label>
                        <div class="grow">
                            <input class="kt-input" id="rent_amount" name="rent_amount" value="{{ old('rent_amount') }}" placeholder="{{ __('Ej. 4500000') }}" type="number" step="0.01" min="0">
                            @error('rent_amount')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-baseline flex-wrap  gap-2.5 flex-1 min-w-[220px]">
                            <label class="kt-form-label max-w-40" for="payment_day">{{ __('Día de pago') }}</label>
                            <div class="grow">
                                <input class="kt-input" id="payment_day" name="payment_day" value="{{ old('payment_day', min(\Carbon\Carbon::today()->day, 28)) }}" type="number" min="1" max="28">
                                <p class="text-xs text-muted-foreground mt-1">{{ __('Se facturará cada mes, en el día elegido.') }}</p>
                                @error('payment_day')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 flex-1 min-w-[220px]">
                            <label class="kt-form-label max-w-40" for="maintenance_pct">{{ __('% Mantenimiento') }}</label>
                            <div class="grow">
                                <input class="kt-input" id="maintenance_pct" name="maintenance_pct" value="{{ old('maintenance_pct', 10) }}" type="number" step="0.01" min="0">
                                @error('maintenance_pct')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 flex-1 min-w-[220px]">
                            <label class="kt-form-label max-w-40" for="advertising_pct">{{ __('% Publicidad') }}</label>
                            <div class="grow">
                                <input class="kt-input" id="advertising_pct" name="advertising_pct" value="{{ old('advertising_pct', 10) }}" type="number" step="0.01" min="0">
                                @error('advertising_pct')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 flex-1 min-w-[220px]">
                            <label class="kt-form-label max-w-40" for="start_date">{{ __('Fecha inicio') }}</label>
                            <div class="grow">
                                <input class="kt-input" id="start_date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" type="date">
                                @error('start_date')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 flex-1 min-w-[220px]">
                            <label class="kt-form-label max-w-40" for="end_date">{{ __('Fecha fin') }}</label>
                            <div class="grow">
                                <input class="kt-input" id="end_date" name="end_date" value="{{ old('end_date') }}" type="date">
                                @error('end_date')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="status">{{ __('Estado del contrato') }}</label>
                        <div class="grow">
                            @php
                                $selectedStatus = old('status', 'activo');
                            @endphp
                            <select class="kt-input" id="status" name="status">
                                <option value="activo" {{ $selectedStatus === 'activo' ? 'selected' : '' }}>{{ __('Activo') }}</option>
                                <option value="pendiente_firma" {{ $selectedStatus === 'pendiente_firma' ? 'selected' : '' }}>{{ __('Pendiente de firma') }}</option>
                                <option value="finalizado" {{ $selectedStatus === 'finalizado' ? 'selected' : '' }}>{{ __('Finalizado') }}</option>
                                <option value="rescindido" {{ $selectedStatus === 'rescindido' ? 'selected' : '' }}>{{ __('Rescindido') }}</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-2.5">
                        <label class="kt-form-label max-w-56" for="notes">{{ __('Notas') }}</label>
                        <div class="grow">
                            <textarea class="kt-input min-h-[120px] pt-2" id="notes" name="notes" placeholder="{{ __('Observaciones adicionales') }}" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-card pb-2.5 mt-5">
                <div class="kt-card-header py-4">
                    <h3 class="kt-card-title">{{ __('Servicios') }}</h3>
                    <div class="flex items-center gap-2 text-sm text-secondary-foreground">
                        {{ __('Selecciona los conceptos que aplican, su frecuencia de facturación y, de ser fija, una cuota adicional.') }}
                    </div>
                </div>
                <div class="kt-card-content pt-5 pb-2">
                    @foreach ($billableConcepts as $index => $concept)
                        <div class="flex flex-col py-3">
                            <div class="flex items-center flex-wrap sm:flex-nowrap w-full justify-between gap-3.5">
                                <div class="flex md:items-center gap-3.5 w-full sm:w-auto">
                                    <div class="">
                                        <input type="checkbox" name="concepts[{{ $concept->id }}][selected]" value="1" class="kt-switch" id="concept_{{ $concept->id }}" {{ old("concepts.{$concept->id}.selected") ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col justify-center gap-1.5 -mt-1 hover:cursor-pointer">
                                        <div class="text-sm font-medium text-dark leading-5.5">{{ $concept->name }}</div>
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-xs font-normal text-secondary-foreground">{{ __('Frecuencia sugerida:') }} <span class="font-medium text-foreground">{{ $concept->billing_period_months }} {{ trans_choice('mes|meses', $concept->billing_period_months) }}</span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap sm:flex-nowrap items-start text-end gap-3.5 ml-auto">
                                    <div class="flex flex-col gap-1 text-start sm:text-end">
                                        <label for="concept_frequency_{{ $concept->id }}" class="text-xs font-medium text-dark">Meses</label>
                                        <input type="number" name="concepts[{{ $concept->id }}][billing_period_months]" id="concept_frequency_{{ $concept->id }}" class="kt-input w-[80px]" placeholder="Meses" value="{{ old("concepts.{$concept->id}.billing_period_months", $concept->billing_period_months) }}" step="1" min="1" title="Frecuencia en meses">
                                    </div>
                                    <div class="flex flex-col gap-1 text-start sm:text-end">
                                        <label for="concept_amount_{{ $concept->id }}" class="text-xs font-medium text-dark">Monto (opcional)</label>
                                        <input type="number" name="concepts[{{ $concept->id }}][amount]" id="concept_amount_{{ $concept->id }}" class="kt-input w-[120px]" placeholder="Ej. 1500" value="{{ old("concepts.{$concept->id}.amount") }}" step="0.01" min="0">
                                        <span class="text-[10px] text-muted-foreground">{{ __('Dejar vacío si es variable') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full my-3.5"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-5">
                <a class="kt-btn kt-btn-outline" href="{{ route('premises.index') }}">
                    {{ __('Cancelar') }}
                </a>
                <button class="kt-btn kt-btn-primary" type="submit">
                    <span>{{ __('Guardar contrato') }}</span>
                </button>
            </div>
        </form>
    </div>
@endsection
