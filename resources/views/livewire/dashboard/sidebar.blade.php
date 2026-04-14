<!-- Sidebar -->
<div class="fixed z-20 hidden lg:flex flex-col shrink-0 w-(--sidebar-width) bg-muted [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start flex top-0 bottom-0" id="sidebar" data-kt-drawer-initialized="true">
	<!-- Sidebar Header -->
	<div id="sidebar_header">
		<div class="flex items-center gap-2.5 px-3.5 h-[70px]">
			<a href="#">
				<img class="dark:hidden h-[42px]" src="{{ asset('assets/app/logo.png') }}">
				<img class="hidden dark:inline-block h-[42px]" src="{{ asset('assets/app/logo.png') }}">
			</a>
			<div class="kt-menu kt-menu-default grow" data-kt-menu="true">
				<div class="kt-menu-item grow kt-menu-item-dropdown" data-kt-menu-item-offset="0px,0px" data-kt-menu-item-placement="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="hover">
					<div class="kt-menu-label cursor-pointer text-mono font-medium grow justify-between">
						<span class="text-base font-medium text-mono grow justify-start">
							{{ config('app.name', 'Laravel') }}
						</span>
						<span class="kt-menu-arrow">
							<i class="ki-filled ki-down">
							</i>
						</span>
					</div>
					<div class="kt-menu-dropdown w-48 py-2">
						<div class="kt-menu-item">
							<a class="kt-menu-link" href="{{ route('profile') }}" tabindex="0">
								<span class="kt-menu-icon">
									<i class="ki-filled ki-profile-circle">
									</i>
								</span>
								<span class="kt-menu-title">
									Mi perfil
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="pt-2.5 px-3.5 mb-1 z-50">
			<livewire:global-search />
		</div>
	</div>
	<!-- End of Sidebar Header -->
	<!-- Sidebar menu -->
	<livewire:dashboard.partials.sidebar-menu />
	<!-- End of Sidebar kt-menu-->
	<!-- Footer -->
	<div class="flex flex-center justify-between shrink-0 ps-4 pe-3.5 mb-3.5" id="sidebar_footer">
		<livewire:shared.topbar-user-dropdown />
		<livewire:shared.topbar-notification-dropdown />
	</div>
	<!-- End of Footer -->
</div>
<!-- End of Sidebar -->
