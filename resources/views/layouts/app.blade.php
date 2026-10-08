<x-app-layout>
    <x-slot name="title">@yield('title', config('app.name', 'Lishe Afya'))</x-slot>

    <!-- Main Content Wrapper -->
    <main class="main-content w-full px-[var(--margin-x)] pb-8 pt-5">
        @if (session('success'))
            <div class="alert flex rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-success sm:px-5 mb-5" role="alert">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert flex rounded-lg border border-error/30 bg-error/10 px-4 py-3 text-error sm:px-5 mb-5" role="alert">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <x-slot name="script">
        @yield('script')
        @stack('scripts')
    </x-slot>
</x-app-layout>
