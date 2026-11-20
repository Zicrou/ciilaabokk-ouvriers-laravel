@extends("base")

@section("content")
    <h1 class="text-center">
        @if ($departement->id)
            Modifier un département
        @else
            Ajouter
        @endif
    </h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($departement->exists ?"departement.update" : "departement.store", $departement) }}" method="post">
                @csrf
                @method($departement->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du métier</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $departement->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="region_id" class="form-label">Région</label>
                    <select name="region_id" id="region_id" class="form-control" required>
                        <option value="">Choisir</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $departement->region_id == $region->id ? 'selected' : '' }}>{{ $region->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection