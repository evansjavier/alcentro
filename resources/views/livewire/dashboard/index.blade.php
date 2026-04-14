<div wire:ignore.self>

    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-1">
            <div class="kt-card h-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        Highlights
                    </h3>
                    <div class="kt-menu" data-kt-menu="true">
                        <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px"
                            data-kt-menu-item-placement="bottom-start" data-kt-menu-item-toggle="dropdown"
                            data-kt-menu-item-trigger="click">
                            <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                <i class="ki-filled ki-dots-vertical text-lg">
                                </i>
                            </button>
                            <div class="kt-menu-dropdown kt-menu-default w-full max-w-[200px]"
                                data-kt-menu-dismiss="true">
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="/metronic/tailwind/demo1/account/activity">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-cloud-change">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Activity
                                        </span>
                                    </a>
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" data-kt-modal-toggle="#share_profile_modal" href="#">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-share">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Share
                                        </span>
                                    </a>
                                </div>
                                <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="-15px, 0"
                                    data-kt-menu-item-placement="right-start" data-kt-menu-item-toggle="dropdown"
                                    data-kt-menu-item-trigger="click|lg:hover">
                                    <div class="kt-menu-link">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-notification-status">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Notifications
                                        </span>
                                        <span class="kt-menu-arrow">
                                            <i class="ki-filled ki-right text-xs rtl:transform rtl:rotate-180">
                                            </i>
                                        </span>
                                    </div>
                                    <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]">
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link"
                                                href="/metronic/tailwind/demo1/account/home/settings-sidebar">
                                                <span class="kt-menu-icon">
                                                    <i class="ki-filled ki-sms">
                                                    </i>
                                                </span>
                                                <span class="kt-menu-title">
                                                    Email
                                                </span>
                                            </a>
                                        </div>
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link"
                                                href="/metronic/tailwind/demo1/account/home/settings-sidebar">
                                                <span class="kt-menu-icon">
                                                    <i class="ki-filled ki-message-notify">
                                                    </i>
                                                </span>
                                                <span class="kt-menu-title">
                                                    SMS
                                                </span>
                                            </a>
                                        </div>
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link"
                                                href="/metronic/tailwind/demo1/account/home/settings-sidebar">
                                                <span class="kt-menu-icon">
                                                    <i class="ki-filled ki-notification-status">
                                                    </i>
                                                </span>
                                                <span class="kt-menu-title">
                                                    Push
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" data-kt-modal-toggle="#report_user_modal" href="#">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-dislike">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Report
                                        </span>
                                    </a>
                                </div>
                                <div class="kt-menu-separator">
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link"
                                        href="/metronic/tailwind/demo1/account/home/settings-enterprise">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-setting-3">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Settings
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-card-content flex flex-col gap-4 p-5 lg:p-7.5 lg:pt-4">
                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-normal text-secondary-foreground">
                            Pagos del mes vs esperados
                        </span>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="text-3xl font-semibold text-mono">
                                ${{ number_format($paidThisMonth, 0, '.', ',') }}
                            </span>
                            <span class="kt-badge kt-badge-outline kt-badge-primary kt-badge-sm">
                                de ${{ number_format($expectedThisMonth, 0, '.', ',') }} esperados
                            </span>
                            <span class="kt-badge kt-badge-success kt-badge-sm">
                                {{ $progress }}%
                            </span>
                        </div>
                    </div>
                    <div class="border-b border-input">
                    </div>
                    <div class="grid gap-3">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-1.5">
                                <i class="ki-filled ki-wallet text-base text-muted-foreground">
                                </i>
                                <span class="text-sm font-normal text-mono">
                                    Facturación del mes
                                </span>
                            </div>
                            <div class="flex items-center text-sm font-medium text-foreground gap-6">
                                <span class="lg:text-right">
                                    ${{ number_format($invoicedThisMonth / 1000, 1) }}k
                                </span>
                                <span class="lg:text-right">
                                    @if($invoicedDiff >= 0)
                                    <i class="ki-filled ki-arrow-up text-green-500"></i>
                                    @else
                                    <i class="ki-filled ki-arrow-down text-destructive"></i>
                                    @endif
                                    {{ abs($invoicedDiff) }}%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-1.5">
                                <i class="ki-filled ki-bank text-base text-muted-foreground">
                                </i>
                                <span class="text-sm font-normal text-mono">
                                    Pagos recibidos
                                </span>
                            </div>
                            <div class="flex items-center text-sm font-medium text-foreground gap-6">
                                <span class="lg:text-right">
                                    ${{ number_format($paidThisMonth / 1000, 1) }}k
                                </span>
                                <span class="lg:text-right">
                                    @if($paymentsDiff >= 0)
                                    <i class="ki-filled ki-arrow-up text-green-500"></i>
                                    @else
                                    <i class="ki-filled ki-arrow-down text-destructive"></i>
                                    @endif
                                    {{ abs($paymentsDiff) }}%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-1.5">
                                <i class="ki-filled ki-timer text-base text-muted-foreground">
                                </i>
                                <span class="text-sm font-normal text-mono">
                                    Pagos pendientes
                                </span>
                            </div>
                            <div class="flex items-center text-sm font-medium text-foreground gap-6">
                                <span class="lg:text-right">
                                    ${{ number_format($pendingThisMonth / 1000, 1) }}k
                                </span>
                                <span class="lg:text-right">
                                    @if($pendingDiff >= 0)
                                    <i class="ki-filled ki-arrow-up text-destructive"></i>
                                    @else
                                    <i class="ki-filled ki-arrow-down text-green-500"></i>
                                    @endif
                                    {{ abs($pendingDiff) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="kt-card h-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        Pagos por mes
                    </h3>
                    <div class="flex gap-5" wire:ignore>
                        <select id="kt_select_year" class="kt-select min-w-[120px]" data-kt-select="true"
                            data-kt-select-placeholder="Periodo" name="periodo">
                            <option></option>
                            @for($year = 2026; $year <= date('Y'); $year++) <option value="{{ $year }}" {{
                                $selectedYear==$year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="kt-card-content flex flex-col justify-end items-stretch grow px-3 py-1">
                    <div id="earnings_chart" wire:ignore style="min-height: 265px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch mt-5">
        <div class="lg:col-span-2">
            <div class="kt-card h-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Últimas facturas generadas</h3>
                </div>
                <div class="kt-card-table">
                    <table class="kt-table">
                        <thead>
                            <tr>
                                <th class="text-start">Factura</th>
                                <th class="text-start">Cliente</th>
                                <th class="text-end">Monto</th>
                                <th class="text-end">Estado</th>
                                <th class="text-end">Vencimiento</th>
                                <th class="w-[30px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestInvoices as $invoice)
                            <tr>
                                <td class="text-start">
                                    <a class="text-sm font-medium text-mono hover:text-primary"
                                        href="{{ route('invoices.show', $invoice) }}">FAC {{ str_pad($invoice->id, 3,
                                        '0', STR_PAD_LEFT) }}</a>
                                </td>
                                <td class="text-sm text-foreground text-start">{{ $invoice->client->name ?? 'Cliente
                                    Anónimo' }}</td>
                                <td class="text-sm text-foreground text-end">${{ number_format($invoice->total_amount,
                                    0, ',', '.') }}</td>
                                <td class="text-end">
                                    @if($invoice->status === 'paid')
                                    <div class="kt-badge kt-badge-sm kt-badge-success kt-badge-outline">Pagada</div>
                                    @elseif($invoice->status === 'pending' || $invoice->status === 'partial')
                                    @if(\Carbon\Carbon::parse($invoice->due_date)->startOfDay()->isPast())
                                    <div class="kt-badge kt-badge-sm kt-badge-destructive kt-badge-outline">Vencida
                                    </div>
                                    @else
                                    <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
                                    @endif
                                    @endif
                                </td>
                                <td class="text-sm text-foreground text-end">{{
                                    \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                <td class="text-start">
                                    <button class="kt-btn kt-btn-icon kt-btn-ghost">
                                        <i class="ki-filled ki-dots-vertical text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="kt-card-footer justify-center">
                    <a class="kt-link kt-link-underlined kt-link-dashed" href="{{ route('invoices.index') }}">Ver todas
                        las facturas</a>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="kt-card h-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Clientes con mayor deuda</h3>
                </div>
                <div class="kt-card-content flex flex-col gap-5">
                    <div class="text-sm text-foreground">Listado de referencia para seguimiento y gestión de cobro.
                    </div>
                    <div class="flex flex-col gap-5">
                        @foreach($clientsWithDebt as $client)
                        <div class="flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex items-center justify-center relative text-2xl text-primary size-10 ring-1 ring-primary/20 bg-primary/5 rounded-full">
                                    {{ $client->initial }}</div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="leading-none font-medium text-sm text-mono">{{ $client->name }}</span>
                                    <span class="text-sm text-secondary-foreground">Saldo: ${{
                                        number_format($client->debt, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @if($client->status === 'Vencida')
                            <div class="kt-badge kt-badge-sm kt-badge-destructive kt-badge-outline">Vencida</div>
                            @else
                            <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="grid gap-5 lg:gap-7.5">
        <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
            <div class="lg:col-span-2">
                <div class="kt-card h-full">
                    <div class="kt-card-header">
                        <div>
                            <div class="text-sm text-muted-foreground">Hola, {{ auth()->user()->name ?? 'Usuario' }}
                            </div>
                            <div class="kt-card-title">Panel de administracion</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profile') }}" class="kt-btn kt-btn-outline kt-btn-sm">Perfil</a>
                        </div>
                    </div>
                    <div class="kt-card-content grid gap-4 md:grid-cols-2">
                        <div class="p-4 rounded-lg border border-border bg-muted/40">
                            <div class="text-sm text-muted-foreground">Estado</div>
                            <div class="text-2xl font-semibold mt-1">Acceso activo</div>
                            <p class="text-sm text-muted-foreground mt-2">Gestiona contratos, locales, facturas y pagos.
                            </p>
                        </div>
                        <div class="p-4 rounded-lg border border-border bg-muted/40">
                            <div class="text-sm text-muted-foreground">Siguientes pasos</div>
                            <ul class="mt-2 space-y-1 text-sm text-foreground">
                                <li>• Revisar contratos vigentes y fechas de vencimiento</li>
                                <li>• Actualizar estado de locales (disponible, rentado, mantenimiento)</li>
                                <li>• Generar o registrar facturas y pagos recientes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="kt-card h-full">
                    <div class="kt-card-header">
                        <div class="kt-card-title">Accesos rapidos</div>
                    </div>
                    <div class="kt-card-content space-y-3">
                        <a href="#" class="kt-link block">Inquilinos</a>
                        <a href="#" class="kt-link block">Contratos</a>
                        <a href="#" class="kt-link block">Locales</a>
                        <a href="#" class="kt-link block">Facturas y pagos</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        const data = @json($monthlyPaymentsChart);

        var options = {
            series: [{
                name: 'Pagos del mes',
                data: data
            }],
            chart: {
                type: 'area',
                height: 265,
                toolbar: { show: false }
            },
            colors: ['#3b82f6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#9ca3af',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "$" + (value / 1000) + "k";
                    },
                    style: {
                        colors: '#9ca3af',
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4,
                yaxis: {
                    lines: { show: true }
                }
            }
        };

        if (document.querySelector("#earnings_chart")) {
            var chart = new ApexCharts(document.querySelector("#earnings_chart"), options);
            chart.render();

            Livewire.on('update-chart', (event) => {
                chart.updateSeries([{
                    name: 'Pagos del mes',
                    data: event.data
                }]);
            });
        }

        var yearSelect = document.querySelector("#kt_select_year");
        if (yearSelect) {
            yearSelect.addEventListener('change', function (e) {
                @this.set('selectedYear', e.target.value);
            });
        }
    });
</script>
@endpush
