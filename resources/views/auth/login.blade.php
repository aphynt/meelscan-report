<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('logo/icon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Meelcount</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #001932;
            /* primary background */
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 18px;
            padding: 32px 28px;
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #001932;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .logo h2 {
            margin: 0;
            color: #001932;
            font-size: 22px;
        }

        .logo p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #777;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: #001932;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #ccd4db;
            outline: none;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #001932;
            box-shadow: 0 0 0 3px rgba(0, 25, 50, 0.15);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: #001932;
            /* primary button */
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.1s, background 0.2s;
        }

        .login-btn:hover {
            background: #00284d;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .footer-text {
            margin-top: 22px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

    </style>
</head>

<body>
    <div class="login-card">

        <div class="logo">
            <img src="{{ asset('logo/logo-full.png') }}" alt="" width="200px">
            <h2>{{ config('app.name') }}</h2>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan NIK" required />
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required />
            </div>

            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="footer-text">
            © 2026 Meelcount. by IT
        </div>
    </div>
</body>

</html>
