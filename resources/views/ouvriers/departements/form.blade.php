@extends("base")

@section("content")

    <h1 class="text-center">{{ $departement->exists? "Modifier une région" : "Ajouter une région" }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ $departement->exists ? route('departement.update', $departement) : route('departement.store') }}" method="post">
                @csrf
                @if($departement->exists)
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du département</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $departement->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="region_id" class="form-label">Région</label>
                    <select name="region_id" id="region_id" class="form-control" required>
                        <option value="">Sélectionnez une région</option>
                        @foreach($regions as $k => $v)
                            <option value="{{ $k }}" @selected($departement->region_id === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection