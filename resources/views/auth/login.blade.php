<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <script src="{{ asset('js/login.js') }}" defer></script>
</head>
<body>
  <main class="login-wrap">
    <section class="card login-card">
      <h1 class="title">Ingreso</h1>
      <form id="loginForm">
        <label for="email" class="label">Email</label>
        <input id="email" class="input" type="email" placeholder="admin@example.com" value="admin@example.com" required />
        <label for="password" class="label">Contraseña</label>
        <input id="password" class="input" type="password" placeholder="••••••" value="admin123" required />
        <button id="btnLogin" class="btn btn-primary w-100" type="submit">Entrar</button>
        <div id="err" class="alert alert-error" role="alert" hidden></div>
        <p class="muted">Autenticación contra la API REST. El token se guarda en localStorage.</p>
      </form>
    </section>
  </main>
</body>
</html>
