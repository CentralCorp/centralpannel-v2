@extends('admin.base')

@section('title', 'Options Générales')

@section('page-title', 'Options Générales')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.general.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="mods_enabled" class="form-label">Mods activés</label>
                        <input type="hidden" name="mods_enabled" value="0">
                        <input type="checkbox" id="mods_enabled" name="mods_enabled" class="form-check-input" value="1" {{ old('mods_enabled', $options->mods_enabled) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="file_verification" class="form-label">Vérification des fichiers</label>
                        <input type="hidden" name="file_verification" value="0">
                        <input type="checkbox" id="file_verification" name="file_verification" class="form-check-input" value="1" {{ old('file_verification', $options->file_verification) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="embedded_java" class="form-label">Utiliser la version préembarquée de Java</label>
                        <input type="hidden" name="embedded_java" value="0">
                        <input type="checkbox" id="embedded_java" name="embedded_java" class="form-check-input" value="1" {{ old('embedded_java', $options->embedded_java) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="game_folder_name" class="form-label">Nom du dossier de jeu</label>
                    <input type="text" class="form-control" id="game_folder_name" name="game_folder_name" placeholder="lenomdevotredossier" value="{{ old('game_folder_name', $options->game_folder_name) }}">
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="email_verified" class="form-label">Demander une email vérifiée</label>
                        <input type="hidden" name="email_verified" value="0">
                        <input type="checkbox" id="email_verified" name="email_verified" class="form-check-input" value="1" {{ old('email_verified', $options->email_verified) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="role_display" class="form-label">Afficher le rôle</label>
                        <input type="hidden" name="role_display" value="0">
                        <input type="checkbox" id="role_display" name="role_display" class="form-check-input" value="1" {{ old('role_display', $options->role_display) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="money_display" class="form-label">Affichage des points</label>
                        <input type="hidden" name="money_display" value="0">
                        <input type="checkbox" id="money_display" name="money_display" class="form-check-input" value="1" {{ old('money_display', $options->money_display) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="azuriom_url" class="form-label">URL Azuriom</label>
                    <input type="text" class="form-control" id="azuriom_url" name="azuriom_url" placeholder="https:/votresite.com" value="{{ old('azuriom_url', $options->azuriom_url) }}">
                </div>

                <div class="mb-3">
                    <label for="min_ram" class="form-label">Ram minimum par defaut</label>
                    <input type="number" class="form-control" id="default_min_ram" name="min_ram" placeholder="2048" value="{{ old('min_ram', $options['min_ram'] ?? 2048) }}" min="512" max="65536">
                </div>

                <div class="mb-3">
                    <label for="max_ram" class="form-label">Ram maximum par defaut</label>
                    <input type="number" class="form-control" id="default_max_ram" name="max_ram" placeholder="4096" value="{{ old('max_ram', $options['max_ram'] ?? 4096) }}" min="512" max="65536">
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
@endsection
