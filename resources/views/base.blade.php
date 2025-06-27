<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Caltrack</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://bootswatch.com/5/brite/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap4.min.css">
    <link href="{{ asset('fontawesome-free-6.7.2-web/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.7.2-web/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <nav class="navbar mainnav navbar-expand-lg navbar-light" style="background-color: rgb(104, 211, 145);">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @if (Auth::user())
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('food-entries.index') }}" role="button"> 🍗 Jurnal</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ route('food-items.index') }}" role="button"> 📋 Catalog</a>
            </li>
            @if (Auth::user())
            <li class="nav-item disabled">
                <a class="nav-link" href="#" role="button"> 🏋️‍♀️ Exerciții</a>
            </li>
            <li class="nav-item disabled">
                <a class="nav-link" href="#" role="button"> 📆 Calendar</a>
            </li>
            @endif
        </ul>
            @if (Auth::user())
            <span class="navbar-text dropdown" style="margin-right: 20px;">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                        @if (Auth::user()->name == 'Călina')👩🏻‍💻 @else 👱🏼‍♂️ @endif{{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu logoutButton" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Deconectare</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </span>
            @endif
    </div>
    </nav>

    <div class="event-schedule-area-two bg-color pad100">
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
