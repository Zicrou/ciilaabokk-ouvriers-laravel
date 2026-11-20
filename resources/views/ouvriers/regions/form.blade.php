@extends("base")

@section("content")
    <h1 class="text-center">
        @if ($region->id)
            Modifier une région
        @else
            Ajouter une région
        @endif
    </h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route($region->exists ?"region.update" : "region.store", $region) }}" method="post">
                @csrf
                @method($region->exists ? 'put' : 'post')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de la région</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->nom }}" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection