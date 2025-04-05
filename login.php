    <?php
    // login.php
    session_start();
    include 'config.php';

    $msg = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Fetch user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id']; // ✅ SET USER ID for filtering tasks
                header("Location: index.php");
                exit();
            } else {
                $msg = "Invalid password.";
            }
        } else {
            $msg = "User not found.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body>
        <div class="login-register">
            <button onclick="toggleTheme()" class="btn btn-sm btn-light position-absolute top-0 end-0 m-3">🌓</button>
            <div class="form-container">
                <h2 class="text-center">Welcome Back</h2>
                <?php if ($msg): ?>
                    <div class="alert alert-danger text-center"> <?= $msg ?> </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <div class="text-center mt-3">
                        <a href="register.php">Don't have an account? Register</a>
                    </div>
                </form>
            </div>
        </div>
        <script>
        function toggleTheme() {
            const body = document.body;
            body.classList.toggle("dark-mode");
            localStorage.setItem("darkMode", body.classList.contains("dark-mode"));
        }
        window.onload = () => {
            if (localStorage.getItem("darkMode") === "true") {
                document.body.classList.add("dark-mode");
            }
        };
        </script>
    </body>
    </html>
