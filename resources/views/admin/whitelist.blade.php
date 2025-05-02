@extends('layouts.admin')

@section('title', 'Gestion de la Whitelist')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Gestion de la Whitelist</h2>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.whitelist.store') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch">
                        <input type="hidden" name="whitelist" value="0">
                        <label for="whitelist" class="form-label">Activer la whitelist</label>
                        <input type="checkbox" id="whitelist" name="whitelist" class="form-check-input" value="1" {{ ($securityOptions->whitelist ?? false) ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="whitelist_users" class="form-label">Ajouter des utilisateurs (séparés par des virgules)</label>
                        <input type="text" class="form-control" id="whitelist_users" name="whitelist_users" placeholder="Utilisateur1, Utilisateur2">
                    </div>

                    <div class="mb-3">
                        <label for="whitelist_roles" class="form-label">Ajouter des rôles (séparés par des virgules)</label>
                        <input type="text" class="form-control" id="whitelist_roles" name="whitelist_roles" placeholder="Rôle1, Rôle2">
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <h2 class="mt-5">Utilisateurs dans la Whitelist</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group mb-4">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $user->users }}</span>
                            <form action="{{ route('admin.whitelist.destroyUser', $user->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <h2 class="mt-5">Rôles dans la Whitelist</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group">
                    @foreach($roles as $role)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $role->role }}</span>
                            <form action="{{ route('admin.whitelist.destroyRole', $role->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
