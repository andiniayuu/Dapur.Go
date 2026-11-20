<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Dapur.Go</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            width: 380px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 5px 20px #00000012;
        }

        h2, p {
            text-align: center;
        }

        h2 {
            color: #315c42;
        }

        p {
            color: #777;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #315c42;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        a {
            color: #315c42;
        }

        .error {
            color: red;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Dapur.Go</h2>
    <p>Buat akun untuk memesan catering</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf

        <label>Nama Lengkap</label>
        <input type="text"
               name="name"
               value="{{ old('name') }}"
               placeholder="Nama lengkap"
               required>

        <label>Email</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="Email aktif"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Minimal 8 karakter"
               required>

        <label>Konfirmasi Password</label>
        <input type="password"
               name="password_confirmation"
               placeholder="Ulangi password"
               required>

        <button type="submit">Daftar</button>
    </form>

    <p>
        Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </p>

</div>

</body>
</html>