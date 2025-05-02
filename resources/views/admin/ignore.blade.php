@extends('layouts.admin')

@section('title', 'Gestion des dossiers et fichiers ignorés')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Gestion des dossiers et fichiers ignorés</h2>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.ignore.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ignored_folders" class="form-label">Ajouter des dossiers/fichiers (séparés par des virgules)</label>
                        <input type="text" class="form-control" id="ignored_folders" name="ignored_folders" placeholder="Dossier1, fichier2.txt">
                    </div>


                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <h2 class="mt-5">Dossiers et fichiers ignorés</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group mb-4">
                    @foreach($folders as $folder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $folder->folder_name }}</span>
                            <form action="{{ route('admin.ignore.destroyFolder', $folder->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
@endsection
