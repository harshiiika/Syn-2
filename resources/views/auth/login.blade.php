<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
 
<body style="background-image: url('{{ asset('images/motion graphic background_12.0de2e3ff0dd28bfa.png') }}'); background-size: cover;">
    <div class="signin">
 
        <div class="container">

                <img src="{{ asset('images/background logo.png') }}" alt="logo"
                    class="logo">

<!-- //form to submit login credentials -->
<form method="POST" action="{{ route('login.submit') }}" class="card">
    @csrf
    <h3>Login</h3><br>
    <div class="login-form">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" class="username" placeholder="Enter Your Email" required><br>
        <label for="password">Password</label>

<div class="password-wrapper">
    <input id="password" type="password" name="password"
           placeholder="Enter Your Password"
           required>

    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
</div>

        
    </div>  
                  <!-- display validation errors -->

                   @if ($errors->any())
    <div style="color: red;">
        <ul>

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<<<<<<< HEAD
<!-- form to submit login credentials -->
<form method="POST" action="{{ route('login.submit') }}" class="card">
    @csrf
    <h3>Login</h3><br>
    <div class="login-form">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" class="username" placeholder="Enter Your Email" required><br>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" class="username" placeholder="Enter Your Password" required><br>
    </div>
=======
>>>>>>> e0a20c03d227abca7acfad29709396e5c461146b
    <button class="btn" type="submit">Continue</button>
</form>

</div>
    </div>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
 