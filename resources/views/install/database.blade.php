<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Configuration Base de Données</title>
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
        .alert {
            border-radius: 8px;
        }
        #database-config {
            display: none;
        }
        .progress {
            height: 8px;
            border-radius: 4px;
            margin-bottom: 2rem;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="install-header">
                <i class="fas fa-database"></i>
                <h2>Configuration de la Base de Données</h2>
                <p class="text-muted">Configurez votre base de données pour CentralCorp Panel</p>
            </div>

            <div class="step-indicator">
                <div class="step active">
                    <div class="step-circle">1</div>
                    <span>Base de données</span>
                </div>
                <div class="step-line"></div>
                <div class="step">
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

            <form method="POST" action="{{ route('install.database.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="type" class="form-label">Type de base de données</label>
                    <select class="form-control" id="type" name="type" required onchange="toggleDatabaseConfig()">
                        <option value="">Sélectionnez un type</option>
                        @foreach($databaseDrivers as $key => $name)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="database-config">
                    <div class="mb-3">
                        <label for="host" class="form-label">Hôte</label>
                        <input type="text" class="form-control" id="host" name="host" value="{{ old('host', 'localhost') }}" placeholder="localhost">
                    </div>

                    <div class="mb-3">
                        <label for="port" class="form-label">Port</label>
                        <input type="number" class="form-control" id="port" name="port" value="{{ old('port') }}" placeholder="3306">
                    </div>

                    <div class="mb-3">
                        <label for="database" class="form-label">Nom de la base de données</label>
                        <input type="text" class="form-control" id="database" name="database" value="{{ old('database') }}" placeholder="centralcorp_panel">
                    </div>

                    <div class="mb-3">
                        <label for="user" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="user" name="user" value="{{ old('user') }}" placeholder="root">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide si aucun mot de passe">
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continuer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDatabaseConfig() {
            const type = document.getElementById('type').value;
            const config = document.getElementById('database-config');
            
            if (type === 'sqlite') {
                config.style.display = 'none';
            } else if (type) {
                config.style.display = 'block';
            } else {
                config.style.display = 'none';
            }
        }

        // Initialize on page load
        toggleDatabaseConfig();
    </script>
</body>
</html>
