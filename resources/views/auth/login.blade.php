<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }

        #auth {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        #auth-left {
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 2rem;
        }

        .auth-logo img {
            width: 80px;
            height: auto;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .form-control-xl {
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1.1rem;
            border-radius: 0.75rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control-xl:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .form-check-label {
            font-size: 1rem;
            color: #666;
        }

        #auth-right {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        #auth-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><circle cx="200" cy="200" r="100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><circle cx="200" cy="200" r="150" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></svg>') center/cover;
        }

        @media (max-width: 991.98px) {
            #auth-left {
                margin: 1rem;
                border-radius: 1rem;
            }
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="container-fluid">
            <div class="row h-100">
                <div class="col-lg-5 col-12 d-flex align-items-center">
                    <div id="auth-left" class="w-100">
                        <div class="auth-logo text-center">
                            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23667eea'/><text x='50' y='60' font-family='Arial' font-size='30' fill='white' text-anchor='middle'>A</text></svg>" alt="Logo">
                        </div>
                        <h1 class="auth-title text-center">Masuk</h1>
                        <p class="auth-subtitle text-center mb-5">Masukkan username dan Password anda untuk masuk!</p>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="text" class="form-control form-control-xl" placeholder="Email" name="email" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                            
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="password" class="form-control form-control-xl" placeholder="Password" name="password" id="password" required>
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            
                            <div class="form-check form-check-lg d-flex align-items-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                    Biarkan saya tetap masuk
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block w-100 btn-lg shadow-lg mt-4">
                                Masuk
                            </button>
                        </form>
                        
                        <!-- Uncomment if you want to add registration and forgot password links
                        <div class="text-center mt-5">
                            <p class="text-muted">Belum punya akun? <a href="auth-register.html" class="fw-bold text-decoration-none">Daftar</a></p>
                            <p><a class="fw-bold text-decoration-none" href="auth-forgot-password.html">Lupa password?</a></p>
                        </div>
                        -->
                    </div>
                </div>
                
                <div class="col-lg-7 d-none d-lg-block">
                    <div id="auth-right">
                        <!-- Background design area -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password visibility toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (togglePassword && password && toggleIcon) {
                togglePassword.addEventListener('click', function() {
                    // Toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // Toggle the eye icon
                    if (type === 'password') {
                        toggleIcon.classList.remove('bi-eye-slash');
                        toggleIcon.classList.add('bi-eye');
                    } else {
                        toggleIcon.classList.remove('bi-eye');
                        toggleIcon.classList.add('bi-eye-slash');
                    }
                });
            }
        });
    </script>
</body>
</html>