@extends('base')
@section('content')
<div class="col-lg-12">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"> Failed to save entry. </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger"> {{ Session::get('error') }} </div>
    @endif
    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">name</th>
        <th scope="col">brand</th>
        <th scope="col">base unit</th>
        <th scope="col">kcal</th>
        <th scope="col">protein</th>
        <th scope="col"></th>
        </tr>

        <form method="POST" action="{{ route('food-items.store') }}">
            @csrf
        <tr class="bg-success text-white">
        <th scope="col" style="vertical-align: middle;">
            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" placeholder="Nume" name="name" value="{{ old('name') }}">
        </th>
        <th scope="col" style="vertical-align: middle;">
            <input type="text" class="form-control @if ($errors->has('brand')) is-invalid @endif" placeholder="Brand" name="brand" value="{{ old('brand') }}">
        </th>
        <th scope="col" style="vertical-align: middle;">
            <div>
                <input type="number" class="form-control @if ($errors->has('unit_base_quantity')) is-invalid @endif " placeholder="Cantitate de bază" name="unit_base_quantity" value="{{ old('unit_base_quantity') }}">
                <input style="margin-top:5px;" type="text" class="form-control @if ($errors->has("unit_name")) is-invalid @endif " placeholder="Unitate de măsură" name="unit_name" value="{{ old('unit_name') }}">
            </div>
        </th>

        <th scope="col" style="vertical-align: middle;">
            <input type="number" min="0" class="form-control @if ($errors->has('kcal')) is-invalid @endif " placeholder="kcal" name="kcal" value="{{ old('kcal') }}">
        </th>

        <th scope="col" style="vertical-align: middle;">
            <input type="number" min="0" step="0.1" class="form-control @if ($errors->has('protein')) is-invalid @endif" placeholder="protein" name="protein" value="{{ old('protein') }}">
        </th>

        <th scope="col" style="vertical-align: middle; text-align: center;">
            <button type="submit" class="btn btn-primary">Adaugă</button>
        </th>
        </tr>
        </form>
    </thead>
    <tbody>
        @foreach($foodItems as $foodItem)
        <tr>
            <td>{{ $foodItem->name }}</td>
            <td>{{ $foodItem->brand }}</td>
            <td>{{ $foodItem->qtyForHumans }} </td>
            <td>{{ number_format($foodItem->kcal, 0) }}</td>
            <td>{{ number_format($foodItem->protein, 2) }}</td>
            <td class="text-center">
                @if (!$foodItem->entries->count())
                <form method="POST" action="{{ route('food-items.destroy', $foodItem->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
@endsection
