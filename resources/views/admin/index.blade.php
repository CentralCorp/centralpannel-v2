@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Welcome to Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Nombre de comptes</h6>
                            <h2 class="mb-0">{{ $userCount ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes de version -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Notes de version</h5>
                    <div class="list-group">
                        @if(isset($releases) && count($releases) > 0)
                            @foreach($releases as $release)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <a href="{{ $release->link }}" target="_blank" class="text-decoration-none">
                                                Version {{ $release->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $release->date }}</small>
                                    </div>
                                    <p class="mb-1">{{ $release->description }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Aucune note de version disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Export/Import -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Export/Import</h5>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <h6 class="mb-2">Export des paramètres</h6>
                            <p class="text-muted small mb-2">Exportez tous les paramètres du panneau d'administration au format .centralcorp</p>
                            <a href="{{ route('admin.settings.export') }}" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Exporter (.centralcorp)
                            </a>
                        </div>
                        <div>
                            <h6 class="mb-2">Import des paramètres</h6>
                            <p class="text-muted small mb-2">Importez des paramètres depuis un fichier .centralcorp</p>
                            <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" class="form-control" name="settings_file" accept=".centralcorp" required>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload me-2"></i>Importer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@endsection
