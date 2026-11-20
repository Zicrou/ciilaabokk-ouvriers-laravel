@extends("base")

@section("content")
    <h1 class="text-center">
        @if ($metier->id)
            Modifier un domaine
        @else
            Ajouter
        @endif
    </h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($metier->exists ?"metier.update" : "metier.store", $metier) }}" method="post">
                @csrf
                @method($metier->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du métier</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $metier->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="domaine_id" class="form-label">Domaine</label>
                    <select name="domaine_id" id="domaine_id" class="form-control" required>
                        <option value="">Choisir</option>
                        @foreach($domaines as $domaine)
                            <option value="{{ $domaine->id }}" {{ $metier->domaine_id == $domaine->id ? 'selected' : '' }}>{{ $domaine->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection