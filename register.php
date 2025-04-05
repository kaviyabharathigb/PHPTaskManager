<?php
// register.php
include 'config.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $msg = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $msg = "Username already taken!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hash);
            $insert->execute();
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-register">
    <button onclick="toggleTheme()" class="btn btn-sm btn-light position-absolute top-0 end-0 m-3">🌓</button>
    <div class="form-container">
        <h2 class="text-center">Create Account</h2>
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
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <div class="text-center mt-3">
                <a href="login.php">Already have an account? Login</a>
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
