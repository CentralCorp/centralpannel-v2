@extends('layouts.admin')

@section('title', 'Options Générales')
@section('page-title', 'Options Générales')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Des erreurs sont survenues :</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.general.update') }}" method="POST">
                @csrf

                <fieldset class="border p-3 mb-4 rounded">
                    <legend class="float-none w-auto px-2">Fonctionnalités</legend>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @php
                            $switches = [
                                ['id' => 'mods_enabled', 'label' => 'Mods activés'],
                                ['id' => 'file_verification', 'label' => 'Vérification des fichiers'],
                                ['id' => 'embedded_java', 'label' => 'Java préembarquée'],
                                ['id' => 'email_verified', 'label' => 'Email vérifiée obligatoire'],
                                ['id' => 'role_display', 'label' => 'Afficher le rôle'],
                                ['id' => 'money_display', 'label' => 'Afficher les points'],
                            ];
                        @endphp

                        @foreach ($switches as $switch)
                            <div class="col">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="{{ $switch['id'] }}" value="0">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="{{ $switch['id'] }}"
                                           name="{{ $switch['id'] }}"
                                           value="1"
                                           {{ old($switch['id'], $options->{$switch['id']}) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $switch['id'] }}">{{ $switch['label'] }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="border p-3 mb-4 rounded">
                    <legend class="float-none w-auto px-2">Paramètres techniques</legend>

                    <div class="mb-3">
                        <label for="game_folder_name" class="form-label">Nom du dossier de jeu</label>
                        <input type="text"
                               class="form-control"
                               id="game_folder_name"
                               name="game_folder_name"
                               placeholder="lenomdevotredossier"
                               value="{{ old('game_folder_name', $options->game_folder_name) }}">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="default_min_ram" class="form-label">RAM minimum (MB)</label>
                            <input type="number"
                                   class="form-control"
                                   id="default_min_ram"
                                   name="min_ram"
                                   placeholder="2048"
                                   min="512"
                                   max="65536"
                                   value="{{ old('min_ram', $options['min_ram'] ?? 2048) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="default_max_ram" class="form-label">RAM maximum (MB)</label>
                            <input type="number"
                                   class="form-control"
                                   id="default_max_ram"
                                   name="max_ram"
                                   placeholder="4096"
                                   min="512"
                                   max="65536"
                                   value="{{ old('max_ram', $options['max_ram'] ?? 4096) }}">
                        </div>
                    </div>
                </fieldset>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">💾 Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection
