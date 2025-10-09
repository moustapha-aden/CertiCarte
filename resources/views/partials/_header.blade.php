{{-- Header Component --}}
<header class="bg-white sticky top-0 z-50 shadow-md border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo and Title --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-4 hover:opacity-80 transition-opacity">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-700 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z" />
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée Ahmed Farah Ali</h1>
                        <p class="text-xs text-gray-500 font-medium">@yield('page-subtitle', 'Système de Gestion')</p>
                    </div>
                </a>
            </div>

            {{-- Navigation Links (Centered) --}}
            <nav class="hidden md:flex items-center space-x-6">
                @can('view_classes')
                    <a href="{{ route('classes.index') }}"
                        class="text-base font-semibold px-4 py-2 rounded-lg transition duration-150 {{ request()->routeIs('classes.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        Classes
                    </a>
                @endcan

                @can('view_students')
                    <a href="{{ route('students.index') }}"
                        class="text-base font-semibold px-4 py-2 rounded-lg transition duration-150 {{ request()->routeIs('students.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        Étudiants
                    </a>
                @endcan

                @can('view_users')
                    <a href="{{ route('users.index') }}"
                        class="text-base font-semibold px-4 py-2 rounded-lg transition duration-150 {{ request()->routeIs('users.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        Personnel
                    </a>
                @endcan

                @canany(['generate_certificates', 'generate_cards', 'generate_attendance_lists'])
                    <a href="{{ route('reports.index') }}"
                        class="text-base font-semibold px-4 py-2 rounded-lg transition duration-150 {{ request()->routeIs('reports.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        Rapports
                    </a>
                @endcanany
            </nav>

            {{-- Mobile Menu Button --}}
            <button type="button"
                class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                onclick="toggleMobileMenu()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- User Profile and Logout (Right Side) --}}
            <div class="relative group">
                <div
                    class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'Administrateur' }}</p>
                        {{-- <p class="text-xs text-indigo-600 font-medium">
                            {{ Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Admin' }}
                        </p> --}}
                    </div>
                    <div
                        class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                        <span
                            class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                    </div>
                    {{-- <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg> --}}
                </div>

                {{-- Dropdown Menu --}}
                <div
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                            <p class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'Administrateur' }}</p>
                            <p class="text-xs text-indigo-600 font-medium">
                                {{ Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Admin' }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-800 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z" />
                                </svg>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-4">
            <div class="space-y-2">
                @can('view_classes')
                    <a href="{{ route('classes.index') }}"
                        class="block px-4 py-3 text-base font-semibold text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('classes.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                        Classes
                    </a>
                @endcan

                @can('view_students')
                    <a href="{{ route('students.index') }}"
                        class="block px-4 py-3 text-base font-semibold text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('students.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                        Étudiants
                    </a>
                @endcan

                @can('view_users')
                    <a href="{{ route('users.index') }}"
                        class="block px-4 py-3 text-base font-semibold text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                        Personnel
                    </a>
                @endcan

                @canany(['generate_certificates', 'generate_cards', 'generate_attendance_lists'])
                    <a href="{{ route('reports.index') }}"
                        class="block px-4 py-3 text-base font-semibold text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                        Rapports
                    </a>
                @endcanany

                {{-- Mobile Action Button --}}
                @hasSection('header-action')
                    <div class="px-3 py-2">
                        @yield('header-action')
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>

{{-- Mobile Menu JavaScript --}}
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
