<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Fonts: Inter for that modern, neutral look -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            [x-cloak] {
                display: none !important
            }
            body { 
                font-family: 'Inter', sans-serif; 
                letter-spacing: -0.01em;
            }
            
            /* Material 3 (Material You) Variables */
            :root {
                --m3-primary: #6750A4;
                --m3-on-primary: #ffffff;
                --m3-primary-container: #EADDFF;
                --m3-on-primary-container: #21005D;
                --m3-surface: #FEF7FF;
                --m3-surface-container: #F3EDF7;
                --m3-surface-container-high: #ECE6F0;
                --m3-outline: #79747E;
                --m3-outline-variant: #CAC4D0;
            }

            /* DataTables M3 Overrides */
            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid var(--m3-outline);
                border-radius: 8px;
                background-color: transparent;
                padding: 10px 16px;
                font-size: 0.875rem;
            }
            .dataTables_wrapper .dataTables_filter input:focus {
                outline: none;
                border: 2px solid var(--m3-primary);
                padding: 9px 15px; /* Adjust for border thickness */
                box-shadow: none;
            }
            table.dataTable {
                border-collapse: separate !important;
                border-spacing: 0;
                width: 100% !important;
                border-bottom: none !important;
            }
            table.dataTable thead th {
                background-color: var(--m3-surface-container);
                color: #49454F;
                font-weight: 600;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid var(--m3-outline-variant) !important;
            }
            table.dataTable thead th:first-child { border-top-left-radius: 12px; }
            table.dataTable thead th:last-child { border-top-right-radius: 12px; }
            
            table.dataTable tbody td {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid var(--m3-outline-variant);
                color: #1C1B1F;
                font-size: 0.9rem;
            }

            /* Consistent Row Styling */
            table.dataTable tbody tr,
            table tbody tr.border-b {
                cursor: pointer;
                transition: background-color 0.2s ease;
            }

            table.dataTable tbody tr:hover,
            table tbody tr.border-b:hover {
                background-color: var(--m3-surface-container-high) !important;
            }

            /* Comprehensive reset to disable all alternating and column-sorting background colors */
            table.dataTable,
            table.dataTable tbody td,
            table.dataTable.display tbody tr.odd,
            table.dataTable.display tbody tr.even,
            table.dataTable tbody td.sorting_1,
            table.dataTable tbody td.sorting_2,
            table.dataTable tbody td.sorting_3,
            table.dataTable.display tbody tr.odd > .sorting_1,
            table.dataTable.display tbody tr.even > .sorting_1 {
                background-color: transparent !important;
            }
            table.dataTable.no-footer { border-bottom: none !important; }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 20px !important;
                padding: 6px 16px !important;
                margin: 0 4px !important;
                border: 1px solid var(--m3-outline-variant) !important;
                background: white !important;
                color: var(--m3-primary) !important;
                font-weight: 500;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: var(--m3-primary-container) !important;
                color: var(--m3-on-primary-container) !important;
                border-color: var(--m3-primary-container) !important;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { 
                background: var(--m3-outline-variant); 
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover { background: var(--m3-outline); }
        </style>
    </head>
    <body class="antialiased bg-[#FEF7FF] text-main">
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
            
            <!-- Sidebar (M3 Navigation Rail / Drawer Hybrid) -->
            <aside class="flex flex-col w-72 h-full bg-[#F3EDF7] z-30 transition-all duration-300 transform lg:translate-x-0 fixed lg:static px-4"
                   :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
                
                <div class="flex items-center h-20 px-4">
                    <div class="bg-[#6750A4] w-10 h-10 rounded-xl flex items-center justify-center mr-3 shadow-sm">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    <span class="text-xl font-semibold tracking-tight text-main">Sistem Penjualan</span>
                </div>

                <nav class="flex-1 overflow-y-auto mt-2 space-y-1">
                    <!-- Nav Item Pill -->
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#EADDFF] text-[#21005D]' : 'hover:bg-[#ECE6F0] text-[#49454F]' }}">
                        <div class="flex items-center justify-center w-6 h-6 mr-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        </div>
                        <span class="font-medium">Beranda</span>
                    </a>

                    @if(Auth::user()->roles->count() > 0)
                    <a href="{{ route('penjualan.index') }}" 
                       class="group flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->routeIs('penjualan.*') ? 'bg-[#EADDFF] text-[#21005D]' : 'hover:bg-[#ECE6F0] text-[#49454F]' }}">
                        <div class="flex items-center justify-center w-6 h-6 mr-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
                        </div>
                        <span class="font-medium">Penjualan</span>
                    </a>

                    <a href="{{ route('pembayaran.index') }}" 
                       class="group flex items-center px-4 py-3 rounded-full transition-all duration-200 {{ request()->routeIs('pembayaran.*') ? 'bg-[#EADDFF] text-[#21005D]' : 'hover:bg-[#ECE6F0] text-[#49454F]' }}">
                        <div class="flex items-center justify-center w-6 h-6 mr-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        </div>
                        <span class="font-medium">Pembayaran</span>
                    </a>

                    <!-- Master Dropdown M3 Style -->
                    <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-3 rounded-full transition-all duration-200 {{ request()->routeIs('master.*') ? 'text-[#21005D]' : 'hover:bg-[#ECE6F0] text-[#49454F]' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 mr-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM3 21.5h8v-8H3v8zm2-6h4v4H5v-4z"/></svg>
                                </div>
                                <span class="font-medium">Data Master</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="mt-1 ml-4 border-l border-[#CAC4D0] space-y-1">
                            @can('user-list')
                            <a href="{{ route('master.users.index') }}" 
                               class="block px-6 py-2 rounded-full text-sm font-medium transition-all {{ request()->routeIs('master.users.*') ? 'bg-[#EADDFF] text-[#21005D]' : 'text-[#49454F] hover:bg-[#ECE6F0]' }}">
                                Pengguna
                            </a>
                            @endcan
                            @can('item-list')
                            <a href="{{ route('master.items.index') }}" 
                               class="block px-6 py-2 rounded-full text-sm font-medium transition-all {{ request()->routeIs('master.items.*') ? 'bg-[#EADDFF] text-[#21005D]' : 'text-[#49454F] hover:bg-[#ECE6F0]' }}">
                                Barang
                            </a>
                            @endcan
                            @can('setting-manage')
                            <a href="{{ route('master.settings.index') }}" 
                               class="block px-6 py-2 rounded-full text-sm font-medium transition-all {{ request()->routeIs('master.settings.*') ? 'bg-[#EADDFF] text-[#21005D]' : 'text-[#49454F] hover:bg-[#ECE6F0]' }}">
                                Pengaturan Sistem
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif
                </nav>

                <!-- Fixed Logout M3 -->
                <div class="p-4 mb-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 rounded-full text-[#B3261E] hover:bg-[#F9DEDC] transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                
                <!-- Overlay for mobile sidebar -->
                <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/20 z-20 lg:hidden"></div>

                <!-- Header (M3 Top App Bar Style) -->
                <header class="flex items-center justify-between h-20 bg-[#FEF7FF] px-3 z-10">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-[#49454F] hover:bg-[#ECE6F0] p-2 rounded-full lg:hidden mr-2 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4" x-data="{ userMenuOpen: false }">
                        <div @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" 
                             class="relative flex items-center bg-[#ECE6F0] px-4 py-2 m-2 rounded-full border border-transparent hover:border-[#CAC4D0] transition-all cursor-pointer">
                            <div class="w-6 h-6 bg-[#6750A4] rounded-full flex items-center justify-center text-[10px] text-white font-bold mr-2 uppercase">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <span class="font-medium text-sm text-[#49454F]">{{ Auth::user()->name }}</span>

                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl z-50 py-2 overflow-hidden border border-[#CAC4D0]"
                                 x-cloak>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-[#49454F] hover:bg-[#ECE6F0] transition-colors flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Profil</span>
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-3 sm:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @include('layouts.crud-js')
        @stack('scripts')

        <script>
            $(document).ready(function() {
                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#6750A4',
                    });
                @endif

                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#6750A4',
                    });
                @endif
            });
        </script>
    </body>
</html>
