@extends(config('larafast.path.views').'/master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <fieldset class="mt30">
        <legend>{{ preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()) }}</legend>
        {{ Form::open(['route' => preg_replace('/\.excelCreate/', '.excelStore', app('router')->getCurrentRoute()->getName()), 'files' => true]) }}
        {{ Form::file_('excel', 'Excel', ['accept' => '.xlsx, .xls, .csv']) }}
        @include(config('larafast.path.views').'/save_and_reset_buttons')
        {{ Form::close() }}
    </fieldset>
@endsection
