@extends('layouts.admin')

@php
use App\Models\OptionsServer;
@endphp

@section('title', 'Paramètres Server')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="text-3xl font-bold">Liste des serveurs Azuriom</h2>
        
        @if($error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @elseif(!$options)
            <div class="alert alert-warning" role="alert">
                Veuillez d'abord configurer l'URL Azuriom dans les paramètres généraux.
            </div>
        @else
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h3 class="card-title">Serveurs synchronisés</h3>
                </div>
                <div class="card-body">
                    @if(empty($servers))
                        <div class="alert alert-info" role="alert">
                            Aucun serveur n'a été trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Défaut</th>
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
                                            <td>{{ $server['address'] }}</td>
                                            <td>{{ $server['port'] }}</td>
                                            <td>{{ $server['type'] }}</td>
                                            <td>
                                                @if($server['icon'])
                                                    <img src="{{ rtrim($options->azuriom_url, '/') . $server['icon'] }}" alt="Icône du serveur" style="max-width: 50px; max-height: 50px;">
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
            document.addEventListener('DOMContentLoaded', function() {
                const radioButtons = document.querySelectorAll('.server-default');
                
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const serverId = this.dataset.serverId;
                        
                        fetch('/admin/server/set-default', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                server_id: serverId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Mettre à jour l'interface si nécessaire
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
