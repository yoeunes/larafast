@extends(config('larafast.views_path').'.master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <div class="mt30">
        <div class="table-wrapper">
            {!! $dataTable->table(['class' => 'table table-bordered']) !!}
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset(config('larafast.assets_path').'/datatables/css/datatables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset(config('larafast.assets_path').'/datatables/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset(config('larafast.assets_path').'/datatables/css/buttons.dataTables.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset(config('larafast.assets_path').'/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset(config('larafast.assets_path').'/datatables/js/buttons.server-side.js') }}"></script>
    <script src="{{ asset(config('larafast.assets_path').'/datatables/js/datatables.bootstrap.js') }}"></script>
    <script src="{{ asset(config('larafast.assets_path').'/datatables/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset(config('larafast.assets_path').'/datatables/js/dataTables.buttons.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
