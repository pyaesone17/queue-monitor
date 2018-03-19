@extends('queue-monitor::layouts')

@section('content')

    <h2 class="text-center">  {{ $job->payload->displayName }}::class Failed at {{ $job->failed_at->diffForHumans() }}
    </h2>

    <h3> ID : {{ $job->id }} </h3>
    <h3> Connection : {{ $job->connection }} </h3>
    <h3> Queue : {{ $job->queue }} </h3>

    <div class="well well-lg">
        Exception : {{ $job->exception }}
    </div>

    <form action="{{ route('queue-monitor::queue-monitor.update',$job->id)}}" style="display:inline" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="patch" />
        <input type="hidden" name="_rdt" value="{{ route('queue-monitor::queue-monitor.index') }}" />
        <button type="submit" class="btn btn-lg btn-primary">Requeue</button> 
    </form>

    <form action="{{ route('queue-monitor::queue-monitor.destroy',$job->id)}}" style="display:inline" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="delete" />
        <input type="hidden" name="_rdt" value="{{ route('queue-monitor::queue-monitor.index') }}" />
        <button type="submit" class="btn btn-lg btn-danger">Delete</button> 
    </form>

    <br/><br/>
@endsection

