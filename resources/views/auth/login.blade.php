<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dapur.Go</title>

    <style>
        * {
            box-sizing: border-box;
        }

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

        h2 {
            text-align: center;
            color: #315c42;
        }

        p {
            text-align: center;
            color: #777;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid #ddd;
            border-radius: 7px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #315c42;
            color: white;
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
    <p>Silakan login ke akun Anda</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="Masukkan email"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Masukkan password"
               required>

        <button type="submit">Login</button>
    </form>

    <p>
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>

</div>

</body>
</html>