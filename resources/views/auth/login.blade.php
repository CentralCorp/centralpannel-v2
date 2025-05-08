@extends('layouts.app')

@section('content')
<div class="container h-100">
    <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg bg-body-tertiary">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-primary mb-2">{{ __('Connexion') }}</h1>
                        <p class="text-body-secondary">{{ __('Bienvenue ! Connectez-vous pour continuer.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label text-body-secondary small fw-medium">{{ __('Adresse e-mail') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0">
                                    <i class="bi bi-envelope text-body-secondary"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="exemple@email.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-body-secondary small fw-medium">{{ __('Mot de passe') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0">
                                    <i class="bi bi-lock text-body-secondary"></i>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                    name="password" required autocomplete="current-password"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-body-secondary small" for="remember">
                                    {{ __('Se souvenir de moi') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-primary small text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Mot de passe oublié ?') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                            {{ __('Connexion') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
