@extends('layouts.auth')

@section('content')
            <div class="card">
                <div class="card-header text-center pb-2 pt-3 font-weight-bold" style="font-size: 1.5rem; letter-spacing: 0.05em;">
                    SIGN <span class="text-primary text-glow">IN</span>
                </div>

                @if ($errors->has('oauth'))
                    <div class="alert alert-danger mx-3 mt-3 mb-0 text-center" role="alert" style="font-size: 0.9rem;">
                        {{ $errors->first('oauth') }}
                    </div>
                @endif

                <div class="card-body text-center py-5">
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">
                        Please authenticate via the secure Single Sign-On portal to access your account.
                    </p>

                    <a href="{{ route('login.sso') }}" class="btn btn-primary btn-block py-2.5 d-flex align-items-center justify-content-center" style="font-weight: 600; letter-spacing: 0.05em; font-size: 1rem; border-radius: 0.375rem; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
                        <svg class="mr-2" style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        SIGN IN WITH SSO
                    </a>
                </div>
            </div>
@endsection
