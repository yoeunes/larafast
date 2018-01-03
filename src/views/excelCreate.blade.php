@extends('master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <fieldset class="mt30">
        <legend>{{ preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()) }}</legend>
        {{ Form::open(['route' => preg_replace('/\.excelCreate/', '.excelStore', app('router')->getCurrentRoute()->getName()), 'files' => true]) }}
        {{ Form::file_('excel', 'Excel', ['accept' => '.xlsx, .xls, .csv']) }}
        <div class="col-sm-12">
            <div class="form-group-btn">
                <button type="submit" class="btn btn-primary pull-right mb5 w200">Enregistrer</button>
                <button type="reset" class="btn btn-danger pull-right mr5 mb5 w200">Réinitialiser</button>
            </div>
        </div>
        {{ Form::close() }}
    </fieldset>
@endsection
