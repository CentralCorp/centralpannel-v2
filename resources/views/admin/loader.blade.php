@extends('layouts.admin')

@section('title', 'Paramètres du Loader')
@section('page-title', 'Paramètres du Loader')

@section('content')
<div class="container-fluid px-4 py-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.loader.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="minecraft_version" class="form-label fw-semibold">Version de Minecraft</label>
                    <input type="text" class="form-control" id="minecraft_version" name="minecraft_version" placeholder="ex: 1.20.1" value="{{ old('minecraft_version', $row->minecraft_version ?? '') }}">
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="loader_activation" value="0">
                    <input type="checkbox" class="form-check-input" id="loader-activation" name="loader_activation" value="1" {{ ($row->loader_activation ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="loader-activation">Activer le loader</label>
                </div>

                <div class="mb-3">
                    <label for="loader-type" class="form-label fw-semibold">Type de Loader</label>
                    <select class="form-select" id="loader-type" name="loader_type">
                        @foreach (['forge', 'fabric', 'legacyfabric', 'neoForge', 'quilt'] as $type)
                            <option value="{{ $type }}" {{ (isset($row) && $row->loader_type === $type) ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="loader-build-version" class="form-label fw-semibold">Version de Build</label>
                    <select class="form-select" id="loader-build-version" name="loader_forge_version"></select>
                    <input type="text" class="form-control d-none" id="loader-build-version-input" name="loader_build_version" value="{{ old('loader_build_version', $row->loader_build_version ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary btn-sm rounded-2">Enregistrer</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loaderType = document.getElementById('loader-type');
        const mcVersion = document.getElementById('minecraft_version');
        const buildSelect = document.getElementById('loader-build-version');
        const buildInput = document.getElementById('loader-build-version-input');
        const currentVersion = "{{ $row->loader_forge_version ?? '' }}";

        function toggleBuildInputs(showSelect) {
            buildSelect.classList.toggle('d-none', !showSelect);
            buildInput.classList.toggle('d-none', showSelect);
        }

        function loadForgeBuilds(version) {
            const url = `/admin/loader/builds?mc_version=${version}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    buildSelect.innerHTML = '';
                    data.builds.forEach(build => {
                        const option = document.createElement('option');
                        option.value = build;
                        option.text = build;
                        if (build === currentVersion) option.selected = true;
                        buildSelect.appendChild(option);
                    });
                    toggleBuildInputs(true);
                })
                .catch(err => console.error('Erreur de chargement Forge:', err));
        }

        function loadFabricVersions() {
            fetch('/admin/loader/fabric-versions')
                .then(response => response.json())
                .then(data => {
                    buildSelect.innerHTML = '';
                    data.versions.forEach(version => {
                        const option = document.createElement('option');
                        option.value = version.version;
                        option.text = version.version;
                        if (version.version === currentVersion) option.selected = true;
                        buildSelect.appendChild(option);
                    });
                    toggleBuildInputs(true);
                })
                .catch(err => console.error('Erreur de chargement Fabric:', err));
        }

        loaderType.addEventListener('change', () => {
            switch(loaderType.value) {
                case 'forge':
                    loadForgeBuilds(mcVersion.value);
                    break;
                case 'fabric':
                    loadFabricVersions();
                    break;
                default:
                    toggleBuildInputs(false);
            }
        });

        mcVersion.addEventListener('input', () => {
            if (loaderType.value === 'forge') {
                loadForgeBuilds(mcVersion.value);
            }
        });

        // Initialisation
        if (loaderType.value === 'forge') {
            loadForgeBuilds(mcVersion.value);
        } else if (loaderType.value === 'fabric') {
            loadFabricVersions();
        } else {
            toggleBuildInputs(false);
        }
    });
</script>
@endsection
