@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 w-100" style="max-width: 400px;">
        <div class="card-body p-5">
            <h2 class="mb-4 text-center fw-bold text-primary">{{ __('Nouveau mot de passe') }}</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                    <input id="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
                    <input id="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                    <input id="password-confirm" type="password" class="form-control rounded-pill" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-2">
                    {{ __('Réinitialiser le mot de passe') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
