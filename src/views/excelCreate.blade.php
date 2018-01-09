@extends(config('larafast.views_path').'.master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <fieldset class="mt30">
        <legend>{{ preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()) }}</legend>
        {{ Form::open(['route' => preg_replace('/\.excelCreate/', '.excelStore', app('router')->getCurrentRoute()->getName()), 'files' => true]) }}
        {{ Form::file_('excel', 'Excel', ['accept' => '.xlsx, .xls, .csv']) }}
        @include(config('larafast.views_path').'.save_and_reset_buttons')
        {{ Form::close() }}
    </fieldset>
@endsection
