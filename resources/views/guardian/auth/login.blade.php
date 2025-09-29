<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Login Wali Murid' }} - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        .login-container {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            width: 100vw;
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 25%, #06b6d4 50%, #0891b2 75%, #0d9488 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 15%;
            left: 10%;
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 25%;
            right: 15%;
            width: 110px;
            height: 110px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 25%;
            left: 20%;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(45deg);
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            bottom: 35%;
            right: 25%;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50% 0 50% 0;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-card-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 32px;
            box-shadow:
                0 32px 64px rgba(0, 0, 0, 0.15),
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
            min-height: 650px;
            position: relative;
            z-index: 1;
            animation: slideUp 1s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .welcome-section {
            flex: 1.2;
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 30%, #06b6d4 70%, #0891b2 100%);
            padding: 70px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 1px,
                rgba(255, 255, 255, 0.03) 1px,
                rgba(255, 255, 255, 0.03) 2px
            );
            animation: movePattern 30s linear infinite;
        }

        @keyframes movePattern {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .school-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
            animation: logoFloat 4s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-10px) scale(1.05); }
        }

        .logo-icon {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .logo-icon img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .welcome-title {
            color: white;
            font-size: 48px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
            font-weight: 400;
        }

        .view-more-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
            width: fit-content;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .view-more-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .login-section {
            flex: 1;
            padding: 70px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-title {
            color: #1e293b;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 16px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .form-input {
            width: 100%;
            padding: 18px 24px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            outline: none;
            font-family: inherit;
            font-weight: 500;
        }

        .form-input:focus {
            border-color: #14b8a6;
            background: rgba(255, 255, 255, 0.95);
            box-shadow:
                0 0 0 4px rgba(20, 184, 166, 0.1),
                0 10px 30px rgba(20, 184, 166, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #9ca3af;
            font-size: 15px;
            font-weight: 400;
        }

        .form-input.error {
            border-color: #ef4444;
            background: rgba(254, 242, 242, 0.9);
        }

        .input-error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
            animation: shake 0.5s ease-in-out;
            font-weight: 500;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .login-button {
            width: 100%;
            background: linear-gradient(135deg, #14b8a6 0%, #0f766e 50%, #0d9488 100%);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow:
                0 10px 30px rgba(20, 184, 166, 0.3),
                0 4px 15px rgba(20, 184, 166, 0.2);
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow:
                0 15px 40px rgba(20, 184, 166, 0.4),
                0 8px 25px rgba(20, 184, 166, 0.3);
        }

        .login-button:active {
            transform: translateY(-1px);
        }

        .help-section {
            text-align: center;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
        }

        .help-link {
            background: linear-gradient(135deg, #14b8a6 0%, #0f766e 50%, #0d9488 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            display: inline-block;
            margin-top: 12px;
            transition: all 0.4s ease;
            box-shadow: 0 6px 20px rgba(20, 184, 166, 0.3);
        }

        .help-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(20, 184, 166, 0.4);
        }

        .phone-help {
            background: rgba(255, 247, 237, 1);
            border: 2px solid rgba(251, 191, 36, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #92400e;
            font-weight: 500;
        }

        .phone-help strong {
            color: #78350f;
        }

        @media (max-width: 968px) {
            .login-card-wrapper {
                flex-direction: column;
                max-width: 500px;
                min-height: auto;
            }

            .welcome-section {
                padding: 50px 40px;
                text-align: center;
            }

            .welcome-title {
                font-size: 36px;
            }

            .login-section {
                padding: 50px 40px;
            }
        }

        @media (max-width: 640px) {
            .login-container {
                padding: 10px;
            }

            .login-card-wrapper {
                max-width: 100%;
                border-radius: 24px;
            }

            .welcome-section,
            .login-section {
                padding: 30px 25px;
            }

            .welcome-title {
                font-size: 28px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <div class="login-card-wrapper">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="school-logo">
                    <div class="logo-icon">
                        <img src="{{ asset('images/logo-smk-mahardhika.png') }}" alt="SMK Mahardhika Logo">
                    </div>
                </div>
                <h1 class="welcome-title">Portal<br>Wali Murid</h1>
                <p class="welcome-subtitle">Sistem Monitoring Kehadiran Siswa SMK Mahardhika Surabaya. Pantau aktivitas belajar putra/putri Anda secara real-time melalui platform digital yang terintegrasi.</p>
                <a href="#" class="view-more-btn">Selengkapnya</a>
            </div>

            <!-- Login Section -->
            <div class="login-section">
                <div class="login-header">
                    <h2 class="login-title">Panel Wali Murid<br>SMK MAHARDHIKA</h2>
                    <p class="login-subtitle">Masuk dengan Nomor WhatsApp Anda</p>
                </div>

                <div class="phone-help">
                    <strong>Format Nomor WhatsApp:</strong><br>
                    Gunakan kode negara 62, contoh: <strong>6281234567890</strong><br>
                    (Tanpa tanda + atau 0 di depan)
                </div>

                <form action="{{ route('guardian.login') }}" method="POST">
                    @csrf

                    <!-- Nomor WhatsApp -->
                    <div class="form-group">
                        <label for="guardian_phone" class="form-label">Nomor WhatsApp</label>
                        <input id="guardian_phone"
                               class="form-input @error('guardian_phone') error @enderror"
                               type="text"
                               name="guardian_phone"
                               value="{{ old('guardian_phone') }}"
                               placeholder="Contoh: 6281234567890"
                               required
                               autofocus />
                        @error('guardian_phone')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password"
                               class="form-input"
                               type="password"
                               name="password"
                               placeholder="••••••••••••"
                               required />
                    </div>

                    <button type="submit" class="login-button">
                        Masuk
                    </button>

                    <div class="help-section">
                        <p>Butuh bantuan atau lupa password?</p>
                        <a href="#" class="help-link">Hubungi Admin Sekolah</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
