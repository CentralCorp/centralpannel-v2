@extends('install.layout')

@section('content')
    <h1>Création d'un utilisateur</h1>

    <form action="{{ route('install.user.creation.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Terminer l'installation</button>
    </form>
@endsection

