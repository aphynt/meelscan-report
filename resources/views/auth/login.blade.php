<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/icon.png') }}">
    <title>Login | {{ config('app.name') }}</title>
    <link href="{{ asset('admin/dist') }}/assets/css/app.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/dist') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/dist') }}/assets/css/meelcount-modern.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @include('sweetalert2')

    <main class="mc-auth-shell">
        <section class="mc-auth-visual" aria-hidden="true">
            <div class="mc-auth-brand">
                <img src="{{ asset('logo/logo-full.png') }}" alt="SIMS Jaya Kaltim">
            </div>

            <div class="mc-auth-message">
                <div class="mc-auth-eyebrow"><i class="mdi mdi-silverware-fork-knife"></i> Meal Consumption & Attendance</div>
                <h1>Simple monitoring for better meal operations.</h1>
                <p>Track meal consumption, employee attendance, and Healthy Menu data in one clean internal system.</p>
            </div>

            <div class="mc-auth-footnote">PT SIMS Jaya Kaltim · Internal System</div>
        </section>

        <section class="mc-auth-form-panel">
            <div class="mc-auth-card">
                <div class="mc-auth-mobile-logo">
                    <img src="{{ asset('logo/logo-full.png') }}" alt="SIMS Jaya Kaltim">
                </div>

                <h2>Welcome back</h2>
                <p class="lead">Masuk menggunakan NIK dan kata sandi untuk melanjutkan ke {{ config('app.name') }}.</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3" style="font-size:12px;border-radius:10px">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" autocomplete="on">
                    @csrf
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <div class="mc-input-wrap">
                            <span class="mc-input-icon mdi mdi-account-outline"></span>
                            <input type="text" id="nik" name="nik" class="form-control" value="{{ old('nik') }}" placeholder="Masukkan NIK" autocomplete="username" autofocus required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="mc-input-wrap">
                            <span class="mc-input-icon mdi mdi-lock-outline"></span>
                            <input type="password" id="password" name="password" class="form-control pe-5" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                            <button type="button" class="mc-password-toggle" id="togglePassword" aria-label="Show password"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mc-auth-submit">
                        <i class="mdi mdi-login-variant"></i> Masuk ke Sistem
                    </button>
                </form>

                <div class="mc-auth-footer">© 2026 {{ config('app.name') }} · PT SIMS JAYA KALTIM</div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline';
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    </script>
</body>
</html>
