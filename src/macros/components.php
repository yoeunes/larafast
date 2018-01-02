<?php

Form::component('text_', 'components.form.text', [
    'name',
    'placeholder' => null,
    'value' => null,
    'attributes' => [],
    'label_attributes' => null
]);

Form::component('textarea_', 'components.form.textarea', [
    'name',
    'editor' => true,
    'placeholder' => null,
    'value' => null,
    'attributes' => [],
    'label_attributes' => null
]);

Form::component('file_', 'components.form.file', [
    'name',
    'placeholder' => null,
    'attributes' => [],
    'label_attributes' => null
]);

Html::macro('nav', function ($name, $icon = '') {

    $cb = function ($fn) {
        return $fn;
    };

    return <<<"HTML"
<li class="">
            <a href="#" class="dropdown-toggle">
                <i class="fa fa-{$icon}"></i>
                {$cb(ucfirst($name))}
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="">
                    <a href="{$cb(route($name.'.create'))}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Add {$cb(str_singular($name))}
                    </a>
                
                    <b class="arrow"></b>
                </li>
                <li class="">
                    <a href="{$cb(route($name.'.index'))}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        {$cb(ucfirst(str_singular($name)))} list
                    </a>
                
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
HTML;
});

