<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPUS-Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-sipus { background-color: #4C1D95; }
        .text-purple { color: #4C1D95; }
        .btn-purple { background-color: #4C1D95; color: white; }
        .btn-purple:hover { background-color: #3B0764; color: white; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sipus mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('buku.index') }}">SIPUS-Del</a>
        </div>
    </nav>
    <div class="container pb-5">
        {{ $slot }}
    </div>
</body>
</html>