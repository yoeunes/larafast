<p>
    Please add ur form :
    <ul>
        <li>{{ preg_replace('/(\.create|\.edit)/', '._form', session('uri')) }}</li>
        <li>{{ preg_replace('/(\.create|\.edit)/', '._form_create', session('uri')) }}</li>
        <li>{{ preg_replace('/(\.create|\.edit)/', '._form_edit', session('uri')) }}</li>
    </ul>
</p>
