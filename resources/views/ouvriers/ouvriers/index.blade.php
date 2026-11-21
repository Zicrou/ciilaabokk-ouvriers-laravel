@extends('base')

@section('title', 'Annuaire des ouvriers')

@section('content')
    @php
        $activeFilters = collect([
            request('search'),
            request('region_id'),
            request('departement_id'),
            request('domaine_id'),
            request('metier_id'),
        ])->filter(fn ($value) => is_string($value) && $value !== '');
    @endphp
    // hero section
    <section class="hero px-4 py-5 px-lg-5" aria-labelledby="directory-title">
        <img
            src="{{ asset('images/image_98761e53.jpg') }}"
            alt="Plusieurs ouvriers réunis sur un chantier"
            class="hero-image"
            width="1376"
            height="768"
        >
        <div class="hero-content">
            <span class="badge rounded-pill bg-white text-primary fw-semibold mb-3">ANNUAIRE DES SAVOIR-FAIRE</span>
            <h1 id="directory-title" class="display-4 fw-bold mb-3">Trouvez le bon ouvrier</h1>
            <p class="lead mb-4">Explorez les profils et les savoir-faire près de chez vous.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="#search-title" class="btn bg-color text-blue fw-semibold text-nowrap px-3 px-sm-4 py-2">
                    Rechercher un ouvrier
                </a>
                @auth
                    <a href="{{ route('ouvrier.create') }}" class="btn btn-outline-primary px-4 py-2">
                        Ajouter un ouvrier
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <div class="directory-page pb-5">

        <section class="card border-0 shadow-sm rounded-4 mb-4" aria-labelledby="search-title">
            <div class="card-body p-3 p-lg-4">
                <div class="mb-3">
                    <h2 id="search-title" class="h5 fw-semibold mb-1">Rechercher un profil</h2>
                    <p class="text-secondary small mb-0">Combinez un mot-clé avec les filtres pour affiner les résultats.</p>
                </div>

                <form action="{{ route('ouvrier.rechercher') }}" method="get">
                    <div class="row g-3">

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="region_id" class="form-label">Région</label>
                            <select name="region_id" id="region_id" class="form-select">
                                <option value="">Toutes les régions</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>
                                        {{ $region->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="departement_id" class="form-label">Département</label>
                            <select name="departement_id" id="departement_id" class="form-select">
                                <option value="">Tous les départements</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->id }}" @selected(request('departement_id') == $departement->id)>
                                        {{ $departement->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="domaine_id" class="form-label">Domaine</label>
                            <select name="domaine_id" id="domaine_id" class="form-select">
                                <option value="">Tous les domaines</option>
                                @foreach($domaines as $domaine)
                                    <option value="{{ $domaine->id }}" @selected(request('domaine_id') == $domaine->id)>
                                        {{ $domaine->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="metier_id" class="form-label">Métier</label>
                            <select name="metier_id" id="metier_id" class="form-select">
                                <option value="">Tous les métiers</option>
                                @foreach($metiers as $metier)
                                    <option value="{{ $metier->id }}" @selected(request('metier_id') == $metier->id)>
                                        {{ $metier->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mt-4">
                        <span class="text-secondary small">
                            @if($activeFilters->isNotEmpty())
                                {{ $activeFilters->count() }} filtre(s) actif(s)
                            @else
                                Recherchez par nom, téléphone, métier ou localisation.
                            @endif
                        </span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ouvrier.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                            <button type="submit" class="btn bg-color text-white px-4">Appliquer les filtres</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <div>
                <h2 class="h5 fw-semibold mb-1">Les ouvriers</h2>
                <p class="text-secondary small mb-0">Des professionnels et leurs compétences.</p>
            </div>
            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                {{ $ouvriers->count() }} résultat(s)
            </span>
        </div>

        @if($ouvriers->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5 px-4">
                    <div class="directory-empty-icon mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path stroke-linecap="round" d="m16 16 4 4"></path>
                        </svg>
                    </div>
                    <h3 class="h5 fw-semibold">Aucun ouvrier trouvé</h3>
                    <p class="text-secondary mb-4">Essayez d’élargir votre recherche ou de retirer certains filtres.</p>
                    <a href="{{ route('ouvrier.index') }}" class="btn btn-outline-primary">Effacer les filtres</a>
                </div>
            </div>
        @else
            <div class="row g-3">
                @foreach($ouvriers as $ouvrier)
                    <div class="col-12">
                        <article class="card directory-worker-card border-0 shadow-sm rounded-4">
                            <div class="card-body p-3 p-lg-4">
                                <div class="d-flex flex-column flex-lg-row gap-3 gap-lg-4">
                                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                                        @if($ouvrier->photo)
                                            <img
                                                src="{{ asset('storage/' . $ouvrier->photo) }}"
                                                alt="Photo de {{ $ouvrier->nom }}"
                                                class="directory-avatar rounded-3 object-fit-cover flex-shrink-0"
                                            >
                                        @else
                                            <div class="directory-avatar rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold fs-3">
                                                {{ strtoupper(substr($ouvrier->nom ?? 'O', 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="directory-worker-info">
                                            <a href="{{ route('ouvrier.show', $ouvrier->id) }}" class="h5 fw-semibold text-dark text-decoration-none">
                                                {{ $ouvrier->nom }}
                                            </a>
                                            <p class="text-secondary small mb-2">
                                                {{ $ouvrier->departement?->nom ?? 'Département non renseigné' }}
                                                @if($ouvrier->region)
                                                    · {{ $ouvrier->region->nom }}
                                                @endif
                                            </p>

                                            <div class="d-flex flex-wrap gap-2">
                                                @forelse($ouvrier->metiers as $metier)
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis fw-medium">
                                                        @if($metier->domaine)
                                                            {{ $metier->domaine->nom }} ·
                                                        @endif
                                                        {{ $metier->nom }}
                                                    </span>
                                                @empty
                                                    <span class="text-secondary small">Aucun métier renseigné</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>

                                    <div class="directory-worker-details d-flex flex-column gap-2">
                                        @if($ouvrier->telephone)
                                            <div>
                                                <span class="text-secondary small d-block">Téléphone</span>
                                                <a href="tel:{{ $ouvrier->telephone }}" class="text-dark text-decoration-none fw-medium">
                                                    {{ $ouvrier->telephone }}
                                                </a>
                                                @if($ouvrier->telephone_2)
                                                    <span class="text-secondary small"> · {{ $ouvrier->telephone_2 }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        
                                    </div>

                                    <div class="d-flex flex-row flex-lg-column gap-2 align-items-stretch justify-content-lg-center">
                                        <a href="{{ route('ouvrier.show', $ouvrier->id) }}" class="btn btn-outline-primary text-nowrap">
                                            Voir le profil
                                        </a>
                                        @auth
                                            @if(auth()->id() === $ouvrier->user_id)
                                                <a href="{{ route('ouvrier.edit', $ouvrier) }}" class="btn btn-outline-secondary">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('ouvrier.destroy', $ouvrier) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-danger w-100"
                                                        onclick="return confirm('Supprimer ce profil ?')"
                                                    >
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="my-4">
        {{ $ouvriers->links() }}
    </div>
@endsection
