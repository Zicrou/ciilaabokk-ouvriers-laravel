@extends("base")

@section("content")
    <h1 class="text-center">Ajouter un ouvrier</h1>
    <div class="mt-4">
        <a href="{{ route('ouvrier.index') }}" class="btn btn-secondary">
            Liste ouvriers
        </a>
        <a href="{{ route('ouvrier.create') }}" class="btn btn-secondary mx-5">
            Créer ouvrier
        </a>
    </div>

    <div class="mt-4">
        <a href="{{ route('domaine.index') }}" class="btn btn-secondary">
            Domaines
        </a>
    </div>
    

    <div class="mt-4">
        <a href="{{ route('metier.index') }}" class="btn btn-secondary">
            Metiers
        </a>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('country.index') }}" class="btn btn-secondary">
            Country
        </a>
    </div>

    <div class="mt-4">
        <a href="{{ route('region.index') }}" class="btn btn-secondary">
            Region
        </a>
    </div>

    <div class="mt-4">
        <a href="{{ route('departement.index') }}" class="btn btn-secondary">
            Departement
        </a>
    </div>
    

    <div class="mt-4">
        <a href="{{ route('diplome.index') }}" class="btn btn-secondary">
            Diplome
        </a>
    </div>
@endsection