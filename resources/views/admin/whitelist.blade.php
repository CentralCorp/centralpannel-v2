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

                    @if(isset($azuriomUsers) && count($azuriomUsers) > 0)
                        <div class="mb-3">
                            <label class="form-label">Sélectionner des utilisateurs</label>
                            <div class="row" id="userList">
                                @foreach($azuriomUsers as $user)
                                    @if(!$user['is_banned'])
                                        <div class="col-md-4 mb-2 user-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="whitelist_users[]" value="{{ $user['name'] }}" id="user_{{ $user['id'] }}">
                                                <label class="form-check-label" for="user_{{ $user['id'] }}">
                                                    <span class="user-name">{{ $user['name'] }}</span>
                                                    <span style="color: {{ $user['role']['color'] }};">({{ $user['role']['name'] }})</span>
                                                    @if($user['role']['is_admin'])
                                                        <span class="badge bg-danger">Admin</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(isset($azuriomRoles) && count($azuriomRoles) > 0)
                        <div class="mb-3">
                            <label class="form-label">Sélectionner des rôles Azuriom</label>
                            <div class="row" id="roleList">
                                @foreach($azuriomRoles as $role)
                                    <div class="col-md-4 mb-2 role-item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="azuriom_roles[]" value="{{ $role['name'] }}" id="role_{{ $role['id'] }}">
                                            <label class="form-check-label" for="role_{{ $role['id'] }}">
                                                <span class="role-name" style="color: {{ $role['color'] }};">{{ $role['name'] }}</span>
                                                @if($role['is_admin'])
                                                    <span class="badge bg-danger">Admin</span>
                                                @endif
                                                <span class="badge bg-secondary">Power: {{ $role['power'] }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
