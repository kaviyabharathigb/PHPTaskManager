<?php include 'session.php'; ?>
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id']; // 💡 Get logged-in user ID

    // Save with user_id
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, category, priority, status, due_date, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $category, $priority, $status, $due_date, $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h2 class="text-center">Add Task</h2>
    <form method="POST" class="p-4 border rounded bg-light">
        <label class="form-label">Title:</label>
        <input type="text" name="title" class="form-control" required>

        <label class="form-label mt-2">Description:</label>
        <textarea name="description" class="form-control" required></textarea>

        <label class="form-label mt-2">Category:</label>
        <input type="text" name="category" class="form-control" required>

        <label class="form-label mt-2">Priority:</label>
        <select name="priority" class="form-select">
            <option value="Low">Low</option>
            <option value="Medium" selected>Medium</option>
            <option value="High">High</option>
        </select>

        <label class="form-label mt-2">Status:</label>
        <select name="status" class="form-select">
            <option value="Pending" selected>Pending</option>
            <option value="Completed">Completed</option>
        </select>

        <label class="form-label mt-2">Due Date:</label>
        <input type="date" name="due_date" class="form-control" required>

        <button type="submit" class="btn btn-primary mt-3">Add Task</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
