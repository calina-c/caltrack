<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Event Schedule list - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<div class="event-schedule-area-two bg-color pad100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <div class="jumbotron">
                    <h1 class="display-4">Mi-e foame</h1>
                    <p class="lead">Jurnal alimentar turbat, cu multe calorii și proteine</p>
                    <hr class="my-4">
                    <p>îmi place.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg disabled" href="#" role="button"> 🥵 Exerciții</a>
                        <a class="btn btn-secondary btn-lg" href="{{ route('food-items.index') }}" role="button">📋 Catalog</a>
                        <a class="btn btn-secondary btn-lg disabled" href="#" role="button">📆 Calendar</a>
                    </p>
                    </div>
                </div>
            </div>
            <!-- /.col end-->
        </div>
        <!-- row end-->
        <div class="row">
            <div class="col-lg-12">
                @if(request()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ request()->get('success') }}
                    </div>
                @endif
                 @if ($errors->any())
                    <div class="alert alert-danger"> Failed to save entry. </div>
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
                    <th scope="col">
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" placeholder="Nume" name="name" value="{{ old('name') }}">
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control @if ($errors->has('brand')) is-invalid @endif" placeholder="Brand" name="brand" value="{{ old('brand') }}">
                    </th>
                    <th scope="col">
                         <input type="number" class="form-control @if ($errors->has('unit_base_quantity')) is-invalid @endif " placeholder="Cantitate de bază" name="unit_base_quantity" value="{{ old('unit_base_quantity') }}">
                         <input type="text" class="form-control @if ($errors->has("unit_name")) is-invalid @endif " placeholder="Unitate de măsură" name="unit_name" value="{{ old('unit_name') }}">
                    </th>

                    <th scope="col">
                        <input type="number" min="0" class="form-control @if ($errors->has('kcal')) is-invalid @endif " placeholder="kcal" name="kcal" value="{{ old('kcal') }}">
                    </th>

                    <th scope="col">
                        <input type="number" min="0" step="0.1" class="form-control @if ($errors->has('protein')) is-invalid @endif" placeholder="protein" name="protein" value="{{ old('protein') }}">
                    </th>

                    <th scope="col">
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
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <!-- /col end-->
        </div>
        <!-- /row end-->
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>
