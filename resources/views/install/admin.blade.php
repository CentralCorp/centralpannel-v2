<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Compte Administrateur</title>
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
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e3e6f0;
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
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .alert {
            border-radius: 8px;
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
        .step.active .step-circle {
            background: #4e73df;
            color: white;
        }
        .step.completed .step-circle {
            background: #1cc88a;
            color: white;
        }
        .step:not(.active):not(.completed) .step-circle {
            background: #e3e6f0;
            color: #5a5c69;
        }
        .step-line {
            height: 2px;
            background: #e3e6f0;
            flex: 1;
            margin: 20px 10px 0 10px;
        }
        .step.completed + .step .step-line {
            background: #1cc88a;
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-header">
                <i class="fas fa-user-shield"></i>
                <h2>Compte Administrateur</h2>
                <p class="text-muted">Créez votre compte administrateur pour CentralCorp Panel</p>
            </div>

            <div class="step-indicator">
                <div class="step completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <span>Base de données</span>
                </div>
                <div class="step-line"></div>
                <div class="step active">
                    <div class="step-circle">2</div>
                    <span>Administrateur</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <span>Finalisation</span>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('install.admin.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom d'utilisateur</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Votre nom d'utilisateur">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="votre@email.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Mot de passe sécurisé">
                    <div class="password-requirements">
                        <ul>
                            <li>Au moins 8 caractères</li>
                            <li>Mélange de lettres, chiffres et symboles recommandé</li>
                        </ul>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           placeholder="Répétez votre mot de passe">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-rocket me-2"></i>
                        Installer CentralCorp Panel
                    </button>
                    <a href="{{ route('install.database') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation du mot de passe en temps réel
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (password && confirmation && password !== confirmation) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
