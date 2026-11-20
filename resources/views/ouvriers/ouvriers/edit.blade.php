@extends("base")

@section("content")
    <h1 class="text-center">Modifier un ouvrier</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route("ouvrier.update",  $ouvrier->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de l'ouvrier</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $ouvrier->nom }}"  required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Adresse</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ $ouvrier->address }}"  required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone 1</label>
                    <input type="text" name="telephone" id="telephone" class="form-control" value="{{ $ouvrier->telephone }}"  required>
                </div>
                <div class="mb-3">
                    <label for="telephone_2" class="form-label">Téléphone 2</label>
                    <input type="text" name="telephone_2" id="telephone_2" class="form-control" value="{{ $ouvrier->telephone_2 }}" >
                </div>
                
                <div class="mb-3">
                    <label for="domaines" class="form-label">Domaine</label>
                    <select name="domaines[]" id="domaines" class="form-control" multiple required>
                        <option value="">Sélectionnez un domaine</option>
                        @foreach($domaines as $k => $v)
                            <option value="{{ $k }}" @selected($selectedDomaines->contains($k))>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="metiers" class="form-label">Metier</label>
                    <select name="metiers[]" id="metiers" class="form-control" multiple required>
                        <option value="">Sélectionnez un metier</option>
                        @foreach($metiers as $k => $v)
                            <option value="{{ $k }}" @selected($selectedMetiers->contains($k))>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>
                </div>
                

                
                <div class="mb-3">
                    <label for="country_id" class="form-label">Pays</label>
                    <select name="country_id" id="country_id" class="form-control" required>
                        <option value="">Sélectionnez un pays</option>
                        @foreach($countries as $k => $v)
                            <option value="{{ $k }}" @selected($selectedCountry->contains($k))>
                                {{ $v }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="region_id" class="form-label">Region</label>
                    <select name="region_id" id="region_id" class="form-control" required>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $ouvrier->region_id == $region->id ? 'selected' : '' }}>
                                {{ $region->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="departement_id" class="form-label">Département</label>
                    <select name="departement_id" id="departement_id" class="form-control" required>
                        @foreach($departements as $departement)
                            <option value="{{ $departement->id }}" {{ $ouvrier->departement_id == $departement->id ? 'selected' : '' }}>
                                {{ $departement->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('ouvrier.index') }}" class="btn btn-secondary">
            Retour
        </a>
    </div>

    <script>
        document.getElementById('add-entreprise').addEventListener('click', function () {
            const row = document.createElement('div');

            row.innerHTML = `
                <div class="d-flex mb-2">
                    <input
                        type="text"
                        name="entreprises[]"
                        class="form-control me-2"
                        placeholder="Nom de l'entreprise">

                    <button
                        type="button"
                        class="btn btn-danger remove-row">
                        X
                    </button>
                </div>
            `;

            document
                .getElementById('entreprises-container')
                .appendChild(row);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.d-flex').remove();
            }
        });
        
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {

                const id = e.target.dataset.id;

                if (id) {
                    const deletedInput = document.getElementById('deleted_entreprises');

                    let deleted = deletedInput.value
                        ? deletedInput.value.split(',')
                        : [];
                    console.log(deletedInput);
                    deleted.push(id);
                    deletedInput.value = deleted.join(',');
                }
                e.target.closest('.entreprise-row').remove();
            }
        });
    </script>
@endsection