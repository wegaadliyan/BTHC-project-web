<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BHC') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            min-height: 100vh;
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
            gap: 8px;
        }

        .back-button i {
            font-size: 18px;
        }

        .auth-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .logo {
            margin-bottom: 40px;
            text-align: center;
        }

        .logo-img {
            width: 130px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .logo h2 {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-top: 10px;
        }

        .auth-card {
            background-color: #F5F1EB;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
        }

        .auth-card h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #D6CFB5;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #D6CFB5;
            border: none;
            border-radius: 8px;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #C6BFA5;
        }

        .auth-links {
            margin-top: 20px;
            text-align: center;
        }

        .auth-links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .auth-links a:hover {
            color: #333;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
    @yield('content')
</body>
</html>