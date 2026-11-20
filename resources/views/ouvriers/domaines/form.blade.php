@extends("base")

@section("content")

    <h1 class="text-center">{{ $domaine->exists? "Modifier un domaine" : "Ajouter une domaine" }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ $domaine->exists ? route('domaine.update', $domaine) : route('domaine.store') }}" method="post">
                @csrf
                @if($domaine->exists)
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du domaine</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $domaine->nom }}" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection