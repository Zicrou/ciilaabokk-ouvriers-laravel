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

    <div class="directory-page pb-5">
        <header class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis mb-2">ANNUAIRE</span>
                <h1 class="display-6 fw-bold mb-2">Trouvez le bon ouvrier</h1>
                <p class="text-secondary mb-0">Explorez les profils et les savoir-faire près de chez vous.</p>
            </div>

            @auth
                <a href="{{ route('ouvrier.create') }}" class="btn bg-color text-white px-4 py-2">
                    <span aria-hidden="true" class="me-1">+</span>
                    Ajouter un ouvrier
                </a>
            @endauth
        </header>

        <section class="card border-0 shadow-sm rounded-4 mb-4" aria-labelledby="search-title">
            <div class="card-body p-3 p-lg-4">
                <div class="mb-3">
                    <h2 id="search-title" class="h5 fw-semibold mb-1">Rechercher un profil</h2>
                    <p class="text-secondary small mb-0">Combinez un mot-clé avec les filtres pour affiner les résultats.</p>
                </div>

                <form action="{{ route('ouvrier.rechercher') }}" method="get">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="search" class="form-label fw-medium">Nom ou téléphone</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <path stroke-linecap="round" d="m16 16 4 4"></path>
                                    </svg>
                                </span>
                                <input
                                    type="search"
                                    name="search"
                                    id="search"
                                    class="form-control"
                                    value="{{ request('search') }}"
                                    placeholder="Ex. Amadou ou 77 000 00 00"
                                    autocomplete="off"
                                >
                                <button type="submit" class="btn bg-color text-white px-4">Rechercher</button>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="region_id" class="form-label">Région</label>
                            <select name="region_id" id="region_id" class="form-select">
                                <option value="">Toutes les régions</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>
                                        {{ $region->name }}
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
                                        {{ $departement->name }}
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
                                        {{ $domaine->name }}
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
                                        {{ $metier->name }}
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
                                                alt="Photo de {{ $ouvrier->name }}"
                                                class="directory-avatar rounded-3 object-fit-cover flex-shrink-0"
                                            >
                                        @else
                                            <div class="directory-avatar rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold fs-3">
                                                {{ strtoupper(substr($ouvrier->name ?? 'O', 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="directory-worker-info">
                                            <a href="{{ route('ouvrier.show', $ouvrier->id) }}" class="h5 fw-semibold text-dark text-decoration-none">
                                                {{ $ouvrier->name }}
                                            </a>
                                            <p class="text-secondary small mb-2">
                                                {{ $ouvrier->departement?->name ?? 'Département non renseigné' }}
                                                @if($ouvrier->region)
                                                    · {{ $ouvrier->region->name }}
                                                @endif
                                            </p>

                                            <div class="d-flex flex-wrap gap-2">
                                                @forelse($ouvrier->metiers as $metier)
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis fw-medium">
                                                        @if($metier->domaine)
                                                            {{ $metier->domaine->name }} ·
                                                        @endif
                                                        {{ $metier->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-secondary small">Aucun métier renseigné</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>

                                    <div class="directory-worker-details d-flex flex-column gap-2">
                                        @if($ouvrier->phone_number)
                                            <div>
                                                <span class="text-secondary small d-block">Téléphone</span>
                                                <a href="tel:{{ $ouvrier->phone_number }}" class="text-dark text-decoration-none fw-medium">
                                                    {{ $ouvrier->phone_number }}
                                                </a>
                                                @if($ouvrier->phone_number_2)
                                                    <span class="text-secondary small"> · {{ $ouvrier->phone_number_2 }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($ouvrier->annees_experience)
                                            <div>
                                                <span class="text-secondary small d-block">Expérience</span>
                                                <span class="fw-medium">{{ $ouvrier->annees_experience }} ans</span>
                                            </div>
                                        @endif

                                        @if($ouvrier->entrepriseS->isNotEmpty())
                                            <div>
                                                <span class="text-secondary small d-block">Entreprise(s)</span>
                                                <span class="fw-medium">{{ $ouvrier->entrepriseS->pluck('name')->join(', ') }}</span>
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
@endsection
