@extends('layouts.wrapper')

@section('content')

    <div class="text-center">
        <a href="{{route('home')}}" class="logo-lg">
            <img src="{{asset('images/logo.png')}}" style="max-height:60px;" alt="MeetaWeb">
            <span>{{ config('app.name', 'MeetaWeb') }}</span>
        </a>
    </div>
    <div class="card-box m-t-20">
        <div class="text-center">
            <h5 class="text-uppercase font-bold m-b-0">Email Enviado</h5>
        </div>
        <div class="text-center">
            <img src="{{asset('/images/mail_confirm.png')}}" alt="img" class="thumb-lg m-t-20 center-block">

            <p class="text-muted font-13 m-t-20"> Um email foi enviado para <b>{{Request::get('email')}}</b>. <br>
                Siga as instruções para alterar sua senha. </p>
        </div>
    </div>
@endsection
