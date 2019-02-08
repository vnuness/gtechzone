@extends('layouts.wrapper')

@section('content')
    <div class="text-center">
        <div class="logo-lg">
            <img src="{{asset('images/logo.png')}}" style="max-height:60px;" alt="MeetaWeb">
            <span>{{ config('app.name', 'MeetaWeb') }}</span>
        </div>
    </div>

    <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group row">
            <div class="col-12">
                <div class="input-group">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                    </div>
                    <input id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Email">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="mdi mdi-key"></i></span>
                    </div>
                    <input id="password" type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           name="password" required placeholder="Senha">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
                <div class="checkbox checkbox-primary">
                    <input type="checkbox"
                           name="remember" {{ old('remember') ? 'checked' : '' }}
                           id="checkbox-signup">
                    <label for="checkbox-signup">
                        Manter logado
                    </label>
                </div>

            </div>
        </div>

        <div class="form-group text-right m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit">
                    Entrar
                </button>
            </div>
        </div>

        <div class="form-group row m-t-30">
            <div class="col-sm-7">
                <a class="text-muted" href="{{ route('password.request') }}">
                    <i class="fa fa-lock m-r-5"></i> Esqueci minha senha
                </a>

            </div>
        </div>
    </form>
@endsection
