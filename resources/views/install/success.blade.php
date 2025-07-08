<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Terminée !</title>
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
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #1cc88a;
            margin-bottom: 1.5rem;
            animation: bounce 1s ease-in-out;
        }
        @keyframes bounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            80% {
                transform: translateY(-10px);
            }
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
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .step.completed .step-circle {
            background: #1cc88a;
            color: white;
        }
        .step-line {
            height: 2px;
            background: #1cc88a;
            flex: 1;
            margin: 20px 10px 0 10px;
        }
        .feature-list {
            text-align: left;
            margin: 2rem 0;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8f9fc;
            border-radius: 8px;
        }
        .feature-icon {
            width: 40px;
            height: 40px;
            background: #4e73df;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="step-indicator">
                <div class="step completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <span>Base de données</span>
                </div>
                <div class="step-line"></div>
                <div class="step completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <span>Administrateur</span>
                </div>
                <div class="step-line"></div>
                <div class="step completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <span>Finalisation</span>
                </div>
            </div>

            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <h1 class="h2 mb-4">Installation Terminée !</h1>
            <p class="text-muted mb-4">
                Félicitations ! CentralCorp Panel a été installé avec succès sur votre serveur.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <strong>Base de données configurée</strong><br>
                        <small class="text-muted">Votre base de données a été créée et initialisée</small>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <strong>Compte administrateur créé</strong><br>
                        <small class="text-muted">Votre compte admin est prêt à l'emploi</small>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div>
                        <strong>Configuration optimisée</strong><br>
                        <small class="text-muted">Toutes les configurations ont été appliquées</small>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div>
                        <strong>Prêt à utiliser</strong><br>
                        <small class="text-muted">Votre panel est maintenant opérationnel</small>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-grid gap-2">
                <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Accéder au Panel
                </a>
            </div>

            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Pour des raisons de sécurité, assurez-vous de supprimer les fichiers d'installation si nécessaire.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-redirection après 10 secondes (optionnel)
        // setTimeout(() => {
        //     window.location.href = '{{ url('/') }}';
        // }, 10000);
    </script>
</body>
</html>
