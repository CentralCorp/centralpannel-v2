@extends('layouts.admin')

@section('title', 'Paramètres Security')

@section('content')
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="container-fluid p-0">
        <h2 class="mb-4 fw-bold">Paramètres de sécurité</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.security.update') }}" method="POST">
                    @csrf

                    <div class="form-check form-switch mb-4">
                        <input type="hidden" name="maintenance" value="0">
                        <input type="checkbox" class="form-check-input" id="maintenance" name="maintenance" value="1" 
                               {{ $securityOptions->maintenance ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="maintenance">
                            Activer la maintenance 
                            <i class="fas fa-tools ms-1 text-muted"></i>
                            <br>
                            <small class="text-muted">Le launcher ne sera plus accessible pour les utilisateurs.</small>
                        </label>
                    </div>

                    <div class="mb-3">
                        <label for="maintenance_message" class="form-label fw-semibold">Message de maintenance</label>
                        <input type="text" class="form-control" id="maintenance_message" name="maintenance_message"
                               value="{{ $securityOptions->maintenance_message }}" required
                               placeholder="Ex : Le launcher est temporairement indisponible...">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
