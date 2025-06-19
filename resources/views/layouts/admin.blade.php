<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

    <script>
        (function() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', currentTheme);
            if (currentTheme === 'dark') {
                document.write('<style>#file-manager-css{display:none;} #file-manager-dark-css{display:block;}</style>');
            } else {
                document.write('<style>#file-manager-css{display:block;} #file-manager-dark-css{display:none;}</style>');
            }
        })();
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/admin.css') }}">
    <link id="file-manager-css" rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <link id="file-manager-dark-css" rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager-dark.css') }}" disabled>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>

<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand text-white" href="{{ route('admin.index') }}">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="sidebar-brand-img">
            </a>
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                        Panel
                    </li>
                    <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="bi bi-people align-middle"></i> <span class="align-middle">Users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.config') ? 'active' : '' }}" href="{{ route('admin.config') }}">
                        <i class="bi bi-gear align-middle"></i> <span class="align-middle">Config</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.file-manager') ? 'active' : '' }}" href="{{ route('admin.file-manager') }}">
                        <i class="bi bi-folder align-middle"></i> <span class="align-middle">File Manager</span>
                    </a>
                </li>
                <li class="sidebar-header">
                    Configuration
                </li>
                
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.general') ? 'active' : '' }}" href="{{ route('admin.general') }}">
                        <i class="bi bi-sliders align-middle"></i> <span class="align-middle">General</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.rpc') ? 'active' : '' }}" href="{{ route('admin.rpc') }}">
                        <i class="bi bi-cpu align-middle"></i> <span class="align-middle">RPC</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.server') ? 'active' : '' }}" href="{{ route('admin.server') }}">
                        <i class="bi bi-hdd align-middle"></i> <span class="align-middle">Server</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Sécurité
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.security') ? 'active' : '' }}" href="{{ route('admin.security') }}">
                        <i class="bi bi-lock align-middle"></i> <span class="align-middle">Security</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.whitelist') ? 'active' : '' }}" href="{{ route('admin.whitelist') }}">
                        <i class="bi bi-list-check align-middle"></i> <span class="align-middle">Whitelist</span>
                    </a>
                </li>
                

                <li class="sidebar-header">
                    Client
                </li>
                
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.mods') ? 'active' : '' }}" href="{{ route('admin.mods') }}">
                        <i class="bi bi-box align-middle"></i> <span class="align-middle">Mods optionnels</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.loader') ? 'active' : '' }}" href="{{ route('admin.loader') }}">
                        <i class="bi bi-cloud-arrow-down"></i> <span class="align-middle">Loader</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.ignore') ? 'active' : '' }}" href="{{ route('admin.ignore') }}">
                        <i class="bi bi-slash align-middle"></i> <span class="align-middle">Ignore</span>
                    </a>
                </li>

                <li class="sidebar-header">
                    Interface
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.ui') ? 'active' : '' }}" href="{{ route('admin.ui') }}">
                        <i class="bi bi-display align-middle"></i> <span class="align-middle">UI</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.bg') ? 'active' : '' }}" href="{{ route('admin.bg') }}">
                        <i class="bi bi-image align-middle"></i> <span class="align-middle">Background</span>
                    </a>
                </li>
            </ul>

        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand bg-body-secondary navbar-bgw">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>
            <div class="d-none d-sm-inline-block" bis_skin_checked="1">
                <a href="https://discord.gg/VCmNXHvf77" class="btn btn-outline-primary mx-1" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-discord"></i>
                    Discord
                </a>

                <a href="https://centralcorp.github.io/" class="btn btn-outline-secondary mx-1" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-journals"></i>
                    Documentation
                </a>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <button class="btn btn-outline-secondary" id="themeToggle" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Thème sombre" data-bs-original-title="Thème sombre">
                    <i id="themeIcon" class="bi bi-moon-stars"></i>
                </button>
                <div class="dropdown ms-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Déconnexion') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content">
            <div class="container-fluid">
                <h1 class="mt-4">@yield('page-title')</h1>
                @yield('content')
            </div>
        </main>
        <footer class="bg-body-secondary py-4 mt-4">
                <div class="text-center">
                    <p class="mb-0">© 2025 CentralCorp. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('assets/vendor/admin.js') }}"></script>
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        const fileManagerCssLink = document.getElementById('file-manager-css');
        const fileManagerDarkCssLink = document.getElementById('file-manager-dark-css');
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const currentTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-bs-theme', currentTheme);
        updateFileManagerCss(currentTheme);  // Mettre à jour le CSS du gestionnaire de fichiers

        function updateTooltipAndIcon() {
            const isDarkMode = htmlElement.getAttribute('data-bs-theme') === 'dark';
            if (isDarkMode) {
                themeIcon.classList.replace('bi-moon-stars', 'bi-sun');
                themeToggle.setAttribute('data-bs-original-title', 'Thème clair');
            } else {
                themeIcon.classList.replace('bi-sun', 'bi-moon-stars');
                themeToggle.setAttribute('data-bs-original-title', 'Thème sombre');
            }
            bootstrap.Tooltip.getInstance(themeToggle).setContent({ '.tooltip-inner': themeToggle.getAttribute('data-bs-original-title') });
        }

        function updateFileManagerCss(theme) {
            if (theme === 'dark') {
                fileManagerCssLink.disabled = true;  // Désactive le CSS clair
                fileManagerDarkCssLink.disabled = false;  // Active le CSS sombre
            } else {
                fileManagerCssLink.disabled = false;  // Active le CSS clair
                fileManagerDarkCssLink.disabled = true;  // Désactive le CSS sombre
            }
        }

        updateTooltipAndIcon();

        themeToggle.addEventListener('click', function() {
            const isDarkMode = htmlElement.getAttribute('data-bs-theme') === 'dark';

            if (isDarkMode) {
                htmlElement.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            updateTooltipAndIcon();
            updateFileManagerCss(htmlElement.getAttribute('data-bs-theme'));
            bootstrap.Tooltip.getInstance(themeToggle).hide();
        });
    });
</script>
</body>
</html>
