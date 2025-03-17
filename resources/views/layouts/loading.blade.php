<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman dengan Loading</title>
  <!-- Memasukkan CSS umum -->
  <link href="{{ asset('dist/css/loading.css') }}" rel="stylesheet">
</head>
<body>

  <!-- Overlay Loading Spinner -->
  <div id="loading-overlay">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <!-- Konten halaman di sini -->
  @yield('content')

  <!-- Memasukkan JS umum -->
  <script src="{{ asset('js/loading.js') }}"></script>
</body>
</html>
