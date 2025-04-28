@extends('admin.base')

@section('title', 'Paramètres du Loader')

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
            <form method="POST" action="{{ route('admin.loader.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="minecraft_version" class="form-label">Version de Minecraft :</label>
                    <input type="text" class="form-control" id="minecraft_version" name="minecraft_version" placeholder="Version MC au format X.XX.X" value="{{ old('minecraft_version', $row->minecraft_version ?? '') }}">
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="loader-activation" class="form-label">Activer le loader</label>
                        <input type="hidden" name="loader_activation" value="0">
                        <input type="checkbox" id="loader-activation" name="loader_activation" class="form-check-input" value="1" {{ ($row->loader_activation ?? false) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="loader-type" class="form-label">Type de Loader :</label>
                    <select class="form-select" id="loader-type" name="loader_type">
                        <option value="forge" {{ (isset($row) && $row->loader_type == 'forge') ? 'selected' : '' }}>Forge</option>
                        <option value="fabric" {{ (isset($row) && $row->loader_type == 'fabric') ? 'selected' : '' }}>Fabric</option>
                        <option value="legacyfabric" {{ (isset($row) && $row->loader_type == 'legacyfabric') ? 'selected' : '' }}>LegacyFabric</option>
                        <option value="neoForge" {{ (isset($row) && $row->loader_type == 'neoForge') ? 'selected' : '' }}>NeoForge</option>
                        <option value="quilt" {{ (isset($row) && $row->loader_type == 'quilt') ? 'selected' : '' }}>Quilt</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="loader-build-version" class="form-label">Version de Build du loader :</label>
                    <select class="form-select" id="loader-build-version" name="loader_forge_version" style="display:none;">
                    </select>
                    <input type="text" class="form-control" id="loader-build-version-input" name="loader_build_version" style="display:none;" value="{{ old('loader_build_version', $row->loader_build_version ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loaderTypeSelect = document.getElementById('loader-type');
            const mcVersionInput = document.getElementById('minecraft_version');
            const loaderBuildVersionSelect = document.getElementById('loader-build-version');
            const loaderBuildVersionInput = document.getElementById('loader-build-version-input');
            const loaderForgeVersion = "{{ $row->loader_forge_version ?? '' }}";

            function fetchForgeBuildVersions(mcVersion) {
                const apiUrl = `/admin/loader/builds?loader=forge&mc_version=${mcVersion}`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        updateLoaderBuildVersions(data.builds);
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des versions de build:', error);
                    });
            }

            function updateLoaderBuildVersions(builds) {
                loaderBuildVersionSelect.innerHTML = '';

                builds.forEach(build => {
                    const option = document.createElement('option');
                    option.value = build;
                    option.textContent = build;
                    if (build === loaderForgeVersion) {
                        option.selected = true;
                    }
                    loaderBuildVersionSelect.appendChild(option);
                });
                if (loaderTypeSelect.value === 'forge') {
                    loaderBuildVersionSelect.style.display = 'block';
                    loaderBuildVersionInput.style.display = 'none';
                } else {
                    loaderBuildVersionSelect.style.display = 'none';
                    loaderBuildVersionInput.style.display = 'block';
                }
            }

            loaderTypeSelect.addEventListener('change', function() {
                if (loaderTypeSelect.value === 'forge') {
                    const mcVersion = mcVersionInput.value;
                    fetchForgeBuildVersions(mcVersion);
                    loaderBuildVersionSelect.style.display = 'block';
                    loaderBuildVersionInput.style.display = 'none';
                } else {
                    loaderBuildVersionSelect.style.display = 'none';
                    loaderBuildVersionInput.style.display = 'block';
                }
            });

            mcVersionInput.addEventListener('change', function() {
                if (loaderTypeSelect.value === 'forge') {
                    fetchForgeBuildVersions(mcVersionInput.value);
                }
            });

            if (loaderTypeSelect.value === 'forge') {
                fetchForgeBuildVersions(mcVersionInput.value);
                loaderBuildVersionSelect.style.display = 'block';
                loaderBuildVersionInput.style.display = 'none';
            } else {
                loaderBuildVersionSelect.style.display = 'none';
                loaderBuildVersionInput.style.display = 'block';
            }
        });
    </script>
@endsection
