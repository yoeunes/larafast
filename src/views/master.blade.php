<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | ' : '' }} {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset(config('larafast.path.assets').'/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(config('larafast.path.assets').'/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset(config('larafast.path.assets').'/font-awesome/css/font-awesome.min.css') }}">
    @stack('styles')
</head>
<body>
@yield('content')
<script src="{{ asset(config('larafast.path.assets').'/jquery/jquery.min.js') }}"></script>
<script src="{{ asset(config('larafast.path.assets').'/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token() ]) !!};
</script>
<script src="{{ asset(config('larafast.path.assets').'/toastr/toastr.min.js') }}"></script>
{!! app('toastr')->render() !!}
@stack('scripts')
</body>
</html>
