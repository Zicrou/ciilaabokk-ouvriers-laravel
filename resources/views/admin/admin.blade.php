@extends("base")

@section("content")
@php
 $ouvrierExist = \App\Models\Ouvrier::where('user_id', Auth::user()->id)->first();
@endphp
    <div class="mt-4">
        <a href="{{ route('ouvrier.index') }}" class="btn btn-secondary">
            Liste ouvriers
        </a>
        @if ($ouvrierExist)
            <a href="{{ route('ouvrier.create') }}" class="btn btn-secondary mx-5">
                Créer ouvrier
            </a>
        @else
            <a href="{{ route('ouvrier.show', $ouvrierExist) }}" class="btn btn-secondary mx-5">
                Voir mes information
            </a>
        @endif
    </div>

    @if (Auth::user()->role == "admin")
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
    @endif
@endsection