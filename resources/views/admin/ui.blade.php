@extends('admin.base')

@section('title', 'Paramètres UI')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Paramètres UI</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.ui.update') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch">
                        <input type="hidden" name="alert_activation" value="0">
                        <label for="alert_activation" class="form-label">Activer le message d'alerte</label>
                        <input type="checkbox" id="alert_activation" name="alert_activation" class="form-check-input" value="1" {{ $uiOptions->alert_activation ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="alert_scroll" value="0">
                        <label for="alert_scroll" class="form-label">Activer le scroll du message d'alerte</label>
                        <input type="checkbox" id="alert_scroll" name="alert_scroll" class="form-check-input" value="1" {{ $uiOptions->alert_scroll ? 'checked' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="alert_msg" class="form-label">Message d'alerte</label>
                        <input type="text" class="form-control" id="alert_msg" name="alert_msg" value="{{ $uiOptions->alert_msg }}" required>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="video_activation" value="0">
                        <label for="video_activation" class="form-label">Activer la vidéo communautaire</label>
                        <input type="checkbox" id="video_activation" name="video_activation" class="form-check-input" value="1" {{ $uiOptions->video_activation ? 'checked' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="video_url" class="form-label">URL de la vidéo</label>
                        <input type="text" class="form-control" id="video_url" name="video_url" value="{{ $uiOptions->video_url }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="splash" class="form-label">Message splash (lancement du launcher)</label>
                        <input type="text" class="form-control" id="splash" name="splash" value="{{ $uiOptions->splash }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="splash_author" class="form-label">Auteur du splash</label>
                        <input type="text" class="form-control" id="splash_author" name="splash_author" value="{{ $uiOptions->splash_author }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
@endsection
