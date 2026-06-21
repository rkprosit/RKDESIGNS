<?php session_start(); if (isset($_SESSION['admin'])) header('Location: index.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | RK DESIGNS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #111; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
    .login-box { background: #1a1a1a; padding: 40px; width: 360px; border: 1px solid #333; }
    h1 { color: #fff; font-weight: 400; font-size: 22px; text-align: center; margin-bottom: 5px; }
    h1 span { color: #c8a165; }
    p { color: #888; text-align: center; font-size: 13px; margin-bottom: 30px; }
    input { width: 100%; padding: 12px 14px; margin-bottom: 15px; background: #111; border: 1px solid #333; color: #fff; font-size: 14px; outline: none; font-family: 'Inter', sans-serif; }
    input:focus { border-color: #c8a165; }
    .btn { width: 100%; padding: 12px; background: #c8a165; color: #fff; border: none; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; font-family: 'Inter', sans-serif; }
    .btn:hover { background: #b88d4e; }
    .error { color: #e74c3c; font-size: 13px; text-align: center; margin-bottom: 15px; display: none; }
  </style>
</head>
<body>
  <div class="login-box">
    <h1>RK<span>DESIGNS</span></h1>
    <p>Admin Login</p>
    <div class="error" id="error">Invalid credentials</div>
    <input type="text" id="username" placeholder="Username" autocomplete="off">
    <input type="password" id="password" placeholder="Password">
    <button class="btn" onclick="login()">Sign In</button>
  </div>
  <script>
    async function login() {
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const res = await fetch('../api/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();
      if (data.success) { window.location.href = 'index.php'; }
      else { document.getElementById('error').style.display = 'block'; }
    }
    document.addEventListener('keydown', e => { if (e.key === 'Enter') login(); });
  </script>
</body>
</html>
