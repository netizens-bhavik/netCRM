<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <style>
        /* Include your custom SCSS styles directly here or compile them into app.css */
        .container {
            margin:auto;
            padding: auto;
            width: 400px;

        }

        .progress-bar-container {
            position: relative;
            margin-bottom: 30px;
        }

        .custom-progress-bar {
            counter-reset: step;
            padding-left: 0;
            list-style: none;
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .custom-progress-bar li {
            position: relative;
            text-align: center;
            width: 33%;
        }

        .custom-progress-bar li:before {
            content: counter(step);
            background-color: white;
            border: 2px solid #ccc;
            color: #ccc;
            display: block;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            line-height: 18px;
            margin: 0 auto;
            text-align: center;
            font-size: 12px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        .custom-progress-bar li:after {
            content: "";
            background-color: #e5e5e5;
            height: 3px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            z-index: -1;
        }

        .custom-progress-bar li.active:before {
            border-color: red;
            color: red;
        }

        .custom-progress-bar li.finished:before {
            background-color: red;
            border-color: red;
            color: #fff;
            content: "\2713";
        }

        .custom-progress-line {
            height: 3px;
            position: absolute;
            top: 50%;
            left: 0;
            background-color: red;
            z-index: -1;
            width: 33%; /* Adjust based on progress */
        }

        .horizontal-form-box {
            background-color: #fff;
            border: 1px solid #e5e5e5;
            padding: 30px;
            height: auto;
        }

        .horizontal-info-container img {
            height: 75px;
            margin-bottom: 20px;
        }

        .horizontal-heading {
            color: #000;
            font-size: 22px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .horizontal-subtitle {
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-align: left;
        }

        .horizontal-form-group {
            margin-bottom: 20px;
        }

        .horizontal-form-group label {
            display: block;
            margin-bottom: 5px;
            color: #000;
            font-weight: normal;
        }

        .horizontal-form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
        }

        .o3-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #484c7f;
            color: #fff;
            border: none;
            border-radius: 4px;
            text-transform: capitalize;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="horizontal-form-box">
            <div class="horizontal-info-container text-center">
                <img src="https://staphcrm.com/images/logo.svg" alt="Reset Password Icon"/>
                <p class="horizontal-heading">Reset your password</p>
                <p class="horizontal-subtitle">Your password needs to be at least 8 characters.</p>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="horizontal-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="horizontal-form-group">
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                    @if (isset($errors['email']))
                        <span>{{ $errors['email'][0] }}</span>
                    @endif
                </div>

                <div class="horizontal-form-group">
                    <label for="password">New Password:</label>
                    <input id="password" type="password" name="password" required>
                    @if (isset($errors['password']))
                        <span>{{ $errors['password'][0] }}</span>
                    @endif
                </div>

                <div class="horizontal-form-group">
                    <label for="password_confirmation">Confirm Password:</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                    @if (isset($errors['password_confirmation']))
                        <span>{{ $errors['password_confirmation'][0] }}</span>
                    @endif
                </div>

                <button type="submit" class="o3-btn">Set New Password</button>
            </form>
        </div>
    </div>
</body>
</html>
