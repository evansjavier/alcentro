<div>
    @section('title', __('Registrar Gasto'))
    <div class="grid gap-5 lg:gap-7.5 xl:w-[44rem] mx-auto">
        <form class="grid gap-5" wire:submit="save">
            <div class="kt-card pb-2.5">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">{{ __('Registrar nuevo gasto') }}</h3>
                    <div class="flex items-center gap-2 text-sm text-secondary-foreground">
                        {{ __('Registra los datos del egreso y añade los comprobantes de pago.') }}
                    </div>
                </div>


                <div class="kt-card-content grid gap-5">
                    <!-- Separador de sección -->
                    <div class="pt-2 pb-0">
                        <h4 class="text-sm font-semibold text-foreground uppercase tracking-wider">{{ __('Información Principal') }}</h4>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="expense_concept_id">{{ __('Concepto') }}</label>
                        <div class="grow flex gap-2">
                            <select class="kt-input flex-grow" id="expense_concept_id" wire:model="expense_concept_id">
                                <option value="">{{ __('Selecciona concepto') }}</option>
                                @foreach ($concepts as $concept)
                                    <option value="{{ $concept->id }}">
                                        {{ $concept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <a class="kt-btn kt-btn-outline kt-btn-icon text-secondary-foreground" href="{{ route('expense_concepts.index') }}" title="Administrar Conceptos">
                                <i class="ki-outline ki-setting-2"></i>
                            </a>
                        </div>
                        @error('expense_concept_id')
                            <div class="w-full lg:w-auto"><p class="text-sm text-destructive mt-1">{{ $message }}</p></div>
                        @enderror
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="amount">{{ __('Monto Total') }}</label>
                        <div class="grow relative">
                            <input class="kt-input pl-8" id="amount" wire:model="amount" placeholder="{{ __('Ej. 1500.50') }}" type="number" step="0.01" min="0.01">
                            @error('amount')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="expense_date">{{ __('Fecha del Gasto') }}</label>
                        <div class="grow">
                            <input class="kt-input" id="expense_date" wire:model="expense_date" type="date">
                            @error('expense_date')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-input mt-2 mb-1"></div>
                    <div class="pt-2 pb-2">
                        <h4 class="text-sm font-semibold text-foreground uppercase tracking-wider">{{ __('Detalles de Pago y Comprobantes') }}</h4>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="payment_method">{{ __('Método de Pago') }}</label>
                        <div class="grow">
                            <select class="kt-input" id="payment_method" wire:model="payment_method">
                                @foreach($paymentMethods as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="reference_number">{{ __('Referencia / Factura') }}</label>
                        <div class="grow">
                            <input class="kt-input" id="reference_number" wire:model="reference_number" type="text" placeholder="{{ __('Opcional') }}">
                            @error('reference_number')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56" for="attachment">{{ __('Comprobante') }}</label>
                        <div class="grow">
                            <input class="kt-input !py-1.5" id="attachment" wire:model="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-xs text-muted-foreground mt-1">{{ __('Opcional. Formatos permitidos: PDF, JPG, PNG (Max: 5MB).') }}</p>
                            @error('attachment')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Preview para imágenes -->
                            @if ($attachment && in_array($attachment->extension(), ['jpg', 'jpeg', 'png', 'webp']))
                                <div class="mt-3 relative w-32 h-32 border border-input rounded-md overflow-hidden bg-muted flex items-center justify-center">
                                    <img src="{{ $attachment->temporaryUrl() }}" class="object-cover w-full h-full" alt="Preview">
                                </div>
                            @elseif ($attachment && $attachment->extension() == 'pdf')
                                <div class="mt-3 p-3 bg-red-500/10 text-red-700 border border-red-200 rounded-md flex items-center gap-2">
                                    <i class="ki-outline ki-document text-xl"></i>
                                    <span class="text-sm font-medium">{{ $attachment->getClientOriginalName() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-2.5">
                        <label class="kt-form-label max-w-56" for="notes">{{ __('Notas Adicionales') }}</label>
                        <div class="grow">
                            <textarea class="kt-input min-h-[100px] pt-2" id="notes" wire:model="notes" placeholder="{{ __('Observaciones sobre este gasto...') }}" rows="3"></textarea>
                            @error('notes')
                                <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a class="kt-btn kt-btn-outline" href="{{ route('expenses.index') }}">
                    {{ __('Cancelar') }}
                </a>
                <button class="kt-btn kt-btn-primary" type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">{{ __('Guardar gasto') }}</span>
                    <span wire:loading wire:target="save">{{ __('Guardando...') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
