@extends('larafast.master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <div class="space"></div>
    <div class="global">
        <div class="global-title">
            <i class="fa fa-plus-circle"></i> {{ preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()) }}
        </div>
        <div class="global-body">
            {{ Form::open(['route' => preg_replace('/\.create/', '.store', app('router')->getCurrentRoute()->getName()), 'files' => true]) }}
                <div class="row">
                    @include(getForm())
                </div>
                @include('larafast.default.partials._save_and_reset_buttons')
            {{ Form::close() }}
        </div>
    </div>
@endsection