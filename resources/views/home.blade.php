@extends('layouts.default')

@include('plugins.amcharts')
@include('plugins.ammap')
@include('plugins.circliful')
@include('plugins.sparkline')
@include('plugins.notifyjs')

@push("page-js")
<script>

    $(document).ready(function () {

    });

</script>
@endpush

@push('page-css')
<style>

</style>
@endpush

@section('page-title')
    <i class="fa fa-dash"></i> Dashboard
@endsection

@section('content')
    <div class="row">
        <h1>Conteúdo</h1>
    </div>
@endsection
