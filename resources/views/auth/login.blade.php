@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center bg-light" style="height: calc(100vh - 200px);">
    <div class="card shadow-lg border-0 w-100" style="max-width: 400px;">
        <div class="card-body p-5">
            <h2 class="mb-4 text-center fw-bold text-primary">{{ __('Connexion') }}</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
                    <input id="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                    <input id="password" type="password" class="form-control rounded-pill @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Se souvenir de moi') }}
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-2">
                    {{ __('Connexion') }}
                </button>
                @if (Route::has('password.request'))
                    <div class="text-center">
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
