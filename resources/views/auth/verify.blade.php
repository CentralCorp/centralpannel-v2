@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow-lg border-0 w-100" style="max-width: 400px;">
        <div class="card-body p-5 text-center">
            <h2 class="mb-4 fw-bold text-primary">{{ __('Vérification de l\'adresse e-mail') }}</h2>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                </div>
            @endif
            <p class="mb-3">{{ __('Avant de continuer, veuillez vérifier votre e-mail pour le lien de vérification.') }}</p>
            <p class="mb-4">{{ __('Si vous n\'avez pas reçu l\'e-mail, cliquez ci-dessous pour en recevoir un nouveau :') }}</p>
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">{{ __('Renvoyer le lien') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
