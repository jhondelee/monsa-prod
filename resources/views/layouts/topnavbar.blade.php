    
<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i></a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Welcome to MONSA Trading</span>
            </li>
            <li>
                <a href="{{route('logout')}}" class="auth-logout">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
                {!!Form::open(['route'=>'logout', 'id'=>'logout-form'])!!} {!!Form::close()!!}
            </li>
        </ul>
    </nav>
</div>
      