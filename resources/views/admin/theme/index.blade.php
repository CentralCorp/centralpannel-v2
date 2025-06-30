@extends('layouts.admin')

@section('title', 'Marketplace - Thèmes')
@section('page-title', '🎨 Gestion des Thème')
@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">Gérez et installez vos thèmes d'interface</p>
            </div>
            <div>
                <span class="badge bg-primary fs-6">
                    <i class="bi bi-palette me-1"></i>
                    {{ count($themes ?? []) }} thème(s) disponible(s)
                </span>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        @if(isset($error))
            <div class="alert alert-warning border-0 shadow-sm" role="alert">
                <i class="bi bi-wifi me-2"></i><strong>Erreur de connexion au marketplace:</strong> {{ $error }}
            </div>
        @endif

        {{-- Section Thème Actif --}}
        @if($activeTheme)
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h4 class="mb-1 text-white">{{ $activeTheme['name'] }}</h4>
                                    <span class="badge bg-success bg-opacity-75">
                                        <i class="bi bi-check me-1"></i>Thème actif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row g-3 text-white-50">
                                <div class="col-sm-6">
                                    <small class="d-block opacity-75">Auteur</small>
                                    <span class="text-white">{{ $activeTheme['author'] }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="d-block opacity-75">Version installée</small>
                                    <span class="badge bg-white text-primary">{{ $activeTheme['saved_version'] ?? $activeTheme['version'] }}</span>
                                    @if(isset($activeTheme['saved_version']) && $activeTheme['saved_version'] !== $activeTheme['version'])
                                        <span class="badge bg-warning text-dark ms-1">
                                            <i class="bi bi-arrow-up me-1"></i>{{ $activeTheme['version'] }} disponible
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @if(isset($activeTheme['saved_version']) && $activeTheme['saved_version'] !== $activeTheme['version'])
                                <form action="{{ route('admin.theme.download', $activeTheme['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" 
                                            onclick="return confirm('Mettre à jour vers la version {{ $activeTheme['version'] }} ?')">
                                        <i class="bi bi-download me-1"></i>Mettre à jour
                                    </button>
                                </form>
                            @endif
                            @if(isset($activeTheme['url']))
                                <a href="{{ $activeTheme['url'] }}" target="_blank" class="btn btn-outline-light ms-1">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-palette display-4 text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted mb-2">Aucun thème installé</h5>
                    <p class="text-muted mb-0">Découvrez et installez un thème depuis notre marketplace ci-dessous</p>
                </div>
            </div>
        @endif

        {{-- Section Marketplace avec Tableau --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-store text-primary me-2"></i>
                    <h5 class="mb-0">Thèmes disponibles</h5>
                </div>
            </div>
            <div class="card-body p-0">
                @if(empty($themes))
                    <div class="text-center py-5">
                        <i class="fas fa-wifi-slash fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Aucun thème disponible</h6>
                        <p class="text-muted small mb-3">Vérifiez votre connexion et votre clé API Market</p>
                        <a href="{{ route('admin.config') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-cog me-1"></i>Configuration
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Thème</th>
                                    <th class="border-0">Auteur</th>
                                    <th class="border-0">Version</th>
                                    <th class="border-0">Statut</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($themes as $theme)
                                    <tr class="{{ $activeThemeId && $theme['id'] == $activeThemeId ? 'table-success' : '' }}">
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">#{{ $theme['id'] }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div>
                                                <strong class="d-block">{{ $theme['name'] }}</strong>
                                                @if(isset($theme['url']))
                                                    <small class="text-muted">
                                                        <a href="{{ $theme['url'] }}" target="_blank" class="text-decoration-none">
                                                            <i class="fas fa-external-link-alt me-1"></i>Voir détails
                                                        </a>
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-user text-muted me-1"></i>{{ $theme['author'] }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-primary bg-opacity-10 text-primary">v{{ $theme['version'] }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($activeThemeId && $theme['id'] == $activeThemeId)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Actif
                                                </span>
                                            @elseif($theme['is_owner'])
                                                <span class="badge bg-info">
                                                    <i class="bi bi-person-check me-1"></i>Propriétaire
                                                </span>
                                            @elseif($theme['price'] == 0)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-gift me-1"></i>Gratuit
                                                </span>
                                            @elseif($theme['buy'])
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-shopping-cart me-1"></i>Acheté
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-lock me-1"></i>Non acheté
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($theme['is_owner'] || $theme['price'] == 0 || $theme['buy'])
                                                <form action="{{ route('admin.theme.download', $theme['id']) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir installer ce thème ? Le thème actuel sera remplacé.')">
                                                        <i class="fas fa-download me-1"></i>
                                                        @if($activeThemeId && $theme['id'] == $activeThemeId)
                                                            Réinstaller
                                                        @else
                                                            Télécharger
                                                        @endif
                                                    </button>
                                                </form>
                                            @elseif($theme['price'] > 0 && !$theme['buy'])
                                                <a href="{{ route('admin.theme.purchase', $theme['id']) }}" 
                                                   class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-shopping-cart me-1"></i>Acheter ({{ $theme['price'] }}€)
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-lock me-1"></i>Non disponible
                                                </button>
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

        {{-- Section Info --}}
        <div class="row mt-4">
            <div>
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>
                        <h6 class="mb-2">Achat</h6>
                        <p class="text-muted small mb-0">
                            Si vous avez acheter un theme et que vous ne le voyait pas verifier votres <a href="{{ route('admin.config') }}">MARKET API KEY</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
        }
        .table-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }
    </style>
@endsection

@section('scripts')
<script>
    // Auto-refresh de la page toutes les 60 secondes pour vérifier les nouveaux achats
    setTimeout(function() {
        if (document.hidden === false) {
            window.location.reload();
        }
    }, 60000);
</script>
@endsection
