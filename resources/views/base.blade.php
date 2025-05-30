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
                        <a class="btn btn-primary btn-lg" href="{{ route('food-entries.index') }}" role="button"> 🍗 Jurnal</a>
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
            @yield('content')
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
