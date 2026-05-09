<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management Portal</title>
    <style>
        :root {
            --primary: #1877f2;
            --secondary: #42b72a;
            --accent: #f0f2f5;
            --text: #1c1e21;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--accent);
            color: var(--text);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background: white;
            width: 100%;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            width: 90%;
            max-width: 1100px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        h3 { margin-top: 0; }

        p { color: #606770; font-size: 0.95rem; line-height: 1.5; }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-reg { background-color: var(--primary); }
        .btn-log { background-color: var(--secondary); }
        .btn-card { background-color: #2c3e50; }

        .btn:hover { opacity: 0.9; }

        footer { margin-top: auto; padding: 20px; color: #90949c; font-size: 0.8rem; }
    </style>
</head>
<body>

<header>
    <h1>Project Portal</h1>
    <p>Manage your profile and generate professional name cards</p>
</header>

<div class="container">
    
    <div class="card">
        <div class="icon">📝</div>
        <h3>Create Account</h3>
        <p>Register a new account with your username, full name, and password. Data is secured in MariaDB.</p>
        <a href="register.html" class="btn btn-reg">Sign Up</a>
    </div>

    <div class="card">
        <div class="icon">🔑</div>
        <h3>Sign In</h3>
        <p>Access your existing account. We verify your credentials against our secure database records.</p>
        <a href="signin.html" class="btn btn-log">Login</a>
    </div>

    <div class="card">
        <div class="icon">🪪</div>
        <h3>Name Card</h3>
        <p>Enter your professional details to generate a beautiful, print-ready HTML/PHP business card.</p>
        <a href="inputform.html" class="btn btn-card">Generate Card</a>
    </div>

</div>

<footer>
    PHP 8.4 + MariaDB 11.8 + Nginx Server (Port 90)
</footer>

</body>
</html>
