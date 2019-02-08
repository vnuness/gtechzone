@extends('layouts.wrapper')

@section('content')

    <div class="text-center">
        <div class="logo-lg">
            <img src="{{asset('images/logo.png')}}" style="max-height:60px;" alt="MeetaWeb">
            <span>{{ config('app.name', 'MeetaWeb') }}</span>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" role="form" class="text-center m-t-20">
        @csrf

        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Coloque o seu <b>Email</b> e as instruções vão ser enviadas para você!
        </div>
        <div class="form-group m-b-0">
            <div class="input-group">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email" value="{{ old('email') }}" required placeholder="Email ...">

                <span class="input-group-append">
                    <button type="submit" class="btn btn-email btn-primary waves-effect waves-light"
                            style="border-radius: 0 .25rem .25rem 0;">
                        Enviar
                    </button>
                </span>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('login')}}" class="pull-left">
                    <i class="fa fa-reply"></i> Voltar
                </a>
            </div>
        </div>

    </form>
@endsection
