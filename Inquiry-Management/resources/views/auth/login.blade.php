<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign in</title>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>
<body>
  <div class="page">

    <!-- Logo -->
    <div class="logo-wrap">
      <img
        src="https://synthesisbikaner.org/synthesistest/assets/background%20logo.png"
        alt="logo"
        class="logo"
      />
    </div>

    <!-- Errors -->
    @if ($errors->any())
      <div class="alert">
        <ul style="margin:0; padding-left: 18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Card -->
    <form method="POST" action="{{ route('login.post') }}" class="card" novalidate>
      @csrf

      <h3 class="title">Login</h3>

      <div class="form-grid">
        <label for="email">Email</label>
        <input id="email"
               type="email"
               name="email"
               class="input"
               placeholder="Enter Your Email"
               value="{{ old('email') }}"
               required
               autocomplete="email">
               
        <label for="password">Password</label>
        <input id="password"
               type="password"
               name="password"
               class="input input-blue"
               placeholder="Enter Your Password"
               required
               autocomplete="current-password">
      </div>

      <button class="btn" type="submit">Continue</button>
    </form>

  </div>
</body>
</html>
