<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | ' : '' }} {{ config('app.name') }}</title>
    <link href="{{ asset(config('larafast.assets_path').'/datatables/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('styles')
</head>
<body>
@yield('content')
<script src="{{ asset(config('larafast.assets_path').'/datatables/js/jquery.min.js') }}"></script>
<script src="{{ asset(config('larafast.assets_path').'/datatables/js/bootstrap.min.js') }}"></script>
<script>
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token() ]) !!};
</script>
{!! Toastr::render() !!}
@stack('scripts')
</body>
</html>
