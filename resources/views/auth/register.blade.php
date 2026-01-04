
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surma Filling Station</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('dashboard/assets/images/logo.png') }}">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('dashboard/assets/images/petrol_pump.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 5px;
            width: 450px;
            height: 720px;
        }

        .login-logo {
            font-size: 28px;
            font-weight: bold;
            color: #f77f00;
        }

        .login-logo span {
            color: #1d3557;
        }

        .form-check-label {
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #1d3557;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-start align-items-center" style="height: 100%;">
        <div class="login-container">
            <div class="text-center mb-5">
                <div class="login-logo">
                    <img src="{{ asset('dashboard/assets/images/logo.png') }}" alt="logo" width="24">
                    <span style="color: #1d3557;">Surma</span> <span style="color: orange;">Filling
                        Station</span><sup>™</sup>
                </div>
                <hr>
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold" >Full Name</label>
                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" >Email address</label>
                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" >Phone</label>
                    <input type="file" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone"
                        placeholder="Phone" value="{{ old('phone') }}" required>
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password" name="password" required>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm Password</label>
                    <input type="password" id="password-confirm" class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Password Confirm" name="password_confirmation" required>
                    @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">SIGN UP</button>

                <div class="mb-3 form-check mb-5">
                    <p class="mt-3">Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign In</a></p>
                  
                </div>
            </form>
        </div>
    </div>
</body>

</html>


