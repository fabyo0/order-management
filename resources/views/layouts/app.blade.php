<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Order Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Select2 Css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>

    <!-- Pikaday -->
    <link href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Css --}}
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>


<x-toaster-hub/>

<!-- Sweet Alert Confirm -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Jquery -->
<script defer src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>

<!-- Select2 js-->
<script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  document.addEventListener('livewire:init', () => {
        Livewire.on('swal:confirm', (event) => {
            swal.fire({
                title: event[0].title,
                text: event[0].text,
                icon: event[0].type,
                showCancelButton: true,
                confirmButtonColor: 'rgb(239 68 6)',
                confirmButtonText: 'Yes, delete it!'
            })
                .then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        Livewire.dispatch(event[0].method, { id: event[0].id });
                    }
                });
        })
    });
</script>

{{--  Livewire Js  --}}
@livewireScripts

@stack('js')
</body>
</html>
