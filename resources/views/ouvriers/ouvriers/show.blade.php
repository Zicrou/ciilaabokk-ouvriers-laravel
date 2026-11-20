@extends('base')

@section('title', $ouvrier->name)

@section('content')
    @php
        $isOwner = auth()->check() && auth()->id() === $ouvrier->user_id;
    @endphp

    <div class="directory-profile pb-5">
        <a href="{{ route('ouvrier.index') }}" class="link-secondary text-decoration-none d-inline-flex align-items-center gap-2 mb-3">
            <span aria-hidden="true">&larr;</span>
            Retour à l’annuaire
        </a>

        <section class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3 p-md-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                    @if($ouvrier->photo)
                        <img
                            src="{{ asset('storage/' . $ouvrier->photo) }}"
                            alt="Photo de {{ $ouvrier->name }}"
                            class="directory-profile-avatar rounded-4 object-fit-cover flex-shrink-0"
                        >
                    @else
                        <div class="directory-profile-avatar rounded-4 d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold display-5 flex-shrink-0">
                            {{ strtoupper(substr($ouvrier->name ?? 'O', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis mb-2">PROFIL PROFESSIONNEL</span>
                        <h1 class="h2 fw-bold mb-2">{{ $ouvrier->name }}</h1>
                        <p class="text-secondary mb-3">
                            {{ $ouvrier->departement?->name ?? 'Département non renseigné' }}
                            @if($ouvrier->region)
                                · {{ $ouvrier->region->name }}
                            @endif
                            @if($ouvrier->country)
                                · {{ $ouvrier->country->name }}
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

                    <div class="d-flex flex-wrap gap-2">
                        @if($ouvrier->phone_number)
                            <a href="tel:{{ $ouvrier->phone_number }}" class="btn bg-color text-white px-4">
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
            </div>
        </section>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <section class="card h-100 border-0 shadow-sm rounded-4" aria-labelledby="contact-title">
                    <div class="card-body p-3 p-lg-4">
                        <h2 id="contact-title" class="h5 fw-semibold mb-4">Contact et localisation</h2>

                        <dl class="directory-details-list mb-0">
                            @if($ouvrier->phone_number)
                                <div>
                                    <dt>Téléphone principal</dt>
                                    <dd><a href="tel:{{ $ouvrier->phone_number }}">{{ $ouvrier->phone_number }}</a></dd>
                                </div>
                            @endif
                            @if($ouvrier->phone_number_2)
                                <div>
                                    <dt>Téléphone secondaire</dt>
                                    <dd><a href="tel:{{ $ouvrier->phone_number_2 }}">{{ $ouvrier->phone_number_2 }}</a></dd>
                                </div>
                            @endif
                            @if($ouvrier->email)
                                <div>
                                    <dt>Email</dt>
                                    <dd><a href="mailto:{{ $ouvrier->email }}">{{ $ouvrier->email }}</a></dd>
                                </div>
                            @endif
                            @if($ouvrier->address)
                                <div>
                                    <dt>Adresse</dt>
                                    <dd>{{ $ouvrier->address }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt>Département</dt>
                                <dd>{{ $ouvrier->departement?->name ?? 'Non renseigné' }}</dd>
                            </div>
                            <div>
                                <dt>Région</dt>
                                <dd>{{ $ouvrier->region?->name ?? 'Non renseignée' }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>
            </div>

            <div class="col-12 col-lg-6">
                <section class="card h-100 border-0 shadow-sm rounded-4" aria-labelledby="experience-title">
                    <div class="card-body p-3 p-lg-4">
                        <h2 id="experience-title" class="h5 fw-semibold mb-4">Expérience et entreprises</h2>

                        <div class="directory-experience-highlight rounded-3 bg-light p-3 mb-4">
                            <span class="text-secondary small d-block">Expérience professionnelle</span>
                            <strong class="fs-4">
                                @if($ouvrier->annees_experience)
                                    {{ $ouvrier->annees_experience }} ans
                                @else
                                    Non renseignée
                                @endif
                            </strong>
                        </div>

                        <h3 class="small text-secondary text-uppercase fw-semibold">Entreprises</h3>
                        @forelse($ouvrier->entrepriseS as $entreprise)
                            <span class="badge rounded-pill bg-light text-dark border me-1 mb-2">{{ $entreprise->name }}</span>
                        @empty
                            <p class="text-secondary mb-0">Aucune entreprise renseignée.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        @if($isOwner)
            <section class="card border-0 shadow-sm rounded-4 mt-3" aria-labelledby="portfolio-title">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                        <div>
                            <h2 id="portfolio-title" class="h5 fw-semibold mb-1">Portfolio</h2>
                            <p class="text-secondary small mb-0">Quelques exemples de réalisations.</p>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark border">
                            {{ $ouvrier->portfolios->count() }} image(s)
                        </span>
                    </div>

                    @if($ouvrier->portfolios->isEmpty())
                        <p class="text-secondary mb-0">Aucune image dans le portfolio pour le moment.</p>
                    @else
                        <div class="row g-3">
                            @foreach($ouvrier->portfolios as $portfolio)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <img
                                        src="{{ asset('storage/' . $portfolio->image) }}"
                                        alt="Réalisation de {{ $ouvrier->name }}"
                                        class="directory-portfolio-image rounded-3 w-100"
                                        loading="lazy"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>
@endsection
