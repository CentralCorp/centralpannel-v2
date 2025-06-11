@extends('layouts.admin')

@section('title', 'Mods optionnels')

@section('content')
<div class="container-fluid px-0">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="mb-4 fw-bold">Paramètres des Mods</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="mb-4">
                <label for="optionalMods" class="form-label">Sélectionnez un mod optionnel :</label>
                <select id="optionalMods" name="selectedMod" class="form-select" onchange="handleSelectChange()">
                    <option value="">-- Sélectionnez un mod --</option>
                    @foreach ($optionalMods as $mod)
                        <option value="{{ $mod->id }}" {{ old('selectedMod', $selectedModId) == $mod->id ? 'selected' : '' }}>
                            {{ $mod->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="modDetails" class="{{ $selectedModId ? '' : 'd-none' }}">
                <h4 class="mb-3 fw-semibold">Détails du Mod</h4>

                <form method="POST" action="{{ route('admin.mods.updateOptional') }}" enctype="multipart/form-data" id="modForm" onsubmit="return validateForm()">
                    @csrf
                    <input type="hidden" name="mod_id" id="mod_id" value="{{ $selectedModId ?? '' }}">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fichier du mod</label>
                            <input type="text" id="mod_file" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom du Mod</label>
                            <input type="text" name="optional_name" id="optional_name" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="optional_description" id="optional_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image Actuelle</label><br>
                        <img id="current_image" src="" alt="" class="rounded d-none mb-2" style="height: 64px; width: 64px; object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouvelle Image</label>
                        <input type="file" name="optional_image" accept="image/jpeg,image/png,image/gif" class="form-control">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="optional_recommended" value="1" id="optional_recommended" class="form-check-input">
                        <label class="form-check-label" for="optional_recommended">Recommandé</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Modifier</button>
                        <button type="button" id="deleteBtn" class="btn btn-outline-danger" onclick="deleteMod()">Supprimer</button>
                    </div>
                </form>
            </div>

            <h4 class="mt-5 mb-3 fw-semibold">Mods Disponibles</h4>

            <ul class="list-group">
                @foreach ($modsData as $mod)
                    @if (!in_array($mod['file'], $optionalMods->pluck('file')->toArray()))
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <div class="me-auto">{{ $mod['name'] }}</div>
                            <form method="POST" action="{{ route('admin.mods.addOptional') }}" enctype="multipart/form-data" class="ms-auto">
                                @csrf
                                <input type="hidden" name="file" value="{{ $mod['file'] }}">
                                <input type="hidden" name="name" value="{{ $mod['name'] }}">
                                <input type="hidden" name="description" value="{{ $mod['description'] }}">
                                <input type="hidden" name="icon" value="{{ $mod['icon'] }}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Ajouter en tant que mod optionnel</button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
    const optionalModsSelect = document.getElementById('optionalMods');
    const modDetails = document.getElementById('modDetails');
    const modIdInput = document.getElementById('mod_id');
    const fileInput = document.getElementById('mod_file');
    const nameInput = document.getElementById('optional_name');
    const descriptionInput = document.getElementById('optional_description');
    const currentImage = document.getElementById('current_image');
    const recommendedCheckbox = document.getElementById('optional_recommended');

    function handleSelectChange() {
        const selectedModId = optionalModsSelect.value;

        if (selectedModId) {
            fetch(`/admin/mods/${selectedModId}`)
                .then(response => response.json())
                .then(data => {
                    modIdInput.value = data.id;
                    fileInput.value = data.file;
                    nameInput.value = data.name;
                    descriptionInput.value = data.description;
                    currentImage.src = '/storage/' + data.icon;
                    currentImage.classList.remove('d-none');
                    recommendedCheckbox.checked = data.recommended;

                    modDetails.classList.remove('d-none');
                })
                .catch(error => console.error('Erreur:', error));
        } else {
            modIdInput.value = '';
            fileInput.value = '';
            nameInput.value = '';
            descriptionInput.value = '';
            currentImage.src = '';
            currentImage.classList.add('d-none');
            recommendedCheckbox.checked = false;
            modDetails.classList.add('d-none');
        }
    }

    function validateForm() {
        if (!optionalModsSelect.value) {
            Swal.fire('Erreur', 'Veuillez sélectionner un mod à éditer.', 'error');
            return false;
        }
        return true;
    }

    function deleteMod() {
        const selectedModId = optionalModsSelect.value;
        if (!selectedModId) {
            Swal.fire('Erreur', 'Veuillez sélectionner un mod à supprimer.', 'error');
            return;
        }

        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/mods/delete/${selectedModId}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
