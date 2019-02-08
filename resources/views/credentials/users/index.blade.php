@extends('layouts.default')

@include('plugins.datatables.default')
@include('plugins.moment')
@include('plugins.custombox')
@include('plugins.select2')
@include('plugins.parsley')
@include('plugins.notifyjs')

@push("page-js")

<script>
    $(document).ready(function () {

        var $datatable_users = $('#datatable-users');

        var table = $('#datatable-users').DataTable({
            lengthChange: false,
            "language": {
                "url": "/plugins/datatables/i18n/Portuguese-Brasil.json"
            },
            ajax: '{{route('credentials.users.all')}}',
            "columns": [
                {"data": 'name'},
                {"data": 'email'},
                {
                    "data": 'roles',
                    "render": function (data) {
                        var roles = '';
                        $.map(data, function (v) {
                            roles += roles == '' ? v.title : ', ' + v.title;
                        });
                        return roles;
                    }
                },
                {"data": 'created_at', "render": helper.datatables.datetime_format},
                {"data": 'updated_at', "render": helper.datatables.datetime_format},
                {
                    "data": 'id', "ordering": false, "render": function (data, type, row) {
                    return [
                        @can('credentials.users.edit')'<a href="{{route('credentials.users.index')}}/' + data + '/edit" class="mr-2 edit" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil text-primary"></i></a>', @endcan
                        @can('credentials.users.delete')'<a href="{{route('credentials.users.index')}}/' + data + '" class="mr-2 delete-link" data-toggle="tooltip" title="Remover"><i class="fa fa-trash-o text-danger"></i></a>' @endcan
                    ].join(' ')
                }
                }
            ],
            "initComplete": function (settings, json) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });


        $('.select2').select2({language: 'pt-BR', tags: 'true'});

        $('#form-create').parsley().on('form:submit', function () {

            var _form = $('#form-create');

            $.ajax({
                type: "POST",
                async: true,
                url: _form.attr('action'),
                data: {
                    name: _form.find('[name="name"]').val(),
                    email: _form.find('[name="email"]').val(),
                    roles: _form.find('[name="roles[]"]').val(),
                    notify: _form.find('[name="notify"]').is(":checked"),
                    _token: _form.find('[name="_token"]').val()
                },
                success: function (data) {
                    $('#user-modal').modal('hide');
                    table.ajax.reload();
                    _form[0].reset();
                    _form.find('select').change();
                    _form.find('.switchery').click().click();
                    $.Notification.notify('success', 'top right', 'Operação Realizada', 'Usuário cadastrado')
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

        $('#form-edit').parsley().on('form:submit', function () {

            var _form = $('#form-edit');

            $.ajax({
                async: true,
                url: _form.prop('action').replace('/edit', ''),
                type: 'PUT',
                data: {
                    name: _form.find('[name="name"]').val(),
                    email: _form.find('[name="email"]').val(),
                    roles: _form.find('[name="roles[]"]').val(),
                    _token: _form.find('[name="_token"]').val()
                },
                success: function (data) {
                    table.ajax.reload();
                    $('#edit-user-modal').modal('hide');
                    $.Notification.notify('success', 'top right', 'Operação Realizada', 'Usuário atualizado!')
                    _form[0].reset();
                    _form.find('select').change();
                    table.draw();
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


        @can('credentials.users.edit')
        $('#datatable-users').delegate('.edit', 'click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $('#form-edit').prop('action', url);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {

                    var _modal = $('#edit-user-modal');

                    _modal.find('[name="name"]').val(result.data.name);
                    _modal.find('[name="email"]').val(result.data.email);

                    let roles = [];

                    $(result.data.roles).each((k, v) => {
                        roles.push(v.id);
                    });

                    _modal.find('[name="roles[]"]').val(roles).change();

                    _modal.modal('show');

                    },
                error: function (result) {
                    $.Notification.notify('error', 'top center', 'Error', 'Ocorreu um erro inesperado!')
                }
            });
        });

        $(document).on('hide.bs.modal', '#edit-user-modal', function () {
            $(this).find('select option').prop('selected', false);
        });
        @endcan

        @can('credentials.users.delete')
        $('#datatable-users').delegate('.delete-link', 'click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.Notification.confirm('error', 'top center', 'Excluir usuário', 'Deseja continuar?', function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: $('[name="csrf-token"]').prop('content')
                    },
                    success: function (result) {
                        table.ajax.reload();
                        $.Notification.notify('success', 'top right', 'Operação Realizada', 'Usuário removido');
                    },
                    error: function (result) {
                        $.Notification.notify('error', 'top center', 'Error', 'Ocorreu um erro ao excluir o usuário!')
                    }
                });
            });
        });
        @endcan




    });
</script>

@endpush

@section('page-title')
    <i class="fa fa-id-badge"></i> Usuários
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-12">

            <div class="card-box">
                @can('credentials.users.create')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="m-b-30">
                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light"
                                    data-toggle="modal"
                                    data-target="#user-modal">
                                Cadastrar <i class="mdi mdi-plus-circle-outline"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endcan

                <div class="row">
                    <table class="table table-striped" id="datatable-users">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Criado</th>
                            <th>Atualizado</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Novo Usuário</h4>
                </div>
                {!! Form::open(['route'=>'credentials.users.store','id'=>'form-create']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        {!! Form::text('name',null,['class'=>'form-control','required', 'placeholder'=>'Nome do usuário ']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::email('email',null,['class'=>'form-control','required', 'placeholder'=>'Email ']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::select('roles[]', $roles->pluck('title','id'), null, ['class'=>'select2 form-control', 'multiple', 'required', 'data-placeholder'=>'Perfil ']) !!}
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="notify" checked data-size="small" data-plugin="switchery"
                               data-color="#00b19d"/>
                        Notificar usuário
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="btn-sub-new-user" class="btn btn-success waves-effect waves-light">
                        Salvar
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div id="edit-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Editar Usuário</h4>
                </div>
                {!! Form::open(['route'=>'credentials.users.index','id'=>'form-edit']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        {!! Form::text('name',null,['class'=>'form-control','required', 'placeholder'=>'Nome do usuário ']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::email('email',null,['class'=>'form-control','required', 'placeholder'=>'Email ']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::select('roles[]', $roles->pluck('title','id'), null, ['data-tags'=>"true",'class'=>'form-control select2', 'multiple', 'required', 'data-placeholder'=>'Perfil']) !!}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="btn-sub-new-user" class="btn btn-success waves-effect waves-light">
                        Salvar
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
