@extends('layouts.default')

@include('plugins.datatables.default')
@include('plugins.moment')
@include('plugins.notifyjs')
@include('plugins.jstree')
@include('plugins.parsley')

@push("page-js")

<script>

    $(document).ready(function () {

        var $table = $('#datatable-profiles');

        var table = $table.DataTable({
            lengthChange: false,
            "language": {
                "url": "/plugins/datatables/i18n/Portuguese-Brasil.json"
            },
            ajax: '{{route('credentials.profiles.all')}}',
            "columns": [
                {"data": 'title'},
                {"data": 'created_at', "render": helper.datatables.datetime_format},
                {"data": 'updated_at', "render": helper.datatables.datetime_format},
                {
                    "data": 'id',
                    "orderable": false,
                    "render": function (data, type, row) {
                        return [
                            @can('credentials.profiles.show') '<a href="{{route('credentials.profiles.index')}}/' + data + '" class="mr-2 view" data-toggle="tooltip" title="Visualizar"><i class="fa fa-eye text-warning"></i></a>', @endcan
                            @can('credentials.profiles.edit') '<a href="{{route('credentials.profiles.index')}}/' + data + '/edit" class="mr-2 edit" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil text-primary"></i></a>', @endcan
                            @can('credentials.profiles.delete') '<a href="{{route('credentials.profiles.index')}}/' + data + '" class="mr-2 delete-link" data-toggle="tooltip" title="Remover"><i class="fa fa-trash-o text-danger"></i></a>' @endcan
                        ].join(' ')
                    }
                }
            ],
            "initComplete": function (settings, json) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        @can('credentials.profiles.delete')
            $table.delegate('.delete-link', 'click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.Notification.confirm('error', 'top center', 'Excluir Permanentemente', 'Deseja continuar?', function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: $('[name="csrf-token"]').prop('content')
                    },
                    success: function (result) {
                        table.ajax.reload();
                        $.Notification.notify('success', 'top right', 'Operação Realizada', 'Perfil removido');
                    },
                    error: function (result) {
                        $.Notification.notify('error', 'top center', 'Error', 'Ocorreu um erro ao excluir o perfil!')
                    }
                });
            });
        });
        @endcan

        @can('credentials.profiles.edit')
            $table.delegate('.edit', 'click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $('#form-edit').prop('action', url);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {
                    $('#form-edit').find('[name="title"]').val(result.role.title);

                    treeMake($('#checkTreeEdit'), result.permissions);

                    $('#modal-edit').modal('show');
                },
                error: function (result) {
                    $.Notification.notify('error', 'top center', 'Error', 'Ocorreu um erro inesperado!')
                }
            });
        });
        @endcan

        @can('credentials.profiles.show')
            $table.delegate('.view', 'click', function (e) {

            e.preventDefault();

            var url = $(this).attr('href');

            var _modal = $('#modal-view');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (result) {

                    treeMake($('#checkTreeView'), result.permissions);

                    _modal.find('[name="title"]').val(result.role.title);

                    _modal.modal('show');
                },
                error: function (result) {
                    $.Notification.notify('error', 'top center', 'Error', 'Ocorreu um erro inesperado!')
                }
            });
        });
        @endcan

        $('#form-create').parsley().on('form:submit', function () {

            var _form = $('#form-create');

            $.ajax({
                type: "POST",
                async: true,
                url: _form.attr('action'),
                data: {
                    title: _form.find('[name="title"]').val(),
                    _token: _form.find('[name="_token"]').val()
                },
                success: function (result) {

                    //hide create
                    $('#modal-create').modal('hide');
                    table.ajax.reload();
                    _form[0].reset();

                    //show edit
                    $('#form-edit').prop('action', _form.prop('action') + '/' + result.role.id);
                    $('#form-edit').find('[name="title"]').val(result.role.title);
                    treeMake($('#checkTreeEdit'), result.permissions);
                    $('#modal-edit').modal('show');
                },
                error: function (result) {

                    $('#modal-create').modal('hide');
                    table.ajax.reload();

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

            var permissions = [];

            $('#checkTreeEdit div').jstree("get_checked", null, true).forEach(function (v, k) {
                if ($.isNumeric(v)) {
                    permissions.push(v);
                }
            });

            $.ajax({
                async: true,
                url: _form.prop('action').replace('/edit', ''),
                type: 'PUT',
                data: {
                    title: _form.find('[name="title"]').val(),
                    permissions: permissions,
                    _token: _form.find('[name="_token"]').val()
                },
                success: function (data) {
                    $('#modal-edit').modal('hide');
                    table.ajax.reload();
                    _form[0].reset();

                    $.Notification.notify('success', 'top right', 'Operação Realizada', 'Perfil atualizado!');
                },
                error: function (result) {

                    $('#modal-edit').modal('hide');
                    table.ajax.reload();

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

        var treeMake = function (div, data) {
            div.find('div').remove();
            div.append($('<div>'));
            div.find('div').first().jstree({
                'core': {
                    'data': data,
                    expand_selected_onload: false,
                },
                'types': {
                    'default': {
                        'icon': 'fa fa-folder'
                    },
                    'file': {
                        'icon': 'fa fa-file'
                    }
                },
                'plugins': ['types', 'checkbox']
            });
        };

    });

</script>

@endpush

@section('page-title')
    <i class="fa fa-address-book-o"></i> Perfis
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-12">

            <div class="card-box">
                @can('credentials.profiles.create')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="m-b-30">
                            <a href="javascript:void(null)"
                               class="btn btn-sm btn-success waves-effect waves-light btn-custom" data-toggle="modal"
                               data-target="#modal-create">
                                Cadastrar <i class="mdi mdi-plus-circle-outline"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                <div class="row">
                    <table class="table table-striped" id="datatable-profiles">
                        <thead>
                        <tr>
                            <th>Título</th>
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

    <div id="modal-create" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="mdi mdi-plus-circle-outline"></i> Novo Perfil</h4>
                </div>

                {!! Form::open(['route'=>'credentials.profiles.store','id'=>"form-create"]) !!}
                <div class="modal-body">
                    <div class="row p-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Título</label>
                                {!! Form::text('title',null,['class'=>'form-control','required', 'placeholder'=>'Informe o título do perfil']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-custom" data-dismiss="modal">Fechar
                    </button>
                    <button type="submit" id="btn-sub-new-user"
                            class="btn btn-success waves-effect waves-light btn-custom">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="mdi mdi-plus-circle-outline"></i> Editar Perfil</h4>
                </div>
                {!! Form::open(['route'=>'credentials.profiles.index','id'=>"form-edit"]) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Título</label>
                                {!! Form::text('title',null,['class'=>'form-control','required', 'placeholder'=>'Informe o título do perfil']) !!}
                            </div>
                            <div class="card-box">
                                <h4 class="text-dark header-title m-0">Permissões</h4>

                                <div id="checkTreeEdit"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-custom" data-dismiss="modal">Fechar
                    </button>
                    <button type="submit" id="btn-sub-new-user"
                            class="btn btn-success waves-effect waves-light btn-custom">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div id="modal-view" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="mdi mdi-plus-circle-outline"></i> Novo Perfil</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Título</label>
                                {!! Form::text('title',null,['readonly','class'=>'form-control']) !!}
                            </div>
                            <div class="card-box">
                                <h4 class="text-dark header-title m-0">Permissões</h4>

                                <div id="checkTreeView"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-custom" data-dismiss="modal">Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection