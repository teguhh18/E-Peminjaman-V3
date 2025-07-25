<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>{{ $title }} | E-Peminjaman</title>

    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin"
        rel="stylesheet" type="text/css">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('TemplatePixel/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('TemplatePixel/css/pixeladmin.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('TemplatePixel/css/widgets.min.css') }}" rel="stylesheet" type="text/css">

    {{-- full calender --}}
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />


    <!-- Theme -->
    <link href="{{ asset('TemplatePixel/css/themes/silver.min.css') }}" rel="stylesheet" type="text/css">

    <!-- holder.js -->
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/holder/2.9.0/holder.js"></script>

    <!-- Pace.js -->
    <script src="{{ asset('TemplatePixel/pace/pace.min.js') }}"></script>

    <script src="{{ asset('TemplatePixel/demo/demo.js') }}"></script>

    <!-- Custom styling -->
    <style>
        .page-header-form .input-group-addon,
        .page-header-form .form-control {
            background: rgba(0, 0, 0, .05);
        }
    </style>
    <!-- / Custom styling -->
</head>

<body>
