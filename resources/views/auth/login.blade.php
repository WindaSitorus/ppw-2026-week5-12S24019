<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIPUS-Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 2rem; background: #fff; }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="fw-bold text-center text-dark mb-4">SIPUS-Del</h3>
        
        @if($errors->any())
            <div class="alert alert-danger py-2 border-0 shadow-sm">
                <small>{{ $errors->first() }}</small>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-muted fw-medium small">Email Akses</label>
                <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="Contoh: windasitorus0197@gmail.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted fw-medium small">Kata Sandi</label>
                <input type="password" name="password" class="form-control bg-light border-0 py-2" required>
            </div>
            <button type="submit" class="btn btn-dark w-100 py-2 fw-medium">Masuk ke Dasbor</button>
        </form>
    </div>
</body>
</html>
