@extends("base")

@section("content")

    <h1 class="text-center">{{ $country->exists? "Modifier un pays" : "Ajouter un pays" }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($country->exist ? "country.update" : "country.store",  ['country' => $country->id]) }}" method="post">
                @csrf
                @method($country->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du pays</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $country->nom }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection