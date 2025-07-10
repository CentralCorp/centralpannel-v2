@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1>Mise à jour du panel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <p>Version actuelle : <strong>{{ $currentVersion }}</strong></p>
            @if($info)
                <p>Dernière version disponible : <strong>{{ $info['version'] ?? '?' }}</strong></p>
                <p>PHP requis : <strong>{{ $info['php_version'] ?? '?' }}</strong></p>
                <p>Nom du fichier : <strong>{{ $info['file'] ?? '?' }}</strong></p>
            @else
                <p>Impossible de récupérer les informations de mise à jour.</p>
            @endif
        </div>
    </div>

    @if($hasUpdate)
        <form method="POST" action="{{ route('admin.update.run') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Mettre à jour maintenant</button>
        </form>
    @else
        <div class="alert alert-info">Aucune mise à jour disponible.</div>
    @endif
</div>
@endsection
