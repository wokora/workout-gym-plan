@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
    <div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 dark:shadow-soft-dark sm:p-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-navy-100 uppercase">
                Sign <span class="text-primary dark:text-accent-light">In</span>
            </h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-navy-300">
                Please authenticate via the secure Single Sign-On portal to access your account.
            </p>
        </div>

        @if ($errors->has('oauth'))
            <div class="alert mt-4 flex rounded-lg border border-error/30 bg-error/10 px-4 py-3 text-xs text-error sm:text-sm" role="alert">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('oauth') }}</span>
                </div>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('login.sso') }}"
                class="btn h-12 w-full space-x-2 rounded-xl bg-primary text-base font-semibold text-white shadow-soft transition-all hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90 flex items-center justify-center">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>SIGN IN WITH SSO</span>
            </a>
        </div>
    </div>
@endsection
