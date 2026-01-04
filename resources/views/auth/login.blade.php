<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surma Filling Station</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('dashboard/assets/images/logo.png') }}">

    <style>
        /* Body & Background */
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(29, 53, 87, 0.7), rgba(247, 127, 0, 0.3)),
                        url('{{ asset('dashboard/assets/images/petrol_pump.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Glassmorphism Container */
        .login-container {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 50px 35px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        /* Logo */
        .login-logo {
            font-size: 28px;
            font-weight: 700;
            color: #f77f00;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-logo span {
            color: #ffffff;
            font-weight: 600;
        }

        .login-logo sup {
            color: orange;
            font-size: 12px;
        }

        hr {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Form Inputs */
        .form-label {
            font-weight: 600;
            color: #fff;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: none;
            background: rgba(255, 255, 255, 0.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.35);
            box-shadow: 0 0 0 3px rgba(247, 127, 0, 0.3);
            color: #fff;
        }

        .is-invalid {
            border-color: #ff4d4f !important;
        }

        .invalid-feedback {
            color: #ff4d4f;
        }

        /* Buttons */
        .btn-primary {
            background: #f77f00;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #ff9b3c;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                padding: 35px 25px;
            }

            .login-logo {
                font-size: 22px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="login-container text-center">
        <div class="login-logo mb-4">
            <img src="{{ asset('dashboard/assets/images/icon.png') }}" alt="logo" width="32">
            <span>Surma</span><span>Filling Station</span><sup>™</sup>
        </div>
        <hr>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label">Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" required
                    class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">SIGN IN</button>
        </form>
    </div>
</body>

</html>
