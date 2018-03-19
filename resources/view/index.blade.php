@extends('queue-monitor::layouts')

@section('content')

    <div class="row">
        <div class="col-lg-6"> 
            <canvas id="stats-doughnut-chart" height="150"></canvas>
            <h3 class="text-center"> {{ date('d-F-Y') }}  - Today Failed Jobs Reporting 
                <span class="label label-info">{{ $today_failed_jobs->count() }}</span>
            </h3> 
            @if($today_failed_jobs->count())
                <ul class="list-group">
                    @foreach($today_failed_jobs as $job)
                        <li class="list-group-item">
                            <a href="{{ route('queue-monitor::queue-monitor.show',$job->id) }}"> {{ $job->payload->displayName }}::class </a>
                            <span class="pull-right"> {{ $job->failed_at->diffForHumans() }} </span>
                        </li>
                    @endforeach
                    <li class="list-group-item">
                    <a type="button" class="btn btn-lg btn-primary" href="{{ route('queue-monitor::getManage','today') }}">Manage</a>
                    </li>
                </ul>
            @else
                <h4 class="text-success text-center">Yay !!! There is no failed jobs Today.</h4>
            @endIf
        </div>

        <div class="col-lg-6"> 
            <canvas id="stats-doughnut-chart-this-month" height="150"></canvas>
            <h3 class="text-center"> {{ date('F') }} - This Month Failed Jobs Reporting 
                <span class="label label-info">{{ $thisMonth_failed_jobs->count() }}</span>
            </h3>
            @if($thisMonth_failed_jobs->count())
                <ul class="list-group">
                    @foreach($thisMonth_failed_jobs as $job)
                        <li class="list-group-item">
                            <a href="{{ route('queue-monitor::queue-monitor.show',$job->id) }}"> {{ $job->payload->displayName }}::class </a>
                            <span class="pull-right"> {{ $job->failed_at->diffForHumans() }} </span>
                        </li>
                    @endforeach
                    <li class="list-group-item">
                        <a type="button" class="btn btn-lg btn-primary" href="{{ route('queue-monitor::getManage','this-month') }}">Manage</a>
                    </li>
                </ul>
            @else
                <h4 class="text-success text-center">Yay !!! There is no failed jobs This Month.</h4>
            @endIf
        </div>
    </div>
    
    <hr/>
    <canvas id="stats-doughnut-chart-all" height="80"></canvas>
    <h3 class="text-center"> All Failed Jobs <span class="label label-info">{{ $failed_jobs->count() }}</span></h3>
    @if($failed_jobs->count())
        <ul class="list-group">
            @foreach($failed_jobs as $job)
                <li class="list-group-item">
                    <a href="{{ route('queue-monitor::queue-monitor.show',$job->id) }}"> {{ $job->payload->displayName }}::class </a>
                    <span class="pull-right"> {{ $job->failed_at->diffForHumans() }} </span>
                </li>
            @endforeach
            <li class="list-group-item">
                <a type="button" class="btn btn-lg btn-primary" href="{{ route('queue-monitor::getManage','all') }}">Manage</a>
            </li>
        </ul>
    @else
        <h4 class="text-success text-center">Yay !!! There is no failed jobs.</h4>
    @endIf
    <br/><br/>
@endsection

@push('scripts')
<script>
        $(function() {
            new Chart($('canvas#stats-doughnut-chart'), {
                type: 'doughnut',
                data: {!! $today_chart !!},
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });

        $(function() {
            new Chart($('canvas#stats-doughnut-chart-this-month'), {
                type: 'doughnut',
                data: {!! $thisMonth_chart !!},
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });

        $(function() {
            new Chart($('canvas#stats-doughnut-chart-all'), {
                type: 'doughnut',
                data: {!! $chart !!},
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });
    </script>

@endpush

