@extends('layouts.admin')

@section('title', 'Background')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 fw-bold text-dark">Fond d'écran Par Rôle</h2>
            <p class="text-muted mb-0">Personnalisez les fonds d'écran pour chaque rôle</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        @foreach($roles as $role)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($role['is_admin'])
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">{{ $role['name'] }}</h5>
                        </div>
                    @else
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">{{ $role['name'] }}</h5>
                        </div>
                    @endif

                    <div class="card-body">
                        @if(isset($backgrounds[$role['id']]))
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $backgrounds[$role['id']]->image_path) }}" 
                                     class="img-fluid rounded" 
                                     alt="Background pour {{ $role['name'] }}">
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 180px;">
                                <span class="text-muted">Aucune image</span>
                            </div>
                        @endif

                        <form action="{{ route('admin.bg.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role['id'] }}">

                            <div class="mb-3">
                                <label for="bg_image_{{ $role['id'] }}" class="form-label">Choisir une image</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="bg_image_{{ $role['id'] }}" 
                                       name="bg_image" 
                                       required
                                       onchange="updateFileName(this, 'file-name-{{ $role['id'] }}')">
                                <div class="form-text" id="file-name-{{ $role['id'] }}">Aucun fichier sélectionné</div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-arrow-bar-up me-2"></i>
                                Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function updateFileName(input, targetId) {
    const fileName = input.files[0]?.name || 'Aucun fichier sélectionné';
    document.getElementById(targetId).textContent = fileName;
}
</script>
@endpush
@endsection
