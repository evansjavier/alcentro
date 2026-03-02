@extends('layouts.app')

@section('content')

<div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-1">
         <div class="kt-card h-full">
          <div class="kt-card-header">
           <h3 class="kt-card-title">
            Highlights
           </h3>
           <div class="kt-menu" data-kt-menu="true">
            <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
             <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
              <i class="ki-filled ki-dots-vertical text-lg">
              </i>
             </button>
             <div class="kt-menu-dropdown kt-menu-default w-full max-w-[200px]" data-kt-menu-dismiss="true">
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
              <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="-15px, 0" data-kt-menu-item-placement="right-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click|lg:hover">
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
                 <a class="kt-menu-link" href="/metronic/tailwind/demo1/account/home/settings-sidebar">
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
                 <a class="kt-menu-link" href="/metronic/tailwind/demo1/account/home/settings-sidebar">
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
                 <a class="kt-menu-link" href="/metronic/tailwind/demo1/account/home/settings-sidebar">
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
               <a class="kt-menu-link" href="/metronic/tailwind/demo1/account/home/settings-enterprise">
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
             @php
               $paidThisMonth = $paidThisMonth ?? 125000;
               $expectedThisMonth = $expectedThisMonth ?? 200000;
               $progress = $expectedThisMonth > 0 ? min(100, round(($paidThisMonth / $expectedThisMonth) * 100, 1)) : 0;
             @endphp
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
             <div class="flex flex-col gap-1.5">
            <div class="w-full bg-muted rounded-full h-3 overflow-hidden">
             <div class="bg-primary h-full" style="width: {{ $progress }}%"></div>
            </div>
            <div class="flex items-center justify-between text-sm text-secondary-foreground">
             <span>Pagado del mes</span>
             <span>${{ number_format($paidThisMonth, 0, '.', ',') }} / ${{ number_format($expectedThisMonth, 0, '.', ',') }}</span>
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
               $172k
              </span>
              <span class="lg:text-right">
               <i class="ki-filled ki-arrow-up text-green-500">
               </i>
               3.9%
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
               $85k
              </span>
              <span class="lg:text-right">
               <i class="ki-filled ki-arrow-down text-destructive">
               </i>
               0.7%
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
               $36k
              </span>
              <span class="lg:text-right">
               <i class="ki-filled ki-arrow-up text-green-500">
               </i>
               8.2%
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
           <div class="flex gap-5">


            <select class="hidden" data-kt-select="true" data-kt-select-placeholder="Periodo" name="kt-select" data-kt-select-initialized="true">
             <option data-kt-select-option-initialized="true">
              Ninguno
             </option>
             <option value="1" data-kt-select-option-initialized="true">
              1 mes
             </option>
             <option value="2" data-kt-select-option-initialized="true">
              3 meses
             </option>
             <option value="3" data-kt-select-option-initialized="true">
              6 meses
             </option>
             <option value="4" data-kt-select-option-initialized="true">
              12 meses
             </option>
            </select><div data-kt-select-wrapper="" class="kt-select-wrapper hidden"><div data-kt-select-display="" class="kt-select-display " tabindex="0" role="button" data-selected="0" aria-haspopup="listbox" aria-expanded="false" aria-label="Select an option"><div data-kt-select-placeholder="" class="kt-select-placeholder ">Período</div></div><div data-kt-select-dropdown="" class="kt-select-dropdown hidden " style="z-index: 105;"><ul role="listbox" aria-label="Select an option" class="kt-select-options " data-kt-select-options="true"><li data-kt-select-option="" data-value="None" data-text="None" class="kt-select-option " role="option" aria-selected="true">
			<div class="kt-select-option-text" data-kt-text-container="true">
              None
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="1" data-text="1 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              1 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="2" data-text="3 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              3 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="3" data-text="6 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              6 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="4" data-text="12 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              12 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li></ul></div></div><div data-kt-select-wrapper="" class="kt-select-wrapper w-36"><div data-kt-select-display="" class="kt-select-display kt-select" tabindex="0" role="button" data-selected="0" aria-haspopup="listbox" aria-expanded="false" aria-label="Select an option"><div data-kt-select-placeholder="" class="kt-select-placeholder ">Período</div></div><div data-kt-select-dropdown="" class="kt-select-dropdown hidden " style="z-index: 105;"><ul role="listbox" aria-label="Select an option" class="kt-select-options " data-kt-select-options="true"><li data-kt-select-option="" data-value="None" data-text="None" class="kt-select-option " role="option" aria-selected="true">
			<div class="kt-select-option-text" data-kt-text-container="true">
              None
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="1" data-text="1 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              1 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="2" data-text="3 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              3 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="3" data-text="6 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              6 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li><li data-kt-select-option="" data-value="4" data-text="12 month" class="kt-select-option " role="option" aria-selected="false">
			<div class="kt-select-option-text" data-kt-text-container="true">
              12 month
             </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 ms-auto hidden text-primary kt-select-option-selected:block"><path d="M20 6 9 17l-5-5"></path></svg>
		</li></ul></div></div>
           </div>
          </div>
          <div class="kt-card-content flex flex-col justify-end items-stretch grow px-3 py-1">
           <div id="earnings_chart" style="min-height: 265px;"><div id="apexcharts9y9842ryk" class="apexcharts-canvas apexcharts9y9842ryk apexcharts-theme-light" style="width: 777px; height: 250px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="777" height="250"><foreignObject x="0" y="0" width="777" height="250"><style type="text/css">
      .apexcharts-flip-y {
        transform: scaleY(-1) translateY(-100%);
        transform-origin: top;
        transform-box: fill-box;
      }
      .apexcharts-flip-x {
        transform: scaleX(-1);
        transform-origin: center;
        transform-box: fill-box;
      }
      .apexcharts-legend {
        display: flex;
        overflow: auto;
        padding: 0 10px;
      }
      .apexcharts-legend.apexcharts-legend-group-horizontal {
        flex-direction: column;
      }
      .apexcharts-legend-group {
        display: flex;
      }
      .apexcharts-legend-group-vertical {
        flex-direction: column-reverse;
      }
      .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
        flex-wrap: wrap
      }
      .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
        flex-direction: column;
        bottom: 0;
      }
      .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
        justify-content: flex-start;
        align-items: flex-start;
      }
      .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
        justify-content: center;
        align-items: center;
      }
      .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
        justify-content: flex-end;
        align-items: flex-end;
      }
      .apexcharts-legend-series {
        cursor: pointer;
        line-height: normal;
        display: flex;
        align-items: center;
      }
      .apexcharts-legend-text {
        position: relative;
        font-size: 14px;
      }
      .apexcharts-legend-text *, .apexcharts-legend-marker * {
        pointer-events: none;
      }
      .apexcharts-legend-marker {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-right: 1px;
      }

      .apexcharts-legend-series.apexcharts-no-click {
        cursor: auto;
      }
      .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
        display: none !important;
      }
      .apexcharts-inactive-legend {
        opacity: 0.45;
      }

    </style></foreignObject><g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g><g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g><rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g class="apexcharts-yaxis" rel="0" transform="translate(30.875, 0)"><g class="apexcharts-yaxis-texts-g"><text x="20" y="34" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$100K</tspan><title>$100K</title></text><text x="20" y="69.8224" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$80K</tspan><title>$80K</title></text><text x="20" y="105.6448" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$60K</tspan><title>$60K</title></text><text x="20" y="141.4672" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$40K</tspan><title>$40K</title></text><text x="20" y="177.2896" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$20K</tspan><title>$20K</title></text><text x="20" y="213.11200000000002" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>$0K</tspan><title>$0K</title></text></g></g><g class="apexcharts-inner apexcharts-graphical" transform="translate(60.875, 30)"><defs><clipPath id="gridRectMask9y9842ryk"><rect width="701.7802734375" height="186.112" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMask9y9842ryk"><rect width="701.7802734375" height="186.112" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMask9y9842ryk"><rect width="694.7802734375" height="179.112" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask9y9842ryk"></clipPath><clipPath id="nonForecastMask9y9842ryk"></clipPath><linearGradient x1="0" y1="0" x2="0" y2="1" id="SvgjsLinearGradient1001"><stop stop-opacity="0.25" stop-color="rgba(0,143,251,0.25)" offset="0"></stop><stop stop-opacity="0" stop-color="rgba(128,199,253,0)" offset="1"></stop><stop stop-opacity="0" stop-color="rgba(128,199,253,0)" offset="1"></stop></linearGradient></defs><g class="apexcharts-grid"><g class="apexcharts-gridlines-horizontal"><line x1="0" y1="35.8224" x2="694.7802734375" y2="35.8224" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="71.6448" x2="694.7802734375" y2="71.6448" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="107.4672" x2="694.7802734375" y2="107.4672" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="143.2896" x2="694.7802734375" y2="143.2896" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line><line x1="0" y1="179.11200000000002" x2="694.7802734375" y2="179.11200000000002" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-gridlines-vertical"></g><line x1="0" y1="179.112" x2="694.7802734375" y2="179.112" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line x1="0" y1="1" x2="0" y2="179.112" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g class="apexcharts-grid-borders"><line x1="0" y1="0" x2="694.7802734375" y2="0" stroke="var(--color-border)" stroke-dasharray="5" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g class="apexcharts-area-series apexcharts-plot-series"><g class="apexcharts-series" zindex="0" seriesname="series1" data:longestseries="true" rel="1" data:realindex="0"><path d="M 0 44.77799999999999C 22.106645063920457 44.77799999999999 41.05519797585228 134.334 63.161843039772734 134.334C 85.26848810369319 134.334 104.21704101562501 98.51159999999999 126.32368607954547 98.51159999999999C 148.4303311434659 98.51159999999999 167.37888405539775 152.24519999999998 189.4855291193182 152.24519999999998C 211.59217418323865 152.24519999999998 230.54072709517047 26.866799999999984 252.64737215909093 26.866799999999984C 274.7540172230114 26.866799999999984 293.7025701349432 116.4228 315.8092151988636 116.4228C 337.91586026278407 116.4228 356.86441317471593 53.733599999999996 378.9710582386364 53.733599999999996C 401.0777033025568 53.733599999999996 420.0262562144887 134.334 442.1329012784091 134.334C 464.23954634232956 134.334 483.18809925426143 116.4228 505.29474431818187 116.4228C 527.4013893821024 116.4228 546.3499422940341 152.24519999999998 568.4565873579546 152.24519999999998C 590.563232421875 152.24519999999998 609.5117853338069 98.51159999999999 631.6184303977273 98.51159999999999C 653.7250754616477 98.51159999999999 672.6736283735795 125.3784 694.7802734375 125.3784C 694.7802734375 125.3784 694.7802734375 125.3784 694.7802734375 179.112 L 0 179.112z" fill="url(#SvgjsLinearGradient1001)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask9y9842ryk)" pathto="M 0 44.77799999999999C 22.106645063920457 44.77799999999999 41.05519797585228 134.334 63.161843039772734 134.334C 85.26848810369319 134.334 104.21704101562501 98.51159999999999 126.32368607954547 98.51159999999999C 148.4303311434659 98.51159999999999 167.37888405539775 152.24519999999998 189.4855291193182 152.24519999999998C 211.59217418323865 152.24519999999998 230.54072709517047 26.866799999999984 252.64737215909093 26.866799999999984C 274.7540172230114 26.866799999999984 293.7025701349432 116.4228 315.8092151988636 116.4228C 337.91586026278407 116.4228 356.86441317471593 53.733599999999996 378.9710582386364 53.733599999999996C 401.0777033025568 53.733599999999996 420.0262562144887 134.334 442.1329012784091 134.334C 464.23954634232956 134.334 483.18809925426143 116.4228 505.29474431818187 116.4228C 527.4013893821024 116.4228 546.3499422940341 152.24519999999998 568.4565873579546 152.24519999999998C 590.563232421875 152.24519999999998 609.5117853338069 98.51159999999999 631.6184303977273 98.51159999999999C 653.7250754616477 98.51159999999999 672.6736283735795 125.3784 694.7802734375 125.3784C 694.7802734375 125.3784 694.7802734375 125.3784 694.7802734375 179.112 L 0 179.112z" pathfrom="M 0 179.112 L 0 179.112 L 63.161843039772734 179.112 L 126.32368607954547 179.112 L 189.4855291193182 179.112 L 252.64737215909093 179.112 L 315.8092151988636 179.112 L 378.9710582386364 179.112 L 442.1329012784091 179.112 L 505.29474431818187 179.112 L 568.4565873579546 179.112 L 631.6184303977273 179.112 L 694.7802734375 179.112z"></path><path d="M 0 44.77799999999999C 22.106645063920457 44.77799999999999 41.05519797585228 134.334 63.161843039772734 134.334C 85.26848810369319 134.334 104.21704101562501 98.51159999999999 126.32368607954547 98.51159999999999C 148.4303311434659 98.51159999999999 167.37888405539775 152.24519999999998 189.4855291193182 152.24519999999998C 211.59217418323865 152.24519999999998 230.54072709517047 26.866799999999984 252.64737215909093 26.866799999999984C 274.7540172230114 26.866799999999984 293.7025701349432 116.4228 315.8092151988636 116.4228C 337.91586026278407 116.4228 356.86441317471593 53.733599999999996 378.9710582386364 53.733599999999996C 401.0777033025568 53.733599999999996 420.0262562144887 134.334 442.1329012784091 134.334C 464.23954634232956 134.334 483.18809925426143 116.4228 505.29474431818187 116.4228C 527.4013893821024 116.4228 546.3499422940341 152.24519999999998 568.4565873579546 152.24519999999998C 590.563232421875 152.24519999999998 609.5117853338069 98.51159999999999 631.6184303977273 98.51159999999999C 653.7250754616477 98.51159999999999 672.6736283735795 125.3784 694.7802734375 125.3784" fill="none" fill-opacity="1" stroke="var(--color-primary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask9y9842ryk)" pathto="M 0 44.77799999999999C 22.106645063920457 44.77799999999999 41.05519797585228 134.334 63.161843039772734 134.334C 85.26848810369319 134.334 104.21704101562501 98.51159999999999 126.32368607954547 98.51159999999999C 148.4303311434659 98.51159999999999 167.37888405539775 152.24519999999998 189.4855291193182 152.24519999999998C 211.59217418323865 152.24519999999998 230.54072709517047 26.866799999999984 252.64737215909093 26.866799999999984C 274.7540172230114 26.866799999999984 293.7025701349432 116.4228 315.8092151988636 116.4228C 337.91586026278407 116.4228 356.86441317471593 53.733599999999996 378.9710582386364 53.733599999999996C 401.0777033025568 53.733599999999996 420.0262562144887 134.334 442.1329012784091 134.334C 464.23954634232956 134.334 483.18809925426143 116.4228 505.29474431818187 116.4228C 527.4013893821024 116.4228 546.3499422940341 152.24519999999998 568.4565873579546 152.24519999999998C 590.563232421875 152.24519999999998 609.5117853338069 98.51159999999999 631.6184303977273 98.51159999999999C 653.7250754616477 98.51159999999999 672.6736283735795 125.3784 694.7802734375 125.3784" pathfrom="M 0 179.112 L 0 179.112 L 63.161843039772734 179.112 L 126.32368607954547 179.112 L 189.4855291193182 179.112 L 252.64737215909093 179.112 L 315.8092151988636 179.112 L 378.9710582386364 179.112 L 442.1329012784091 179.112 L 505.29474431818187 179.112 L 568.4565873579546 179.112 L 631.6184303977273 179.112 L 694.7802734375 179.112" fill-rule="evenodd"></path><g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realindex="0"><g class="apexcharts-series-markers"><path d="M 0, 0
           m -0, 0
           a 0,0 0 1,0 0,0
           a 0,0 0 1,0 -0,0" fill="var(--color-primary)" fill-opacity="1" stroke="var(--color-primary)" stroke-opacity="1" stroke-linecap="butt" stroke-width="4" stroke-dasharray="0" cx="0" cy="0" shape="circle" class="apexcharts-marker wntiu8ck4 no-pointer-events" default-marker-size="0"></path></g></g></g><g class="apexcharts-datalabels" data:realindex="0"></g></g><line x1="0" y1="0" x2="0" y2="179.112" stroke="var(--color-primary)" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="179.112" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><line x1="0" y1="0" x2="694.7802734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="694.7802734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g class="apexcharts-xaxis" transform="translate(0, 0)"><g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text x="0" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jan</tspan><title>Jan</title></text><text x="63.161843039772734" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Feb</tspan><title>Feb</title></text><text x="126.32368607954545" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Mar</tspan><title>Mar</title></text><text x="189.48552911931816" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Apr</tspan><title>Apr</title></text><text x="252.64737215909088" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>May</tspan><title>May</title></text><text x="315.8092151988636" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jun</tspan><title>Jun</title></text><text x="378.9710582386364" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Jul</tspan><title>Jul</title></text><text x="442.1329012784091" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Aug</tspan><title>Aug</title></text><text x="505.29474431818187" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Sep</tspan><title>Sep</title></text><text x="568.4565873579546" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Oct</tspan><title>Oct</title></text><text x="631.6184303977274" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Nov</tspan><title>Nov</title></text><text x="694.7802734375001" y="207.112" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="var(--color-muted-foreground)" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan>Dec</tspan><title>Dec</title></text></g></g><g class="apexcharts-yaxis-annotations"></g><g class="apexcharts-xaxis-annotations"></g><g class="apexcharts-point-annotations"></g></g><rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></svg><div class="apexcharts-legend" style="max-height: 125px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0" style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(0, 143, 251);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
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
             <tr>
              <td class="text-start">
               <a class="text-sm font-medium text-mono hover:text-primary" href="#">FAC-2026-014</a>
              </td>
              <td class="text-sm text-foreground text-start">Comercial La Estación</td>
              <td class="text-sm text-foreground text-end">$8,450,000</td>
              <td class="text-end">
               <div class="kt-badge kt-badge-sm kt-badge-success kt-badge-outline">Pagada</div>
              </td>
              <td class="text-sm text-foreground text-end">16 Feb 2026</td>
              <td class="text-start">
               <button class="kt-btn kt-btn-icon kt-btn-ghost">
                <i class="ki-filled ki-dots-vertical text-lg"></i>
               </button>
              </td>
             </tr>
             <tr>
              <td class="text-start">
               <a class="text-sm font-medium text-mono hover:text-primary" href="#">FAC-2026-013</a>
              </td>
              <td class="text-sm text-foreground text-start">Inversiones Plaza Norte</td>
              <td class="text-sm text-foreground text-end">$6,120,000</td>
              <td class="text-end">
               <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
              </td>
              <td class="text-sm text-foreground text-end">20 Feb 2026</td>
              <td class="text-start">
               <button class="kt-btn kt-btn-icon kt-btn-ghost">
                <i class="ki-filled ki-dots-vertical text-lg"></i>
               </button>
              </td>
             </tr>
             <tr>
              <td class="text-start">
               <a class="text-sm font-medium text-mono hover:text-primary" href="#">FAC-2026-012</a>
              </td>
              <td class="text-sm text-foreground text-start">Grupo Central</td>
              <td class="text-sm text-foreground text-end">$4,890,000</td>
              <td class="text-end">
               <div class="kt-badge kt-badge-sm kt-badge-destructive kt-badge-outline">Vencida</div>
              </td>
              <td class="text-sm text-foreground text-end">05 Feb 2026</td>
              <td class="text-start">
               <button class="kt-btn kt-btn-icon kt-btn-ghost">
                <i class="ki-filled ki-dots-vertical text-lg"></i>
               </button>
              </td>
             </tr>
             <tr>
              <td class="text-start">
               <a class="text-sm font-medium text-mono hover:text-primary" href="#">FAC-2026-011</a>
              </td>
              <td class="text-sm text-foreground text-start">Locales Andinos</td>
              <td class="text-sm text-foreground text-end">$3,750,000</td>
              <td class="text-end">
               <div class="kt-badge kt-badge-sm kt-badge-success kt-badge-outline">Pagada</div>
              </td>
              <td class="text-sm text-foreground text-end">28 Ene 2026</td>
              <td class="text-start">
               <button class="kt-btn kt-btn-icon kt-btn-ghost">
                <i class="ki-filled ki-dots-vertical text-lg"></i>
               </button>
              </td>
             </tr>
             <tr>
              <td class="text-start">
               <a class="text-sm font-medium text-mono hover:text-primary" href="#">FAC-2026-010</a>
              </td>
              <td class="text-sm text-foreground text-start">Torres del Parque</td>
              <td class="text-sm text-foreground text-end">$2,980,000</td>
              <td class="text-end">
               <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
              </td>
              <td class="text-sm text-foreground text-end">22 Ene 2026</td>
              <td class="text-start">
               <button class="kt-btn kt-btn-icon kt-btn-ghost">
                <i class="ki-filled ki-dots-vertical text-lg"></i>
               </button>
              </td>
             </tr>
            </tbody>
           </table>
          </div>
          <div class="kt-card-footer justify-center">
           <a class="kt-link kt-link-underlined kt-link-dashed" href="#">Ver todas las facturas</a>
          </div>
         </div>
        </div>
        <div class="lg:col-span-1">
         <div class="kt-card h-full">
          <div class="kt-card-header">
           <h3 class="kt-card-title">Clientes con mayor deuda</h3>
          </div>
          <div class="kt-card-content flex flex-col gap-5">
           <div class="text-sm text-foreground">Listado de referencia para seguimiento y gestión de cobro.</div>
           <div class="flex flex-col gap-5">
            <div class="flex items-center justify-between gap-2.5">
             <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center relative text-2xl text-primary size-10 ring-1 ring-primary/20 bg-primary/5 rounded-full">C</div>
              <div class="flex flex-col gap-0.5">
               <span class="leading-none font-medium text-sm text-mono">Comercial La Estación</span>
               <span class="text-sm text-secondary-foreground">Saldo: $12,300,000</span>
              </div>
             </div>
             <div class="kt-badge kt-badge-sm kt-badge-destructive kt-badge-outline">Vencida</div>
            </div>
            <div class="flex items-center justify-between gap-2.5">
             <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center relative text-2xl text-warning size-10 ring-1 ring-warning/30 bg-warning/10 rounded-full">P</div>
              <div class="flex flex-col gap-0.5">
               <span class="leading-none font-medium text-sm text-mono">Plaza Norte</span>
               <span class="text-sm text-secondary-foreground">Saldo: $9,150,000</span>
              </div>
             </div>
             <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
            </div>
            <div class="flex items-center justify-between gap-2.5">
             <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center relative text-2xl text-destructive size-10 ring-1 ring-destructive/30 bg-destructive/10 rounded-full">T</div>
              <div class="flex flex-col gap-0.5">
               <span class="leading-none font-medium text-sm text-mono">Torres del Parque</span>
               <span class="text-sm text-secondary-foreground">Saldo: $7,480,000</span>
              </div>
             </div>
             <div class="kt-badge kt-badge-sm kt-badge-destructive kt-badge-outline">Vencida</div>
            </div>
            <div class="flex items-center justify-between gap-2.5">
             <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center relative text-2xl text-muted-foreground size-10 ring-1 ring-border bg-muted/40 rounded-full">L</div>
              <div class="flex flex-col gap-0.5">
               <span class="leading-none font-medium text-sm text-mono">Locales Andinos</span>
               <span class="text-sm text-secondary-foreground">Saldo: $5,920,000</span>
              </div>
             </div>
             <div class="kt-badge kt-badge-sm kt-badge-warning kt-badge-outline">Pendiente</div>
            </div>
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
                            <div class="text-sm text-muted-foreground">Hola, {{ auth()->user()->name ?? 'Usuario' }}</div>
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
                            <p class="text-sm text-muted-foreground mt-2">Gestiona contratos, locales, facturas y pagos.</p>
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
@endsection
