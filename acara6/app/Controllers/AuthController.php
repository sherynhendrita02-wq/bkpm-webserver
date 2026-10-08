<?php

class AuthController
{
    public function loginForm(): void
    {
        ?>

        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login</title>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body class="bg-light">

            <div class="container mt-5">

                <div class="row justify-content-center">

                    <div class="col-md-5">

                        <div class="card shadow-sm">

                            <div class="card-body p-4">

                                <h3 class="text-center mb-4">
                                    Login
                                </h3>

                                <?php

                                if (isset($_SESSION['flash'])) {
                                    echo '
        <div class="alert alert-warning">
            ' . $_SESSION['flash'] . '
        </div>
    ';

                                    unset($_SESSION['flash']);
                                }
                                ?>

                                <form method="POST" action="/acara6/public/login">

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Username
                                        </label>

                                        <input type="text" name="username" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Password
                                        </label>

                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        Login
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </body>

        </html>

        <?php
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin') {

            session_start();

            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['logged_in'] = true;

            header('Location: /acara6/public/dashboard');
            exit;
        }

        echo "Username atau password salah";
    }

    public function logout(): void
{
    $_SESSION = [];

    session_destroy();

    header('Location: /acara6/public/login?logout=success');
    exit;
}
}