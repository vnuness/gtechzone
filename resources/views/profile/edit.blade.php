@extends('layouts.default')

@include('plugins.parsley')
@include('plugins.notifyjs')
@include('plugins.croppie')

@section('page-title')
    Perfil
@endsection

@push('page-js')

<script>

    var imagem;

    $(document).ready(function () {

        $uploadCrop = $('.image-crop').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 150,
                type: 'circle'
            },
            boundary: {
                width: 170,
                height: 170
            }
        });


        $('#upload').on('change', function () {

            var reader = new FileReader();

            reader.onload = function (e) {

                $('#img-avatar, #upload-button').hide(0);
                $('.crop-image-container, .crop-buttons ').show(0);

                $uploadCrop.croppie('bind', {
                    url: e.target.result
                });

            }

            reader.readAsDataURL(this.files[0]);
        });


        $('#confirm-crop').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {

                imagem = resp;

                $('#img-avatar').prop('src', resp);

                $('.crop-image-container, .crop-buttons ').hide(0);
                $('#img-avatar, #upload-button').show(0);
            });
        });

        $('#cancel-crop').on('click', function (ev) {
            $('.crop-image-container, .crop-buttons ').hide(0);
            $('#img-avatar, #upload-button').show(0);
        });

        $('#profile-update').parsley().on('form:submit', function () {

            var _form = $('#profile-update');

            $.ajax({
                type: "POST",
                async: true,
                url: _form.attr('action'),
                data: {
                    name: _form.find('[name="name"]').val(),
                    "old-password": _form.find('[name="old-password"]').val(),
                    "new-password": _form.find('[name="new-password"]').val(),
                    avatar: imagem,
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {
                    _form.find('[name="old-password"]').val('');
                    _form.find('[name="new-password"]').val('');
                    $.Notification.notify('success', 'top right', 'Operação Realizada', 'Perfil atualizado')
                },
                error: function (result) {

                    var errors = "Ocorreu um erro inesperado!";

                    if (result.responseJSON.errors) {
                        errors = "";
                        $.map(result.responseJSON.errors, function (v) {
                            errors += v + "<br>\n";
                        });
                    }

                    $.Notification.notify('error', 'top center', 'Atenção', errors);
                }
            });

            return false;
        });

    });

</script>

@endpush

@push('page-css')

<style>

    .crop-image-container {
        display: none;
    }

    .crop-buttons {
        display: none;
    }

</style>

@endpush

@section('content')

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="text-center card-box">
                <div class="member-card">
                    <div class="thumb-xl member-thumb m-b-10 center-block">
                        <img id="img-avatar"
                             src="{{
                            auth()->user()->avatar?
                            asset('storage/images/users/'.auth()->user()->avatar):
                            asset('images/avatar-example.png')
                        }}"
                             class="rounded-circle img-thumbnail"
                             alt="profile-image" id="avatar">

                        <div class="crop-image-container">
                            <div class="image-crop"></div>
                        </div>
                        <div class="crop-buttons row">
                            <div class="btn-group m-b-10 col-md-8 col-offset-2">
                                <button type="button" id="cancel-crop"
                                        class="col-md-6 btn btn-danger btn-sm waves-effect waves-light btn-custom">
                                    <i class="fa fa-times"></i>
                                </button>
                                <button type="button" id="confirm-crop"
                                        class="col-md-6 btn btn-success btn-sm waves-effect waves-light btn-custom">
                                    <i class="fa fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <h5 class="m-b-5">{{auth()->user()->email}}</h5>

                    <p class="text-muted small">{!! auth()->user()->roles->implode('title','<br>')!!}</p>
                </div>
                <button type="button" onclick="$('#upload').trigger('click')"
                        class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light btn-custom"
                        id="upload-button">
                    <span>Alterar Imagem</span>
                </button>
                <input type="file" name="avatar" id="upload" value="Escolha um arquivo" accept="image/*"
                       style="display: none;">
                <input type="hidden" name="avatar">
            </div>

        </div>

        <div class="col-lg-8 col-xl-9">

            <div class="">
                {!! Form::model(auth()->user(),['route'=>'profile.update','method'=>'POST', 'id'=>'profile-update', 'files'=>true]) !!}
                <div class="card-box">
                    <ul class="nav nav-tabs tabs-bordered">
                        <li class="nav-item">
                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                Configurações
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form role="form">
                                <div class="form-group">
                                    <label for="FullName">Nome</label>
                                    {!! Form::text('name',null,['id'=>'name','class'=>'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="FullName">Login</label>
                                    {!! Form::text('login',auth()->user()->email,['class'=>'form-control', 'readonly']) !!}
                                </div>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-xs-12">
                                        <label for="Password">Senha Atual</label>
                                        {!! Form::password('old-password',['id'=>'password','class'=>'form-control']) !!}
                                    </div>
                                    <div class="form-group col-md-6 col-xs-12">
                                        <label for="RePassword">Senha Nova</label>
                                        {!! Form::password('new-password',['id'=>'new-password','class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary waves-effect waves-light w-md pull-right btn-custom"
                                                type="submit">
                                            <i class="fa fa-save"></i> Salvar
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>

@endsection