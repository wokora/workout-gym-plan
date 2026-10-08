@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 dark:shadow-soft-dark sm:p-8">
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-navy-100 uppercase">
            Set New <span class="text-primary dark:text-accent-light">Password</span>
        </h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-navy-300">
            Enter your new password below to reset your credentials.
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                {{ __('E-Mail Address') }}
            </label>
            <input id="email"
                type="email"
                name="email"
                value="{{ $email ?? old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="email@example.com"
                class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('email') border-error @enderror">
            @error('email')
                <p class="mt-1 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                {{ __('Password') }}
            </label>
            <input id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent @error('password') border-error @enderror">
            @error('password')
                <p class="mt-1 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password-confirm" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-navy-200 mb-1.5">
                {{ __('Confirm Password') }}
            </label>
            <input id="password-confirm"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-slate-800 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:text-navy-100 dark:hover:border-navy-400 dark:focus:border-accent">
        </div>

        <button type="submit"
            class="btn mt-6 h-11 w-full rounded-xl bg-primary text-sm font-semibold text-white shadow-soft transition-all hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus">
            {{ __('Reset Password') }}
        </button>
    </form>
</div>
@endsection
