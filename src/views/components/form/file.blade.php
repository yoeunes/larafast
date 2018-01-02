<div class="col-sm-6 field">
    <div class="form-group input-group{{ $errors->has($name) ? ' has-error has-feedback' : '' }}">
        {!! Form::label($name, is_array($placeholder) ? $placeholder['label'] : $placeholder ?? ucfirst($name), array_merge($label_attributes ?? [], ['class' => 'input-group-addon'])) !!}
        {!! Form::file($name, array_merge(['id' => $name, 'placeholder' => is_array($placeholder) ? $placeholder['file'] : $placeholder ?? ucfirst($name), 'class' => 'form-control'], $attributes)) !!}
        @if($errors->has($name))
            <span class="glyphicon glyphicon-remove form-control-feedback"></span>
        @endif
    </div>
    @if($errors->has($name))
        <span class="help-block">{{ $errors->first($name) }}</span>
    @endif
</div>

