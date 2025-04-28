@extends('admin.base')

@section('title', 'Paramètres Security')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Paramètres Security</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.security.update') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch">
                        <input type="hidden" name="maintenance" value="0">
                        <label for="maintenance" class="form-check-label">Activer la maintenance (le launcher ne sera plus accessible pour personne)</label>
                        <input type="checkbox" id="maintenance" name="maintenance" class="form-check-input" value="1" {{ $securityOptions->maintenance ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="maintenance_message" class="form-label">Message de maintenance</label>
                        <input type="text" class="form-control" id="maintenance_message" name="maintenance_message" value="{{ $securityOptions->maintenance_message }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
@endsection
