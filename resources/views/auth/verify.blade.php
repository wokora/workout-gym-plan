@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="card rounded-2xl border border-slate-150 bg-white p-6 shadow-soft dark:border-navy-600 dark:bg-navy-700 dark:shadow-soft-dark sm:p-8">
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-navy-100 uppercase">
            Verify <span class="text-primary dark:text-accent-light">Email</span>
        </h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-navy-300">
            {{ __('Before proceeding, please check your email for a verification link.') }}
        </p>
    </div>

    @if (session('resent'))
        <div class="mt-4 flex rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-xs text-success sm:text-sm">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="mt-6 text-center text-xs text-slate-500 dark:text-navy-300">
        {{ __('If you did not receive the email') }},
        <form class="inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="font-semibold text-primary underline dark:text-accent-light">
                {{ __('click here to request another') }}
            </button>.
        </form>
    </div>
</div>
@endsection
