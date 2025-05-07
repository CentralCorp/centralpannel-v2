@extends('layouts.admin')

@php
    use App\Models\OptionsServer;
@endphp

@section('title', 'Paramètres Server')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="mb-4 fw-bold">Liste des serveurs Azuriom</h2>

        @if($error)
            <div class="alert alert-danger d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}
            </div>
        @elseif(!$options)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fas fa-cogs me-2"></i> Veuillez d'abord configurer l'URL Azuriom dans les paramètres généraux.
            </div>
        @else
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Serveurs synchronisés</h5>
                </div>
                <div class="card-body">
                    @if(empty($servers))
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i> Aucun serveur n'a été trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 70px;">Défaut</th>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Port</th>
                                        <th>Type</th>
                                        <th>Icône</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servers as $server)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input server-default" 
                                                           type="radio" 
                                                           name="default_server" 
                                                           value="{{ $server['id'] }}"
                                                           data-server-id="{{ $server['id'] }}"
                                                           {{ $defaultServers[$server['id']] ?? false ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>{{ $server['name'] }}</td>
                                            <td><code>{{ $server['address'] }}</code></td>
                                            <td>{{ $server['port'] }}</td>
                                            <td><span class="badge bg-secondary">{{ $server['type'] }}</span></td>
                                            <td>
                                                @if($server['icon'])
                                                    <img src="{{ rtrim($options->azuriom_url, '/') . $server['icon'] }}" 
                                                         alt="Icône du serveur" 
                                                         class="img-thumbnail rounded-circle" 
                                                         style="max-width: 50px; max-height: 50px;">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if($options && !empty($servers))
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.server-default').forEach(radio => {
                    radio.addEventListener('change', function () {
                        const serverId = this.dataset.serverId;

                        fetch('/admin/server/set-default', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ server_id: serverId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Serveur par défaut mis à jour');
                            } else {
                                alert('Échec de la mise à jour du serveur par défaut.');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                        });
                    });
                });
            });
        </script>
        @endpush
    @endif
@endsection
