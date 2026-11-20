@extends("base")

@section("content")

    <h1 class="text-center">{{ $region->exists? "Modifier une région" : "Ajouter une région" }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ $region->exists ? route('region.update', $region) : route('region.store') }}" method="post">
                @csrf
                @if($region->exists)
                    @method('PUT')
                @endif
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de la région</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="country_id" class="form-label">Pays</label>
                    <select name="country_id" id="country_id" class="form-control" required>
                        <option value="">Sélectionnez un pays</option>
                        @foreach($countries as $k => $v)
                            <option value="{{ $k }}" @selected($region->country_id === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection