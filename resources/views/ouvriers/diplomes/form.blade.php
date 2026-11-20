@extends("base")

@section("content")
    <h1 class="text-center">
        @if ($diplome)
            Modifier un diplôme
        @else
            Ajouter
        @endif
    </h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($diplome->exists ?"diplome.update" : "diplome.store", $diplome) }}" method="post">
                @csrf
                @method($diplome->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du Diplôme</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $diplome->nom }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection