@extends('layouts.admin')

@section('title', 'Gestion de la Whitelist')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-fluid p-0">
        <h2 class="text-3xl fw-bold mb-4">Gestion de la Whitelist</h2>

        <!-- Activation de la whitelist -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.whitelist.store') }}" method="POST">
                    @csrf
                    <fieldset class="border rounded p-3 mb-4">
                        <legend class="w-auto px-2">Activation</legend>
                        <div class="form-check form-switch">
                            <input type="hidden" name="whitelist" value="0">
                            <input type="checkbox" class="form-check-input" id="whitelist" name="whitelist" value="1"
                                   {{ ($securityOptions->whitelist ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="whitelist">Activer la whitelist</label>
                        </div>
                    </fieldset>

                    <!-- Sélection des utilisateurs Azuriom -->
                    @if(isset($azuriomUsers) && count($azuriomUsers) > 0)
                        <fieldset class="border rounded p-3 mb-4">
                            <legend class="w-auto px-2">Utilisateurs Azuriom</legend>
                            <div class="row">
                                @foreach($azuriomUsers as $user)
                                    @if(!$user['is_banned'])
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="whitelist_users[]"
                                                       value="{{ $user['name'] }}" id="user_{{ $user['id'] }}">
                                                <label class="form-check-label" for="user_{{ $user['id'] }}">
                                                    <strong class="text-truncate" style="max-width: 200px;">{{ $user['name'] }}</strong>
                                                    @if($user['role']['is_admin'])
                                                        <span class="badge bg-danger ms-1">Admin</span>
                                                    @else   
                                                        <span class="badge ms-1" style="color: {{ $user['role']['color'] }};">({{ $user['role']['name'] }})</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </fieldset>
                    @endif

                    <!-- Sélection des rôles Azuriom -->
                    @if(isset($azuriomRoles) && count($azuriomRoles) > 0)
                        <fieldset class="border rounded p-3 mb-4">
                            <legend class="w-auto px-2">Rôles Azuriom</legend>
                            <div class="row">
                                @foreach($azuriomRoles as $role)
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="azuriom_roles[]"
                                                   value="{{ $role['name'] }}" id="role_{{ $role['id'] }}">
                                            <label class="form-check-label" for="role_{{ $role['id'] }}">
                                                <strong class="text-truncate" style="max-width: 200px; color: {{ $role['color'] }};">{{ $role['name'] }}</strong>
                                                @if($role['is_admin'])
                                                    <span class="badge bg-danger ms-1">Admin</span>
                                                @endif
                                                <span class="badge bg-secondary ms-1">Power: {{ $role['power'] }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endif

                    <!-- Bouton de soumission -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">💾 Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des utilisateurs dans la Whitelist -->
        <h3 class="mb-3">Utilisateurs dans la Whitelist</h3>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                @if($users->isEmpty())
                    <p class="text-muted">Aucun utilisateur actuellement whitelisté.</p>
                @else
                    <ul class="list-group">
                        @foreach($users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-truncate" style="max-width: 200px;">{{ $user->users }}</span>
                                <form action="{{ route('admin.whitelist.destroyUser', $user->id) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Liste des rôles dans la Whitelist -->
        <h3 class="mb-3">Rôles dans la Whitelist</h3>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                @if($roles->isEmpty())
                    <p class="text-muted">Aucun rôle actuellement whitelisté.</p>
                @else
                    <ul class="list-group">
                        @foreach($roles as $role)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-truncate" style="max-width: 200px;">{{ $role->role }}</span>
                                <form action="{{ route('admin.whitelist.destroyRole', $role->id) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
