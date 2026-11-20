@extends('base')

@section('title', $ouvrier->nom)

@section('content')
    @php
        $isOwner = auth()->check() && auth()->id() === $ouvrier->user_id;
        $initial = strtoupper(substr($ouvrier->nom ?: 'O', 0, 1));
    @endphp

    <div class="directory-profile pb-5">
        <a href="{{ route('ouvrier.index') }}" class="link-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
            <span aria-hidden="true">&larr;</span>
            Retour à l’annuaire
        </a>

        <header class="worker-profile-header rounded-4 p-4 p-lg-5 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                <div class="worker-profile-initial rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                    {{ $initial }}
                </div>

                <div class="flex-grow-1">
                    <span class="badge rounded-pill bg-white text-primary mb-2">PROFIL D’OUVRIER</span>
                    <h1 class="h2 fw-bold mb-2">{{ $ouvrier->nom }}</h1>
                    <div class="d-flex flex-wrap gap-2 text-secondary">
                        @if($ouvrier->departement)
                            <span>{{ $ouvrier->departement->nom }}</span>
                        @endif
                        @if($ouvrier->region)
                            <span aria-hidden="true">·</span>
                            <span>{{ $ouvrier->region->nom }}</span>
                        @endif
                        @if($ouvrier->country)
                            <span aria-hidden="true">·</span>
                            <span>{{ $ouvrier->country->nom }}</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @if($ouvrier->telephone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ouvrier->telephone) }}" class="btn bg-color text-white px-4">
                            Appeler
                        </a>
                    @endif
                    @if($isOwner)
                        <a href="{{ route('ouvrier.edit', $ouvrier) }}" class="btn btn-outline-primary px-4">
                            Modifier le profil
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <section class="card h-100 border-0 shadow-sm rounded-4" aria-labelledby="skills-title">
                    <div class="card-body p-3 p-lg-4">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                            <div>
                                <span class="text-primary small fw-semibold text-uppercase">Savoir-faire</span>
                                <h2 id="skills-title" class="h4 fw-semibold mb-0">Métiers et domaines</h2>
                            </div>
                            <span class="badge rounded-pill bg-light text-dark border">
                                {{ $ouvrier->metiers->count() }} métier(s)
                            </span>
                        </div>

                        @forelse($ouvrier->metiers as $metier)
                            <article class="worker-skill-card rounded-3 p-3 mb-2">
                                <span class="text-secondary small d-block">
                                    {{ $metier->domaine?->nom ?? 'Domaine professionnel' }}
                                </span>
                                <h3 class="h6 fw-semibold mb-0">{{ $metier->nom }}</h3>
                            </article>
                        @empty
                            <div class="worker-empty-state rounded-3 p-4 text-center">
                                <h3 class="h6 fw-semibold">Aucun métier renseigné</h3>
                                <p class="text-secondary small mb-0">Les compétences de cet ouvrier seront affichées ici.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 col-lg-5">
                <div class="d-grid gap-3 h-100">
                    <section class="card border-0 shadow-sm rounded-4" aria-labelledby="contact-title">
                        <div class="card-body p-3 p-lg-4">
                            <span class="text-primary small fw-semibold text-uppercase">Prise de contact</span>
                            <h2 id="contact-title" class="h4 fw-semibold mb-4">Coordonnées</h2>

                            @if($ouvrier->telephone)
                                <div class="worker-contact-line mb-3">
                                    <span class="text-secondary small d-block">Téléphone principal</span>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ouvrier->telephone) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $ouvrier->telephone }}
                                    </a>
                                </div>
                            @endif

                            @if($ouvrier->telephone_2)
                                <div class="worker-contact-line">
                                    <span class="text-secondary small d-block">Téléphone secondaire</span>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ouvrier->telephone_2) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $ouvrier->telephone_2 }}
                                    </a>
                                </div>
                            @endif

                            @unless($ouvrier->telephone || $ouvrier->telephone_2)
                                <p class="text-secondary mb-0">Aucun numéro de téléphone renseigné.</p>
                            @endunless
                        </div>
                    </section>

                    <section class="card border-0 shadow-sm rounded-4" aria-labelledby="location-title">
                        <div class="card-body p-3 p-lg-4">
                            <span class="text-primary small fw-semibold text-uppercase">Localisation</span>
                            <h2 id="location-title" class="h4 fw-semibold mb-4">Où le trouver</h2>

                            <dl class="directory-details-list mb-0">
                                <div>
                                    <dt>Adresse</dt>
                                    <dd>{{ $ouvrier->address ?: 'Non renseignée' }}</dd>
                                </div>
                                <div>
                                    <dt>Département</dt>
                                    <dd>{{ $ouvrier->departement?->nom ?? 'Non renseigné' }}</dd>
                                </div>
                                <div>
                                    <dt>Région</dt>
                                    <dd>{{ $ouvrier->region?->nom ?? 'Non renseignée' }}</dd>
                                </div>
                                <div>
                                    <dt>Pays</dt>
                                    <dd>{{ $ouvrier->country?->nom ?? 'Non renseigné' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
