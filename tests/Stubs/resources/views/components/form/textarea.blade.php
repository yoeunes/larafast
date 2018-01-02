<div class="col-sm-12 col-xs-12">
    <div class="form-group input-group{{ $errors->has($name) ? ' has-error has-feedback' : '' }}">
        {!! Form::label($name, is_array($placeholder) ? $placeholder['label'] : $placeholder ?? ucfirst($name), array_merge($label_attributes ?? [], ['class' => 'input-group-addon'])) !!}
        {!! Form::textarea($name, $value , array_merge(['id' => $name, 'placeholder' => is_array($placeholder) ? $placeholder['text'] : $placeholder ?? ucfirst($name), 'class' => 'form-control' . ($editor ? ' editor':'')], $attributes)) !!}
        @if($errors->has($name))
            <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        @endif
    </div>
    @if($errors->has($name))
        <span class="help-block">{{ $errors->first($name) }}</span>
    @endif
</div>
