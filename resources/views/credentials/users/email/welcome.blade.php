@component('mail::layout')

@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{config('app.name')}}
@endcomponent
@endslot

# {{ "Olá, seja bem vindo." }}

{{ "Para acessar o sistema utilize seu email e a senha temporária." }}
### {{ "Senha: {$pass}" }}

@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. All rights reserved
@endcomponent
@endslot

@endcomponent