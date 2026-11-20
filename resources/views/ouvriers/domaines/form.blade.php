@extends("base")

@section("content")
    <h1 class="text-center">
        @if ($domaine)
            Modifier un domaine
        @else
            Ajouter
        @endif
    </h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($domaine->exists ?"domaine.update" : "domaine.store", $domaine) }}" method="post">
                @csrf
                @method($domaine->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du domaine</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $domaine->nom }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection