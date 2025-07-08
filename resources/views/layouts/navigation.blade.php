 <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                                <img alt="image" class="img-circle" src="/img/temp-logo.jpg" />
                                 </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{$auth_employee->firstname}} {{$auth_employee->lastname}}</strong>
                                 </span> <span class="text-muted text-xs block">{{$auth_role->display_name}}<b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="{{ route('user.edit',$auth_user->id)}}">Change password</a></li>
                                <li class="divider"></li>
                                <li><a id="logout-form" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            <img alt="image" class="img-circle" src="/img/temp-logo.jpg"/>
                        </div>
                    </li> 

           @foreach($auth_navigation->where('group_id', null) as $nav)
                @if(isRouteExist($nav->route_name))
                    <li class="{{isActive('location.index')}}">
                        <a href="{{route($nav->route_name)}}">
                            <i class="fa {{$nav->icon_class}}"></i> 
                            <span class="nav-label">{{$nav->display_name}}</span>
                        </a>
                    </li>
                @endif
            @endforeach    
            
            @foreach($navGroup as $group)
                @if($auth_navigation->where('group_id', $group->id)->count())
                    <li class="nav-group">
                        <a href="#"><i class="fa {{$group->icon_class}}"></i><span class="nav-label">{{$group->name}}</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                        @foreach($auth_navigation->where('group_id', $group->id) as $nav)
                            @if(isRouteExist($nav->route_name))
                                <li class="{{isActive($nav->route_name)}}"><a href="{{route($nav->route_name)}}"><i class="fa fa-caret-right"></i> {{$nav->display_name}}</a></li>                          
                            @endif
                        @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

                    <li class="landing_link">
                        <a ui-sref="landing" href="#"><i class=""></i> <span class="nav-label ng-binding"></span> <span class="label label-warning pull-right"></span></a>
                    </li>

            </ul>

    </div>

</nav>
        

   