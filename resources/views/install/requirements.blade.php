<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Vérification des Pré-requis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .install-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }
        .install-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .install-header i {
            font-size: 3rem;
            color: #4e73df;
            margin-bottom: 1rem;
        }
        .requirement-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
        }
        .requirement-item.success {
            background: #d4edda;
            border-color: #c3e6cb;
        }
        .requirement-item.error {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        .requirement-name {
            font-weight: 500;
        }
        .requirement-status {
            font-size: 1.25rem;
        }
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #224abe 0%, #1e3a8a 100%);
        }
        .btn-warning {
            background: #f6c23e;
            border: none;
            color: #1a1a1a;
        }
        .php-version {
            font-weight: bold;
            color: #4e73df;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-header">
                <i class="fas fa-tasks"></i>
                <h2>Vérification des Pré-requis</h2>
                <p class="text-muted">Vérification de la compatibilité de votre serveur</p>
            </div>

            <div class="mb-4">
                <h5>Version PHP</h5>
                <div class="requirement-item {{ $requirements['php'] ? 'success' : 'error' }}">
                    <span class="requirement-name">
                        PHP {{ \App\Http\Controllers\InstallController::MIN_PHP_VERSION }}+ 
                        <span class="php-version">(actuel: {{ \App\Http\Controllers\InstallController::parsePhpVersion() }})</span>
                    </span>
                    <span class="requirement-status">
                        @if($requirements['php'])
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <h5>Permissions</h5>
                <div class="requirement-item {{ $requirements['writable'] ? 'success' : 'error' }}">
                    <span class="requirement-name">Dossier racine en écriture</span>
                    <span class="requirement-status">
                        @if($requirements['writable'])
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </span>
                </div>

                <div class="requirement-item {{ $requirements['storage-writable'] ? 'success' : 'error' }}">
                    <span class="requirement-name">Dossier storage en écriture</span>
                    <span class="requirement-status">
                        @if($requirements['storage-writable'])
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </span>
                </div>

                <div class="requirement-item {{ $requirements['bootstrap-writable'] ? 'success' : 'error' }}">
                    <span class="requirement-name">Dossier bootstrap/cache en écriture</span>
                    <span class="requirement-status">
                        @if($requirements['bootstrap-writable'])
                            <i class="fas fa-check-circle text-success"></i>
                        @else
                            <i class="fas fa-times-circle text-danger"></i>
                        @endif
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <h5>Extensions PHP Requises</h5>
                @foreach(\App\Http\Controllers\InstallController::REQUIRED_EXTENSIONS as $extension)
                    <div class="requirement-item {{ $requirements['extension-' . $extension] ? 'success' : 'error' }}">
                        <span class="requirement-name">{{ $extension }}</span>
                        <span class="requirement-status">
                            @if($requirements['extension-' . $extension])
                                <i class="fas fa-check-circle text-success"></i>
                            @else
                                <i class="fas fa-times-circle text-danger"></i>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>

            @php
                $allRequirementsMet = !in_array(false, $requirements, true);
            @endphp

            @if($allRequirementsMet)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Tous les pré-requis sont satisfaits ! Vous pouvez procéder à l'installation.
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('install.database') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continuer l'Installation
                    </a>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Certains pré-requis ne sont pas satisfaits. Veuillez les corriger avant de continuer.
                </div>
                
                <div class="d-grid">
                    <button type="button" class="btn btn-warning" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>
                        Vérifier à Nouveau
                    </button>
                </div>
            @endif

            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Si vous rencontrez des problèmes, consultez la documentation de votre hébergeur pour activer les extensions PHP manquantes.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
