@if($status != NULL AND $message != NULL)
    <br/>
    <div class="alert alert-{{$status}}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! $message !!}
    </div>
@endIf
