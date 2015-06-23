<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="{{$page->summary}}" name="description">
        <meta content="James Ilaki" name="author">
        <link href="{{asset('favicon.png')}}" rel="shortcut icon">
        <title>Nifty - @yield('page-title') </title>
        {{ HTML::style('assets/bootstrap/css/bootstrap.css') }}
        <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
        {{ HTML::style('assets/css/global.css') }}
        @yield('page-css')
        <!--[if lt IE 9]>
            {{ HTML::script('assets/bootstrap/js/html5shiv.js') }}
            {{ HTML::script('assets/bootstrap/js/respond.min.js') }}
        <![endif]-->
    </head>
    <body class="page page-home page-id-{{ $page->id }} slug-{{ $page->slug }}">

{{-- $PrimaryNavigation --}}


        <div class="container">
            @include('nifty.frontends.partials.top')
            <div class="row content">
                @yield('page-content')
            </div>


        </div>
        @include('nifty.frontends.partials.footer')

        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        {{ HTML::script('assets/js/responsive-menu.js') }}
        @yield('page-js')
    </body>
</html>
