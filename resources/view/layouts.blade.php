<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Queue Monitor</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/queue-monitor/css/bootstrap.min.css') }}" media="all" />
    <style>
        .btn-primary{
            background: #54A1E5;
            border-color: #54A1E5;
        }
        .btn-danger{
            background: #EE6E85;
            border-color:#EE6E85;
        }
        #floating-home{
            width: 80px;
            height: 80px;
            border-radius: 50px;
            line-height: 80px;
            position: fixed;
            right: 80px;
            bottom: 80px;
            background: #62B9A8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center"> Failed Job Monitoring In a Nutshell In Parallel Universe</h1>
        <br/>
        <div class="text-center">
            <img src="{{ asset('vendor/queue-monitor/image/icon.png') }}" style="width:150px"/>
        </div>

        <br/>
        <br/>
        <hr/>

        @include('queue-monitor::partials.alert',[
            'status' => session('status'),
            'message' => session('message')
        ])
        @yield('content')
    </div>

    <div id="floating-home">
        <a href="{{ route('queue-monitor::queue-monitor.index') }}">
            <img src="{{ asset('vendor/queue-monitor/image/home.png') }}" style="width:50px; position:absolute; left: 15px; top: 15px"/>
        </a>
    </div>

    <script src="{{ asset('vendor/queue-monitor/js/chart.min.js') }}"></script>
    <script src="{{ asset('vendor/queue-monitor/js/jquery.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
