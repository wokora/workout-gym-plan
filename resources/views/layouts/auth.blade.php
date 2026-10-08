<x-base-layout>
    <x-slot name="title">@yield('title', 'Sign In')</x-slot>

    <div class="min-h-screen w-full flex grow items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- App Logo / Title -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center space-x-2">
                    <img class="size-12" src="{{ asset('images/app-logo.svg') }}" alt="logo" />
                    <span class="text-2xl font-bold tracking-tight text-slate-700 dark:text-navy-100 uppercase">
                        {{ config('app.name', 'Lishe Afya') }}
                    </span>
                </a>
            </div>

            @if (session('success'))
                <div class="alert flex rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-success sm:px-5 mb-5" role="alert">
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert flex rounded-lg border border-error/30 bg-error/10 px-4 py-3 text-error sm:px-5 mb-5" role="alert">
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</x-base-layout>
