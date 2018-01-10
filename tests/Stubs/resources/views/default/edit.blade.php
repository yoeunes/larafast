@extends(config('larafast.path.views').'/master')

@title(preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()))

@section('content')
    <div class="space"></div>
    <div class="global">
        <div class="global-title">
            <i class="fa fa-plus-circle"></i> {{ preg_replace('/\./', ' ', app('router')->getCurrentRoute()->getName()) }}
        </div>
        <div class="global-body">
            {{ Form::model($entity, ['route' => [preg_replace('/\.edit/', '.update', app('router')->getCurrentRoute()->getName()), $entity->id], 'method' => 'PUT', 'files' => true]) }}
            <div class="row">
                @include(getForm('edit'))
            </div>
            @include(config('larafast.path.views').'/save_and_reset_buttons')
            {{ Form::close() }}
        </div>
    </div>
@endsection
