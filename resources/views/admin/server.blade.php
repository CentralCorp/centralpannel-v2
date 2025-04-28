@extends('admin.base')

@section('title', 'Paramètres Server')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>
    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Paramètres Server</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.server.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="server_name" class="form-label">Nom du serveur</label>
                        <input type="text" class="form-control" id="server_name" name="server_name" placeholder="Le nom de votre serveur" value="{{ $serverOptions->server_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="server_ip" class="form-label">IP du serveur</label>
                        <input type="text" class="form-control" id="server_ip" name="server_ip" placeholder="votreipserveur.com" value="{{ $serverOptions->server_ip }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="server_port" class="form-label">Port du serveur</label>
                        <input type="text" class="form-control" id="server_port" name="server_port" placeholder="25565" value="{{ $serverOptions->server_port }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icône du serveur</label>
                        <input type="file" class="form-control" id="icon" name="icon" accept="image/*">
                    </div>
                    @if ($serverOptions->icon)
                        <div class="mb-3">
                            <label class="form-label">Icône actuelle :</label>
                            <img src="{{ asset('storage/uploads/' . $serverOptions->icon) }}" alt="Icône du serveur" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
@endsection
