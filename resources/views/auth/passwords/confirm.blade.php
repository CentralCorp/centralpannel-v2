@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 w-100" style="max-width: 400px;">
        <div class="card-body p-5">
            <h2 class="mb-4 text-center fw-bold text-primary">{{ __('Confirmer le mot de passe') }}</h2>
            <p class="mb-4 text-center">{{ __('Veuillez confirmer votre mot de passe avant de continuer.') }}</p>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                    <input id="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-2">
                    {{ __('Confirmer le mot de passe') }}
                </button>
                @if (Route::has('password.request'))
                    <div class="text-center mt-2">
                        <a class="btn btn-link p-0" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
