@section('title', __('Detalles del gasto'))

<div class="container-fixed">
    @if(session('status'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center gap-2 mb-4 text-sm font-medium border border-green-100">
            <i class="ki-outline ki-check-circle text-xl"></i>
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-gray-900 flex items-center gap-2">
                Gasto #{{ $expense->id }}
                @if($expense->is_approved)
                    <span class="badge badge-success badge-outline border px-2.5 py-1 text-xs font-medium rounded-full bg-green-500/10 text-green-700 border-green-200">Aprobado</span>
                @else
                    <span class="badge badge-warning badge-outline border px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-500/10 text-yellow-700 border-yellow-200">Pendiente</span>
                @endif
            </h1>
            <div class="flex items-center gap-2 text-sm font-normal text-gray-700">
                Información registrada el {{ $expense->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            @if(!$expense->is_approved)
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'approval-modal')" class="kt-btn kt-btn-success">
                <i class="ki-outline ki-check-circle"></i> Aprobar Gasto
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Tarjeta de Información -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="card">
                <div class="card-header px-6 py-4 border-b border-gray-200">
                    <h3 class="card-title text-base font-medium text-gray-900">
                        Resumen del Gasto
                    </h3>
                </div>
                <div class="card-body p-6 flex flex-col gap-5">

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Monto Total</span>
                        <div class="text-2xl font-bold text-gray-900 mt-1">
                            ${{ number_format($expense->amount, 2) }}
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Concepto</span>
                        <div class="text-base font-medium text-gray-800 mt-1">
                            {{ $expense->concept->name ?? 'Concepto Eliminado' }}
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha del Gasto</span>
                        <div class="text-sm text-gray-800 mt-1">
                            {{ $expense->expense_date->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Método Pago</span>
                            <div class="mt-1">
                                <span class="badge badge-light badge-outline">
                                    {{ \App\Models\Expense::$paymentMethods[$expense->payment_method] ?? $expense->payment_method }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Referencia</span>
                            <div class="text-sm text-gray-800 mt-1">
                                {{ $expense->reference_number ?: '---' }}
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Registrado por</span>
                        <div class="text-sm flex items-center gap-2 mt-1">
                            <i class="ki-outline ki-profile-circle text-gray-500"></i>
                            {{ $expense->user->name ?? 'Sistema' }}
                        </div>
                    </div>

                    @if($expense->notes)
                        <div class="border-t border-gray-100 pt-4">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notas</span>
                            <div class="text-sm text-gray-700 mt-1 bg-gray-50 p-3 rounded-md border border-gray-100 italic">
                                "{!! nl2br(e($expense->notes)) !!}"
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Tarjeta del Comprobante -->
        <div class="lg:col-span-2">
            <div class="card h-full">
                <div class="card-header px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="card-title text-base font-medium text-gray-900">
                        Comprobante / Recibo
                    </h3>

                    @if($expense->attachment_path)
                        <button wire:click="downloadAttachment" class="kt-btn kt-btn-outline kt-btn-sm">
                            <i class="ki-outline ki-file-down"></i> Descargar
                        </button>
                    @endif
                </div>

                <div class="card-body p-6 flex items-center justify-center min-h-[400px] bg-gray-50 rounded-b-xl">
                    @if($expense->attachment_path)
                        @php
                            $extension = pathinfo($expense->attachment_path, PATHINFO_EXTENSION);
                            $url = asset('storage/' . $expense->attachment_path);
                        @endphp

                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                            <div class="max-w-full overflow-auto flex justify-center">
                                <img src="{{ $url }}" alt="Comprobante de Gasto" class="rounded shadow-sm border border-gray-200 object-contain" style="max-height: 600px;">
                            </div>
                        @elseif(strtolower($extension) === 'pdf')
                            <iframe src="{{ $url }}" class="w-full border border-gray-200 rounded" frameborder="0" style="height: 600px;"></iframe>
                        @else
                            <div class="text-center p-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ki-outline ki-file text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-1">Archivo de Comprobante</h4>
                                <p class="text-sm text-gray-500 mb-4">Formato no previsualizable ({{ strtoupper($extension) }})</p>
                                <button wire:click="downloadAttachment" class="btn btn-light">
                                    <i class="ki-outline ki-file-down"></i> Descargar Archivo
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-gray-500 flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400">
                                <i class="ki-outline ki-document text-2xl"></i>
                            </div>
                            <p>No se adjuntó ningún comprobante para este gasto.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Modal de confirmación -->
    <x-modal name="approval-modal" maxWidth="md">
        <div class="">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-mono">Confirmar Aprobación</h3>
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="text-secondary-foreground hover:text-primary transition-colors">
                    <i class="ki-outline ki-cross text-xl"></i>
                </button>
            </div>

            <div class="kt-modal-body space-y-4 p-0 pr-1 pb-2">
                <p class="text-muted-foreground text-sm">
                    ¿Estás seguro de que deseas aprobar este gasto por <strong class="text-foreground">${{ number_format($expense->amount, 2) }}</strong>?
                </p>
                <div class="bg-yellow-50 text-yellow-800 p-3 rounded text-xs flex gap-2">
                    <i class="ki-outline ki-warning text-base mt-0.5"></i>
                    <span>Una vez aprobado, este gasto no podrá ser editado.</span>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-input flex flex-wrap items-center justify-end gap-3">
                <button type="button" @click="$dispatch('close-modal', 'approval-modal')" class="kt-btn kt-btn-outline text-secondary-foreground">Cancelar</button>
                <button wire:click="approveExpense" wire:loading.attr="disabled" class="kt-btn kt-btn-success">
                    <span wire:loading.remove wire:target="approveExpense">Sí, Aprobar Gasto</span>
                    <span wire:loading wire:target="approveExpense" style="display: none;">
                        <i class="ki-outline ki-loading animate-spin"></i> Aprobando...
                    </span>
                </button>
            </div>
        </div>
    </x-modal>
</div>
