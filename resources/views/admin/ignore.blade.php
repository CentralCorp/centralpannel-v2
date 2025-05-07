@extends('layouts.admin')

@section('title', 'Gestion des dossiers et fichiers ignorés')

@section('content')
<div class="container-fluid px-4 py-3">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <h2 class="mb-4">Ajouter un dossier ou fichier à ignorer</h2>
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <form action="{{ route('admin.ignore.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="ignored_folders" class="form-label fw-semibold">Chemins à ignorer <span class="text-muted">(séparés par des virgules)</span></label>
                    <input type="text" class="form-control" id="ignored_folders" name="ignored_folders" placeholder="ex: dossier1, fichier2.txt">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <h2 class="mb-3">Dossiers et fichiers actuellement ignorés</h2>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($folders->isEmpty())
                <p class="text-muted">Aucun dossier ou fichier ignoré pour le moment.</p>
            @else
                <ul class="list-group">
                    @foreach($folders as $folder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-break">{{ $folder->folder_name }}</span>
                            <form action="{{ route('admin.ignore.destroyFolder', $folder->id) }}" method="POST" onsubmit="return confirm('Supprimer cet élément ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection
