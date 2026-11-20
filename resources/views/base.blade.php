<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'CiiLaaBokK') | CiiLaaBokK</title>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.6.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite('resources/css/app.css')
</head>
<body class="app-shell">
    <header>
        <nav class="navbar navbar-expand-lg site-navbar">
            <div class="container">
                <a href="{{ route('accueil') }}" class="navbar-brand d-inline-flex align-items-center gap-2 fw-bold">
                    <span class="site-brand-mark" aria-hidden="true">C</span>
                    <span>CiiLaaBokK</span>
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#site-navigation"
                    aria-controls="site-navigation"
                    aria-expanded="false"
                    aria-label="Ouvrir le menu"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="site-navigation">
                    <ul class="navbar-nav me-auto ms-lg-4">
                        <li class="nav-item">
                            <a
                                href="{{ route('ouvrier.index') }}"
                                @class(['nav-link', 'text-dark', 'active' => request()->routeIs('ouvrier.*')])
                                @if(request()->routeIs('ouvrier.*')) aria-current="page" @endif>
                                Annuaire
                            </a>
                        </li>
                    </ul>

                    <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 py-3 py-lg-0">
                        @auth
                            <span class="text-secondary small px-lg-2">{{ auth()->user()->name }}</span>
                            <a href="{{ route('admin') }}" class="btn btn-outline-primary">Mon espace</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none">Connexion</a>
                            <a href="{{ route('register') }}" class="btn bg-color text-white px-3">Créer un compte</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container app-main py-4 py-lg-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="status">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(request()->routeIs('accueil'))
            <section class="home-hero rounded-4 p-4 p-lg-5 mb-4">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-12 col-lg-7">
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis mb-3">LES SAVOIR-FAIRE LOCAUX</span>
                        <h1 class="display-5 fw-bold mb-3">Le bon professionnel, au bon endroit.</h1>
                        <p class="lead text-secondary mb-4">
                            Parcourez les profils d’ouvriers, découvrez leurs métiers et contactez directement les professionnels qui vous correspondent.
                        </p>

                        <form action="{{ route('ouvrier.rechercher') }}" method="get" class="home-search-form bg-white rounded-3 p-2 shadow-sm">
                            <label for="home-search" class="visually-hidden">Rechercher un nom ou un téléphone</label>
                            <div class="input-group">
                                <input
                                    type="search"
                                    name="search"
                                    id="home-search"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Nom ou numéro de téléphone"
                                >
                                <button type="submit" class="btn bg-color text-white px-4">Rechercher</button>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('ouvrier.index') }}" class="btn btn-outline-primary px-4">Explorer l’annuaire</a>
                            @auth
                                <a href="{{ route('ouvrier.create') }}" class="btn btn-link text-decoration-none">Ajouter un profil</a>
                            @endauth
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="home-feature-card bg-white rounded-4 shadow-sm p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="home-feature-icon rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20a7 7 0 0 0-14 0m7-18a10 10 0 1 0 0 20 10 10 0 0 0 0-20Z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-secondary small mb-1">Un annuaire simple</p>
                                    <h2 class="h5 fw-semibold mb-0">Des profils, des compétences, un contact</h2>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                <div class="d-flex gap-3">
                                    <span class="home-step-number">1</span>
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1">Trouvez un métier</h3>
                                        <p class="text-secondary small mb-0">Cherchez par nom, téléphone ou filtrez l’annuaire.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <span class="home-step-number">2</span>
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1">Consultez les profils</h3>
                                        <p class="text-secondary small mb-0">Découvrez les métiers et l’expérience des ouvriers.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <span class="home-step-number">3</span>
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1">Prenez contact</h3>
                                        <p class="text-secondary small mb-0">Retrouvez les coordonnées directement sur le profil.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="row g-3" aria-label="Les atouts de l’annuaire">
                <div class="col-12 col-md-4">
                    <article class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-semibold">Recherche ciblée</h2>
                            <p class="text-secondary small mb-0">Affinez les résultats par région, département, domaine et métier.</p>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-semibold">Profils détaillés</h2>
                            <p class="text-secondary small mb-0">Consultez les compétences, l’expérience et les réalisations présentées.</p>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-md-4">
                    <article class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-semibold">Contact direct</h2>
                            <p class="text-secondary small mb-0">Accédez aux coordonnées disponibles sur chaque fiche ouvrier.</p>
                        </div>
                    </article>
                </div>
            </section>
        @else
            @yield('content')
        @endif
    </main>

    <footer class="site-footer py-4 mt-auto">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <a href="{{ route('accueil') }}" class="text-dark fw-semibold text-decoration-none">CiiLaaBokK</a>
            <span class="text-secondary small">L’annuaire des savoir-faire locaux.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.6.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        if (window.TomSelect) {
            document.querySelectorAll('#diplomes, #domaines, #metiers, #metier_id, #domaine_id').forEach((select) => {
                if (!select.tomselect) {
                    new TomSelect(select, { plugins: { remove_button: { title: 'supprimer' } } });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
