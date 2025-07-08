@if(Session::has('info') or Session::has('message'))
    <div class="alert alert-{{Session::get('status','info','warning')}} alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        @if(is_array(Session::get('message')))
            @if(count(Session::get('message')) > 1)
                <ul>
                @foreach(Session::get('message') as $message)
                    <li>{{$message}}</li>
                @endforeach
                </ul>
            @else
                {{Session::get('message')[0]}}    
            @endif
        @else
            {{Session::get('message')}}
        @endif
    </div>   
@endif