@extends('install.layout')

@section('content')
    <h1>Configuration de la base de données</h1>

    <form action="{{ route('install.db.configuration.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="db_host" class="form-label">Hôte de la base de données</label>
            <input type="text" class="form-control" name="db_host" required>
        </div>
        <div class="mb-3">
            <label for="db_name" class="form-label">Nom de la base de données</label>
            <input type="text" class="form-control" name="db_name" required>
        </div>
        <div class="mb-3">
            <label for="db_user" class="form-label">Utilisateur de la base de données</label>
            <input type="text" class="form-control" name="db_user" required>
        </div>
        <div class="mb-3">
            <label for="db_password" class="form-label">Mot de passe de la base de données</label>
            <input type="password" class="form-control" name="db_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Suivant</button>
    </form>
@endsection

