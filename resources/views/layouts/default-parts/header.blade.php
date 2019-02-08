<nav class="navbar-custom" style="background: #4c5667;">

    <ul class="list-inline float-right mb-0" >

        <li class="list-inline-item notification-list hide-phone">
            <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                <i class="mdi mdi-crop-free noti-icon"></i>
            </a>
        </li>

        <li class="list-inline-item notification-list">
            <a class="nav-link right-bar-toggle waves-light waves-effect" href="#">
                <i class="mdi mdi-dots-horizontal noti-icon"></i>
            </a>
        </li>

        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown"
               href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <img src="{{
                            auth()->user()->avatar?
                            asset('storage/images/users/'.auth()->user()->avatar):
                            asset('images/avatar-example.png')
                            }}"
                     alt="user" class="rounded-circle">
            </a>

            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5 class="text-overflow">
                        <small>
                            {{ Auth::check() ? Auth::user()->name : '' }}
                        </small>
                    </h5>
                </div>

                <!-- item-->
                <a href="{{route('profile.edit')}}" class="dropdown-item notify-item">
                    <i class="mdi mdi-account"></i> <span>Perfil</span>
                </a>

                <!-- item-->
                {!! Form::open(['route'=>'logout']) !!}
                <button type="submit" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout"></i> <span>Sair</span>
                </button>
                {!! Form::close() !!}

            </div>
        </li>

    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button style="background: #4c5667;" class="button-menu-mobile open-left waves-light waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
    </ul>

</nav>