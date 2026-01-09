<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @if ($title !== '')
  <title>{{ $title }} &ndash; Sistem Jastip</title>
  @else
  <title>Sistem Jastip</title>
  @endif

  <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  {{ $style }}
</head>

<body>
  <div id="id">
    <x-sidebar></x-sidebar>
    <div id="main">
      <x-header></x-header>
      {{ $slot }}
      <x-footer></x-footer>
    </div>
  </div>


  <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>

  <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/axios/axios.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/cleave/cleave.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/datatables.net-bs5/js/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
  <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
  <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

  {{-- Custom JS --}}
  <script src="{{ asset('js/app.js') }}"></script>

  {{ $script }}
</body>

</html>