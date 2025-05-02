@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 w-100" style="max-width: 400px;">
        <div class="card-body p-5">
            <h2 class="mb-4 text-center fw-bold text-primary">{{ __('Réinitialiser le mot de passe') }}</h2>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
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
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-2">
                    {{ __('Envoyer le lien de réinitialisation') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
