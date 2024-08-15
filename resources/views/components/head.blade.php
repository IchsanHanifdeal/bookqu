<head lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta property="og:url" content="https://...">
    <meta property="og:type" content="website">
    <meta property="og:title" content="....">
    <meta property="og:description" content="....">
    <meta property="og:image:width" content="470">
    <meta property="og:image:height" content="470">

    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="...">
    <meta property="twitter:url" content="https://...">
    <meta name="twitter:title" content="....">
    <meta name="twitter:description" content="....">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>{{ $title ?? 'Login' }} | {{ ucfirst(config('app.name')) }} </title>
    <meta name="description" content="....">
    @vite('resources/css/app.css')
</head>
