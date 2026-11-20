@extends("base")

@section("content")

    <h1 class="text-center">{{ $metier->exists? "Modifier un métier" : "Ajouter un métier" }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ $metier->exists ? route('metier.update', $metier) : route('metier.store') }}" method="post">
                @csrf
                @if($metier->exists)
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du métier</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $metier->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="domaine_id" class="form-label">Domaine</label>
                    <select name="domaine_id" id="domaine_id" class="form-control" required>
                        <option value="">Sélectionnez un domaine</option>
                        @foreach($domaines as $k => $v)
                            <option value="{{ $k }}" @selected($metier->domaine_id === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection