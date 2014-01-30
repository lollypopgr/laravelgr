<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>
            {{ Config::get('docs.title', 'Documentation') }}
            @if(!empty($title))
                : {{ $title }}
            @endif
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="{{ url('css/style.css') }}">
        <script src="{{ url('js/vendor/modernizr-2.6.2.min.js') }}"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="wrapper">
            <header>
                <h1>{{ Config::get('docs.title', 'Documentation') }}</h1>
            </header>
            <aside class="sidebar">
                @yield('sidebar')
            </aside>
            <section class="content">
                @yield('content')
                <nav>
                @if($prev)
                    <a href="{{ $prev['URI'] }}" title="Previous: {{ $prev['title'] }}">← {{ $prev['title'] }}</a> |
                @endif
                @if($next)
                    <a href="{{ $next['URI'] }}" title="Next: {{ $next['title'] }}">{{ $next['title'] }} →</a>
                @endif
                </nav>
                <footer>
                    <p><a href="http://github.com/daylerees/docs-reader" title="Documentation reader by Dayle Rees.">Docs reader</a> by <a href="http://daylerees.com" title="Dayle Rees">Dayle Rees</a>.</p>
                </footer>
            </section>
            <div class="clearfix"></div>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ url('js/vendor/jquery-1.8.2.min.js') }}"><\/script>')</script>
        <script src="{{ url('js/vendor/prettify.js') }}"></script>
        <script src="{{ url('js/plugins.js') }}"></script>
        <script src="{{ url('js/main.js') }}"></script>

       @include('layout.analytics')
    </body>
</html>
