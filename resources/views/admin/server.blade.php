@extends('layouts.admin')

@php
    use App\Models\OptionsServer;
@endphp

@section('title', 'Paramètres Server')

@section('content')
    <div class="container-fluid p-0">
        <h2 class="mb-4 fw-bold">Liste des serveurs Azuriom</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Port</th>
                                        <th>Type</th>
                                        <th>Icône</th>
                                        <th class="text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servers as $server)
                                        <tr>
                                            <td><strong>{{ $server['name'] }}</strong></td>
                                            <td><code>{{ $server['address'] }}</code></td>
                                            <td>{{ $server['port'] }}</td>
                                            <td><span class="badge bg-info">{{ $server['type'] }}</span></td>
                                            <td>
                                                @if($server['icon'])
                                                    <img src="{{ rtrim($options->azuriom_url, '/') . $server['icon'] }}" 
                                                         alt="Icône du serveur" 
                                                         class="img-thumbnail rounded-circle" 
                                                         style="max-width: 40px; max-height: 40px;">
                                                @else
                                                    <span class="text-muted">Aucune</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!($defaultServers[$server['id']] ?? false))
                                                    <form method="POST" action="{{ route('admin.server.set-default') }}" style="display: inline;" class="set-default-form">
                                                        @csrf
                                                        <input type="hidden" name="server_id" value="{{ $server['id'] }}">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-star"></i> Définir par défaut
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-success fw-bold">
                                                        <i class="bi bi-check-circle-fill"></i> Serveur par défaut
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Information :</strong> Le serveur par défaut sera utilisé comme serveur principal pour toutes les connexions et opérations du launcher.
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
                // Gestion des formulaires de définition de serveur par défaut
                const forms = document.querySelectorAll('.set-default-form');
                
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const serverName = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
                        
                        // Confirmation simple
                        if (!confirm(`Êtes-vous sûr de vouloir définir "${serverName}" comme serveur par défaut ?`)) {
                            e.preventDefault();
                            return false;
                        }
                        
                        // Afficher un indicateur de chargement
                        const button = this.querySelector('button');
                        button.disabled = true;
                        button.innerHTML = '<i class="bi bi-hourglass-split"></i> En cours...';
                    });
                });
            });
        </script>
        @endpush
    @endif
@endsection
