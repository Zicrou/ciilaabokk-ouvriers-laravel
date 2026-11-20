@extends("base")

@section("content")
    <span class="h1 text-center">Diplôme</span> <a href="{{ route("diplome.create") }}" class="h1 btn btn-primary">Ajouter</a>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diplomes as $diplome)
                        <tr>
                            <td>{{ $diplome->nom }}</td> 
                            <td class="text-end">
                                <a href="{{ route("diplome.edit", $diplome->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route("diplome.destroy", $diplome->id) }}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>      
                        </tr>
                        <tr>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection