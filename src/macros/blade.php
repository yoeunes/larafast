<?php

/**
 * get title
 */
Blade::directive('title', function ($expression) {
    return "<?php \$title = $expression ?>";
});

/**
 * Laravel dd() function.
 *
 * Usage: @dd($variableToDump)
 */
Blade::directive('dd', function ($expression) {
    return "<?php dd(with{$expression}); ?>";
});

/**
 * php explode() function.
 *
 * Usage: @explode($delimiter, $string)
 */
Blade::directive('explode', function ($argumentString) {
    list($delimiter, $string) = $this->getArguments($argumentString);

    return "<?php echo explode({$delimiter}, {$string}); ?>";
});

/**
 * php implode() function.
 *
 * Usage: @implode($delimiter, $array)
 */
Blade::directive('implode', function ($argumentString) {
    list($delimiter, $array) = $this->getArguments($argumentString);

    return "<?php echo implode({$delimiter}, {$array}); ?>";
});

/**
 * php var_dump() function.
 *
 * Usage: @var_dump($variableToDump)
 */
Blade::directive('varDump', function ($expression) {
    return "<?php var_dump(with{$expression}); ?>";
});

/**
 * Set variable.
 *
 * Usage: @set($name, value)
 */
Blade::directive('set', function ($argumentString) {
    list($name, $value) = $this->getArguments($argumentString);

    return "<?php {$name} = {$value}; ?>";
});

/**
 * check the environment
 */
Blade::if('env', function ($environment) {
    return app()->environment($environment);
});
