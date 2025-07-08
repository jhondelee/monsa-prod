
@extends('layouts.auth')

@section('content')


  
<div class="middle-box text-center loginscreen animated fadeInDown">
   
       <div>
            <div>

                <h1 class="logo-name">
                  
                        <img alt="image" width="500" height="500" class="img-responsive" src="/img/temporary-logo.jpg"/>
                  
                </h1>

            </div>

            <h3>Monsa Sales and Inventory System</h3>

            @include('layouts.alert2')

            <form class="m-t" role="form" method="POST" action="{{ route('login') }}">

                {{ csrf_field() }}
                
                <div class="form-group">

                    {!! Form::text ('username',null,['class'=>'form-control','placeholder'=>'Username', 'required'=>''])!!}

                       @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                </div>

                <div class="form-group">

                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">


                      @if ($errors->has('password'))
                            <span class="help-block">
                               <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            <!-- <a href="#"><small>Forgot password?</small></a> -->

            </form>

            <p class="m-t"> <small>MONSA Trading &copy; 2024</small> </p>

        </div>

    </div>
@endsection
