@extends('layouts.admin')

@section('title', 'Configuration')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Configuration</h2>
        
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header">
                <h3 class="card-title">Paramètres généraux</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.config.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="app_name">Nom de l'application</label>
                        <input type="text" class="form-control" id="app_name" name="app_name" 
                               value="{{ env('APP_NAME') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="azuriom_url" class="form-label">URL Azuriom</label>
                        <input type="text" class="form-control" id="azuriom_url" name="azuriom_url" 
                               placeholder="https://votre-site.azuriom.com" 
                               value="{{ $options->azuriom_url ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="azuriom_api_key" class="form-label">Clé API Azuriom</label>
                        <input type="text" class="form-control" id="azuriom_api_key" name="azuriom_api_key" 
                               placeholder="Votre clé API Azuriom (plugin API Extender)" 
                               value="{{ $options->azuriom_api_key ?? '' }}" required>
                        <small class="form-text text-muted">La clé API est nécessaire pour la synchronisation avec Azuriom.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
@endsection


