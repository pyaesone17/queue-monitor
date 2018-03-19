@extends('queue-monitor::layouts')

@section('content')
    <ul class="list-group">
        @foreach($failed_jobs as $job)
            <li class="list-group-item">{{ $job->id }}. 
                <a href="{{ route('queue-monitor::queue-monitor.show',$job->id) }}"> {{ $job->payload->displayName }}::class </a>
                <span> <small class="text-muted"> failed at {{ $job->failed_at->diffForHumans() }}</small> </span>
                <span class="pull-right">
                    <form action="{{ route('queue-monitor::queue-monitor.update',$job->id)}}" style="display:inline" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="patch" />
                        <button type="submit" class="btn btn-lg btn-primary">Requeue</button> 
                    </form>
                    <form action="{{ route('queue-monitor::queue-monitor.destroy',$job->id)}}" style="display:inline" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="delete" />
                        <button type="submit" class="btn btn-lg btn-danger">Delete</button> 
                    </form>
                </span>
                <div class="row"></div>
            </li>
        @endforeach
    </ul>

@endsection

@push('scripts')
<script>
        $(function() {
            new Chart($('canvas#stats-doughnut-chart'), {
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

