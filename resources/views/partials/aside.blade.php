<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="/">
            <img alt="Logo" src="assets/media/logos/whiteprimakara.png" class="h-40px logo" />
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="black" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                    </div>
                </div>
                <div class="menu-item {{ ($active === "dashboard") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('dashboard') }}">
                        <span class="menu-icon">
							<i class="bi bi-house fs-3"></i>
						</span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
            
                <div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Menu Cash Flow</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-cash-coin fs-3"></i>
						</span>
						<span class="menu-title">Menu Cash Flow</span>
						<span class="menu-arrow"></span>
					</span>
				    <div class="menu-sub menu-sub-accordion">
					    <div class="menu-item {{ ($active === "Cash Flow") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('cashflow') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-piggy-bank fs-3"></i>
								</span>
							    <span class="menu-title">Cash Flow</span>
							</a>
						</div>
						<div class="menu-item {{ ($active === "Piutang") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('piutang') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-cash-stack fs-3"></i>
								</span>
								<span class="menu-title">Piutang</span>
							</a>
						</div>
                        <div class="menu-item {{ ($active === "Uang Fisik") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('uangFisik') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-wallet2 fs-3"></i>
								</span>
								<span class="menu-title">Uang Fisik</span>
							</a>
						</div>
					</div>
				</div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-journal-bookmark fs-3"></i>
						</span>
						<span class="menu-title">Laporan Cash Flow</span>
						<span class="menu-arrow"></span>
					</span>
				    <div class="menu-sub menu-sub-accordion">
					    <div class="menu-item {{ ($active === "Laporan Compare") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('compare') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-aspect-ratio fs-3"></i>
								</span>
							    <span class="menu-title">Laporan Compare</span>
							</a>
						</div>
						<div class="menu-item {{ ($active === "Laporan Cash Flow") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('lapcashflow') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-graph-down fs-3"></i>
								</span>
								<span class="menu-title">Laporan Cash Flow</span>
							</a>
						</div>
					</div>
				</div>

                <div class="menu-item">
					<div class="menu-content pt-8 pb-2">
						<span class="menu-section text-muted text-uppercase fs-8 ls-1">Menu Jurnal</span>
					</div>
				</div>
				<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-clipboard-data fs-3"></i>
						</span>
						<span class="menu-title">Menu Jurnal</span>
						<span class="menu-arrow"></span>
					</span>
				    <div class="menu-sub menu-sub-accordion">
						<div class="menu-item {{ ($active === "Jurnal") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('jurnal') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-journal-text fs-3"></i>
								</span>
								<span class="menu-title">Jurnal</span>
							</a>
						</div>
						<div class="menu-item {{ ($active === "Kas Masuk") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('kasMasuk') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-journal-plus fs-3"></i>
								</span>
								<span class="menu-title">Kas Masuk</span>
							</a>
						</div>
                        <div class="menu-item {{ ($active === "Kas Keluar") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('kasKeluar') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-journal-minus fs-3"></i>
								</span>
								<span class="menu-title">Kas Keluar</span>
							</a>
						</div>
						<div class="menu-item {{ ($active === "Lock Jurnal") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('lockJurnal') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-lock fs-3"></i>
								</span>
								<span class="menu-title">Lock Jurnal</span>
							</a>
						</div>
					</div>
				</div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
					<span class="menu-link">
						<span class="menu-icon">
							<i class="bi bi-journal-bookmark-fill fs-3"></i>
						</span>
						<span class="menu-title">Laporan Jurnal</span>
						<span class="menu-arrow"></span>
					</span>
				    <div class="menu-sub menu-sub-accordion">
					    <div class="menu-item {{ ($active === "Buku Besar") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('laporanBukuBesar') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-book fs-3"></i>
								</span>
							    <span class="menu-title">Buku Besar</span>
							</a>
						</div>
						<div class="menu-item {{ ($active === "Aktivitas") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('aktivitas') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-arrow-down-up fs-3"></i>
								</span>
								<span class="menu-title">Aktivitas</span>
							</a>
						</div>
                        <div class="menu-item {{ ($active === "Laporan Neraca") ? 'here show' : '' }}">
							<a class="menu-link" href="{{ url('neraca') }}">
								<span class="menu-bullet">
                                    <i class="bi bi-table fs-3"></i>
								</span>
								<span class="menu-title">Laporan Neraca</span>
							</a>
						</div>
					</div>
				</div>
                
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Master</span>
                    </div>
                </div>
                <div class="menu-item {{ ($active === "user") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('user') }}">
                        <span class="menu-icon">
                            <i class="bi bi-people-fill fs-3"></i>
                        </span>
                        <span class="menu-title">User</span>
                    </a>
                </div>
                
                <div class="menu-item {{ ($active === "Uang Kas") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('kas') }}">
                        <span class="menu-icon">
                            <i class="bi bi-bank fs-3"></i>
                        </span>
                        <span class="menu-title">Uang Kas</span>
                    </a>
                </div>
                
                <div class="menu-item {{ ($active === "Pecahan Uang") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('pecahan') }}">
                        <span class="menu-icon">
                            <i class="bi bi-currency-dollar fs-3"></i>
                        </span>
                        <span class="menu-title">Pecahan Uang</span>
                    </a>
                </div>

                <div class="menu-item {{ ($active === "Jurnal Akun") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('jurnalakun') }}">
                        <span class="menu-icon">
                            <i class="bi bi-card-checklist fs-3"></i>
                        </span>
                        <span class="menu-title">Junal Akun</span>
                    </a>
                </div>

                <div class="menu-item {{ ($active === "Divisi") ? 'here show' : '' }}">
                    <a class="menu-link" href="{{ url('divisi') }}">
                        <span class="menu-icon">
                            <i class="bi bi-building fs-3"></i>
                        </span>
                        <span class="menu-title">Divisi</span>
                    </a>
                </div>

                {{-- <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Master Data</span>
                    </div>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/art/art009.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21 18.3V4H20H5C4.4 4 4 4.4 4 5V20C10.9 20 16.7 15.6 19 9.5V18.3C18.4 18.6 18 19.3 18 20C18 21.1 18.9 22 20 22C21.1 22 22 21.1 22 20C22 19.3 21.6 18.6 21 18.3Z" fill="black" />
                                    <path d="M22 4C22 2.9 21.1 2 20 2C18.9 2 18 2.9 18 4C18 4.7 18.4 5.29995 18.9 5.69995C18.1 12.6 12.6 18.2 5.70001 18.9C5.30001 18.4 4.7 18 4 18C2.9 18 2 18.9 2 20C2 21.1 2.9 22 4 22C4.8 22 5.39999 21.6 5.79999 20.9C13.8 20.1 20.1 13.7 20.9 5.80005C21.6 5.40005 22 4.8 22 4Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Master</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link" href="../../demo1/dist/modals/general/invite-friends.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Invite Friends</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="../../demo1/dist/modals/general/view-users.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">View Users</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="../../demo1/dist/modals/general/select-users.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Select Users</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="../../demo1/dist/modals/general/upgrade-plan.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Upgrade Plan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link" href="../../demo1/dist/modals/general/share-earn.html">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Share &amp; Earn</span>
                            </a>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Crafted</span>
                    </div>
                </div>
                <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/art/art009.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21 18.3V4H20H5C4.4 4 4 4.4 4 5V20C10.9 20 16.7 15.6 19 9.5V18.3C18.4 18.6 18 19.3 18 20C18 21.1 18.9 22 20 22C21.1 22 22 21.1 22 20C22 19.3 21.6 18.6 21 18.3Z" fill="black" />
                                    <path d="M22 4C22 2.9 21.1 2 20 2C18.9 2 18 2.9 18 4C18 4.7 18.4 5.29995 18.9 5.69995C18.1 12.6 12.6 18.2 5.70001 18.9C5.30001 18.4 4.7 18 4 18C2.9 18 2 18.9 2 20C2 21.1 2.9 22 4 22C4.8 22 5.39999 21.6 5.79999 20.9C13.8 20.1 20.1 13.7 20.9 5.80005C21.6 5.40005 22 4.8 22 4Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Modals</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">General</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/general/invite-friends.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Invite Friends</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/general/view-users.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">View Users</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/general/select-users.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Select Users</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/general/upgrade-plan.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Upgrade Plan</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/general/share-earn.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Share &amp; Earn</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Forms</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/forms/new-target.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">New Target</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link active" href="../../demo1/dist/modals/forms/new-card.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">New Card</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/forms/new-address.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">New Address</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/forms/create-api-key.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create API Key</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Wizards</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/wizards/two-factor-authentication.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Two Factor Auth</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/wizards/create-app.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create App</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/wizards/create-account.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create Account</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/wizards/create-project.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create Project</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/wizards/offer-a-deal.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Offer a Deal</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Search</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/search/users.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Users</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo1/dist/modals/search/select-location.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Select Location</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <span class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip">
            <span class="btn-label">©2023 Primakara</span>
            <!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
            <span class="svg-icon btn-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM15 17C15 16.4 14.6 16 14 16H8C7.4 16 7 16.4 7 17C7 17.6 7.4 18 8 18H14C14.6 18 15 17.6 15 17ZM17 12C17 11.4 16.6 11 16 11H8C7.4 11 7 11.4 7 12C7 12.6 7.4 13 8 13H16C16.6 13 17 12.6 17 12ZM17 7C17 6.4 16.6 6 16 6H8C7.4 6 7 6.4 7 7C7 7.6 7.4 8 8 8H16C16.6 8 17 7.6 17 7Z" fill="black" />
                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </span>
    </div>
    <!--end::Footer-->
</div>
