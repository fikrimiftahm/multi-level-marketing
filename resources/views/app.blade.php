<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'API Gateway Partner') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @routes

    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" />

    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
