@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 w-100" style="max-width: 450px;">
        <div class="card-body p-5">
            <h2 class="mb-4 text-center fw-bold text-primary">{{ __('Inscription') }}</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nom complet') }}</label>
                    <input id="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                    <input id="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
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
                    {{ __('Inscription') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
