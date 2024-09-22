@extends('layouts.admin')

@section('content')
    <div class="container">
        <!---https://www.youtube.com/watch?v=dQw4w9WgXcQ--->
        <!--Inzio del FORM-->
        <form class="d-flex justify-content-center flex-column" action="{{ route('admin.chefs.update', $chef) }}"
            method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @error('address')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            <label for="address">
                <strong>
                    Indirizzo
                </strong>
            </label>
            <input type="text" value="{{ old('address', $chef->address) }}
        " name="address"
                class="form-control mb-3">
                @error('telephone')
                    <div class="alert alert-danger mb-3">
                        {{ $message }}
                    </div>
                @enderror
            <label for="telephone">
                <strong>
                    Numero di telefono
                </strong>
            </label>
            <input type="text" value="{{ old('telephone', $chef->telephone) }}
        " name="telephone"
                class="form-control mb-3">
                @error('description_of_dishes')
                    <div class="alert alert-danger mb-3">
                        {{ $message }}
                    </div>
                @enderror
            <label for="description_of_dishes">
                <strong>
                    Descrizione dei piatti
                </strong>
            </label>
            <textarea class="form-control mb-3" placeholder="Description of dishes" name="description_of_dishes" id="description_of_dishes" rows="5" cols="33">{{ old('description_of_dishes', $chef->description_of_dishes) }}</textarea>

            <!--Input file--->
            @error('photograph')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            <label for="photograph">Carica foto profilo</label>
            <input class="mb-3 form-control" type="file" name="photograph" id="photograph">
            @error('file')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            <label for="file">Carica CV</label>
            <input class="mb-3 form-control" type="file" name="CV" id="CV">

            <!--Select della visibilità-->
            @error('visibility')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            <label for="visibility">
                <strong>
                    Seleziona visibilità
                </strong>
            </label>
            <select class="form-select mb-3" aria-label="Default select example" name="visibility" id="visibility">
                <option value="0"{{ old('visibility', $chef->visibility == 0 ? 'selected' : '') }}>
                    Not visible
                </option>
                <option value="1"{{ old('visibility', $chef->visibility == 1 ? 'selected' : '') }}>
                    Visible
                </option>
            </select>

            <!--Checkbox-->
            @error('specializations')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            <label for="specializations">
                <strong>
                    Seleziona la specializzazione
                </strong>
            </label>
            <div class="d-flex flex-wrap" role="group" aria-label="Basic checkbox toggle button group">
                @foreach ($specializations as $specialization)
                    <input type="checkbox" name="specializations[]" value="{{ $specialization->id }}" class="btn-check"
                        id="specialization-check-{{ $specialization->id }}" autocomplete="off"
                        {{ in_array($specialization->id, old('specializations',  $chef->specializations->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary m-1"
                        for="specialization-check-{{ $specialization->id }}">
                        {{ $specialization->name }}
                    </label>
                @endforeach
            </div>

            <!--Button submit-->
            <div class="mt-4">
                <input type="submit" class="btn btn-info">
            </div>
        </form>

    </div>
@endsection
