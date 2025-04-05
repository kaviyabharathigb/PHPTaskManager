<?php include 'session.php'; ?>
<?php
include 'config.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tasks WHERE id=$id");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, category=?, priority=?, status=?, due_date=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $description, $category, $priority, $status, $due_date, $id);
    $stmt->execute();

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h2 class="text-center">Edit Task</h2>
    <form method="POST" class="p-4 border rounded bg-light">
        <label class="form-label">Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control" required>

        <label class="form-label mt-2">Description:</label>
        <textarea name="description" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>

        <label class="form-label mt-2">Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>" class="form-control" required>

        <label class="form-label mt-2">Priority:</label>
        <select name="priority" class="form-select">
            <option value="Low" <?= $row['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
            <option value="Medium" <?= $row['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
            <option value="High" <?= $row['priority'] === 'High' ? 'selected' : '' ?>>High</option>
        </select>

        <label class="form-label mt-2">Status:</label>
        <select name="status" class="form-select">
            <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <label class="form-label mt-2">Due Date:</label>
        <input type="date" name="due_date" value="<?= $row['due_date'] ?>" class="form-control" required>

        <button type="submit" class="btn btn-primary mt-3">Update Task</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
