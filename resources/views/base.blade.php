<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Event Schedule list - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://bootswatch.com/5/brite/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap4.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <div class="event-schedule-area-two bg-color pad100">

        <nav class="navbar navbar-expand-lg mainnav" style="background-color: rgb(104, 211, 145);">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('food-entries.index') }}" role="button"> 🍗 Jurnal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('food-items.index') }}" role="button"> 📋 Catalog</a>
                    </li>
                    <li class="nav-item disabled">
                        <a class="nav-link" href="#" role="button"> 🏋️‍♀️ Exerciții</a>
                    </li>
                    <li class="nav-item disabled">
                        <a class="nav-link" href="#" role="button"> 📆 Calendar</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <!-- row end-->
            <div class="row">
                @yield('content')
            </div>
            <!-- /row end-->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>


    <script type="text/javascript">
        @yield('scripts')
    </script>
</body>

</html>
