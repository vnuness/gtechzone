@extends('layouts.default')

@include('plugins.datatables.default')
@include('plugins.datatables.buttons')
@include('plugins.amcharts')
@include('plugins.ammap')
@include('plugins.circliful')
@include('plugins.sparkline')
@include('plugins.notifyjs')

@push("page-js")
<script>

    function GetData(){
        return  {   
                    '_token': $('input[name="_token"]').val(),
                    'os' : $('input[name="os"]').val()
                }
    }

    $(document).ready(function () {
        var datatable = $('#table_pesqchat').DataTable({
            "destroy": true,
            "ajax": {
                "url": "{{route('listProducts')}}",
                "data" : GetData,
                "type": "POST"
            },
            "columns": [
                {"data" : "n_os"},
                {"data" : "n_serie"},
                {"data" : "aparelho"},
                {"data": "localizacao"}
            ],

            "language": {
                "url": "/plugins/datatables/i18n/Portuguese-Brasil.json"
            },
            dom: 'Bfrtip',
            buttons: [
                'csv','excel', 'pdf'
            ]
        });

         $('#form-os').on('submit', function (e) {
            datatable.ajax.reload();
            $('#filters').modal('hide');
            return false;
        });
    });

   

    
</script>
script
@endpush

@push('page-css')
<style>

</style>
@endpush

@section('page-title')
    <i class="fa fa-dash"></i> Estoque
@endsection

@section('content')
    <div class="row">
    <div class="col-md-12">

<!-- Resultado da Pesquisa -->
<div class="portlet">
    <div class="portlet-heading bg-inverse">
        <h3 class="portlet-title">
            <i class="ion-search"></i> RESULTADO DA PESQUISA
        </h3>

        <div class="portlet-widgets">
            <button id="filters_button"
                    class="btn btn-outline-success btn-rounded waves-effect waves-light"
                    data-toggle="modal"
                    data-target="#filters">
                <i class="ion-funnel"></i>
                Localizar O.S
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="bg-inverse" class="panel-collapse collapse show">
        <div class="portlet-body">
            <div class="table-responsive mt-3">
                <table id="table_pesqchat" class="table table-bordered" style="width:100%; font-size: 12px;" >
                    <thead>
                    <tr>
                        <th>Nº O.S</th>
                        <th>Nº Série</th>
                        <th>Aparelho</th>
                        <th>Localização</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>45446</td>
                        <td>1524234</td>
                        <td>PS4 SLIM</td>
                        <td>A1</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="filters" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CenterModalLabel"
         aria-hidden="true">
        <form id="form-os" class="form-horizontal" role="form" method="POST">
            {{ csrf_field() }}

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="CenterModalLabel">Filtros</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row" id="daterange_row">
                            <label for="inputEmail3" class="col-md-3 col-form-label"> Nº O.S </label>

                            <div class="input-daterange input-group col-md-9" id="date-range">
                                <input class="form-control" name="os" type="text" autocomplete="off">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar
                        </button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            Aplicar
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </form>
    </div><!-- /.modal -->

</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

@endsection
