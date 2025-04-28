@extends('admin.base')

@section('title', 'Paramètres RPC')

@section('page-title', 'Paramètres RPC')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.rpc.update') }}" method="POST">
                @csrf
                <div class="form-body">
                    <div class="form-check form-switch">
                        <input type="hidden" name="rpc_activation" value="0"> <!-- Champ caché pour la valeur 0 -->
                        <label for="rpc_activation" class="form-label">Activer RPC</label>
                        <input type="checkbox" id="rpc_activation" name="rpc_activation" class="form-check-input" value="1"
                            {{ old('rpc_activation', optional($rpcOptions)->rpc_activation) ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_id" class="form-label">ID Client pour le RPC</label>
                        <input type="text" class="form-control" id="rpc_id" name="rpc_id"
                               value="{{ old('rpc_id', optional($rpcOptions)->rpc_id) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_details" class="form-label">Message de détails</label>
                        <input type="text" class="form-control" id="rpc_details" name="rpc_details"
                               value="{{ old('rpc_details', optional($rpcOptions)->rpc_details) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_state" class="form-label">Message de l'état</label>
                        <input type="text" class="form-control" id="rpc_state" name="rpc_state"
                               value="{{ old('rpc_state', optional($rpcOptions)->rpc_state) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_large_text" class="form-label">Message pour la grande image</label>
                        <input type="text" class="form-control" id="rpc_large_text" name="rpc_large_text"
                               value="{{ old('rpc_large_text', optional($rpcOptions)->rpc_large_text) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_small_text" class="form-label">Message pour la petite image</label>
                        <input type="text" class="form-control" id="rpc_small_text" name="rpc_small_text"
                               value="{{ old('rpc_small_text', optional($rpcOptions)->rpc_small_text) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="rpc_button1" class="form-label">Nom du 1er bouton</label>
                        <input type="text" class="form-control" id="rpc_button1" name="rpc_button1"
                               value="{{ old('rpc_button1', optional($rpcOptions)->rpc_button1) }}">
                    </div>

                    <div class="mb-3">
                        <label for="rpc_button1_url" class="form-label">URL du 1er bouton</label>
                        <input type="url" class="form-control" id="rpc_button1_url" name="rpc_button1_url"
                               value="{{ old('rpc_button1_url', optional($rpcOptions)->rpc_button1_url) }}">
                    </div>

                    <div class="mb-3">
                        <label for="rpc_button2" class="form-label">Nom du 2ème bouton</label>
                        <input type="text" class="form-control" id="rpc_button2" name="rpc_button2"
                               value="{{ old('rpc_button2', optional($rpcOptions)->rpc_button2) }}">
                    </div>

                    <div class="mb-3">
                        <label for="rpc_button2_url" class="form-label">URL du 2ème bouton</label>
                        <input type="url" class="form-control" id="rpc_button2_url" name="rpc_button2_url"
                               value="{{ old('rpc_button2_url', optional($rpcOptions)->rpc_button2_url) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection
