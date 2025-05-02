@extends('layouts.admin')

@section('title', 'Mods optionnels')

@section('content')
    <div class="alert alert-success" role="alert" style="display: {{ session('success') ? 'block' : 'none' }};">
        {{ session('success') }}
    </div>
    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Paramètres des Mods</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="mb-3">
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

                <div id="modDetails" class="{{ $selectedModId ? '' : 'hidden' }}">
                    <h3 class="text-2xl font-semibold mb-3">Détails du Mod</h3>
                    <form method="post" action="{{ route('admin.mods.updateOptional') }}" enctype="multipart/form-data" id="modForm" onsubmit="return validateForm()">
                        @csrf
                        <input type="hidden" name="mod_id" id="mod_id" value="{{ $selectedModId ?? '' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="form-label">Fichier du mod</label>
                                <input type="text" id="mod_file" class="form-control" readonly>
                            </div>
                            <div>
                                <label class="form-label">Nom du Mod</label>
                                <input type="text" name="optional_name" id="optional_name" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="optional_description" id="optional_description" class="form-control"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Image Actuelle</label>
                            <img id="current_image" src="" alt="" class="rounded mb-2 hidden" style="height: 64px; width: 64px; object-fit: cover;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nouvelle Image</label>
                            <input type="file" name="optional_image" accept="image/jpeg,image/png,image/gif" class="form-control">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="optional_recommended" value="1" id="optional_recommended" class="form-check-input">
                            <label class="form-check-label">Recommandé</label>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-success rounded">Modifier</button>
                            <button type="button" id="deleteBtn" class="btn btn-danger rounded" onclick="deleteMod()">Supprimer</button>
                        </div>
                    </form>
                </div>

                <h3 class="text-2xl font-semibold mt-4">Mods Disponibles</h3>
                <ul class="list-group">
                    @foreach ($modsData as $index => $mod)
                        @if (!in_array($mod['file'], $optionalMods->pluck('file')->toArray()))
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                <form method="post" action="{{ route('admin.mods.addOptional') }}" class="d-flex align-items-center" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $mod['file'] }}">
                                    <input type="hidden" name="name" value="{{ $mod['name'] }}">
                                    <input type="hidden" name="description" value="{{ $mod['description'] }}">
                                    <input type="hidden" name="icon" value="{{ $mod['icon'] }}">
                                    <span class="me-2">{{ $mod['name'] }}</span>
                                    <button type="submit" class="btn btn-primary rounded">Ajouter en tant que mod optionnel</button>
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
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur de récupération des données du mod.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        modIdInput.value = data.id;
                        fileInput.value = data.file;
                        nameInput.value = data.name;
                        descriptionInput.value = data.description;
                        currentImage.src = '/storage/uploads/' + data.icon;
                        currentImage.classList.remove('hidden');
                        recommendedCheckbox.checked = data.recommended;

                        modDetails.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
            } else {
                modIdInput.value = '';
                fileInput.value = '';
                nameInput.value = '';
                descriptionInput.value = '';
                currentImage.src = '';
                currentImage.classList.add('hidden');
                recommendedCheckbox.checked = false;
                modDetails.classList.add('hidden');
            }
        }

        function validateForm() {
            const selectedModId = optionalModsSelect.value;
            if (!selectedModId) {
                Swal.fire('Erreur', 'Veuillez sélectionner un mod à éditer.', 'error');
                return false; // Empêche l'envoi du formulaire
            }
            return true; // Autorise l'envoi du formulaire
        }

        function deleteMod() {
            const selectedModId = optionalModsSelect.value;
            if (!selectedModId) {
                Swal.fire('Erreur', 'Veuillez sélectionner un mod à supprimer.', 'error');
                return;
            }

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action ne peut pas être annulée.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
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
