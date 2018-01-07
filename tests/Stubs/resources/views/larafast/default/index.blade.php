@extends('larafast.master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <div class="mt30">
        <div class="table-wrapper">
            {!! $dataTable->table(['class' => 'table table-bordered']) !!}
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables_laravel/datatables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables_laravel/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables_laravel/buttons.dataTables.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/datatables_laravel/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables_laravel/buttons.server-side.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables_laravel/datatables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables_laravel/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables_laravel/dataTables.buttons.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
