<?php include 'session.php'; ?>
<?php
include 'config.php';

$user_id = $_SESSION['user_id']; // Only show tasks for the logged-in user

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$valid_columns = ['id', 'title', 'description', 'category', 'priority', 'status', 'due_date'];
$sort_by = $_GET['sort_by'] ?? 'due_date';
$sort_order = $_GET['sort_order'] ?? 'asc';

$sort_by = in_array($sort_by, $valid_columns) ? $sort_by : 'due_date';
$sort_order = strtolower($sort_order) === 'desc' ? 'DESC' : 'ASC';
$next_order = $sort_order === 'ASC' ? 'desc' : 'asc';

$search_query = "%$search%";

$sql = "SELECT * FROM tasks 
        WHERE user_id = ? AND (title LIKE ? OR description LIKE ? OR category LIKE ?) 
        ORDER BY $sort_by $sort_order 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssii", $user_id, $search_query, $search_query, $search_query, $limit, $offset);

$stmt->execute();
$result = $stmt->get_result();

$count_stmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND (title LIKE ? OR description LIKE ? OR category LIKE ?)");
$count_stmt->bind_param("isss", $user_id, $search_query, $search_query, $search_query);
$count_stmt->execute();
$count_stmt->bind_result($total_count);
$count_stmt->fetch();
$count_stmt->close();

$total_pages = ceil($total_count / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .sortable a {
            color: inherit;
            text-decoration: none;
        }
        .sortable a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4 flex-grow-1 d-flex flex-column">
  <div class="task-header d-flex justify-content-between align-items-center mb-4">
    <h2>📋 Tasks List</h2>
    <div>
      <a href="add.php" class="btn btn-success">➕ Add Task</a>
      <a href="export_pdf.php" class="btn btn-outline-danger">🖨 Export PDF</a>
      <a href="export_excel.php" class="btn btn-outline-success">📊 Export Excel</a>
    </div>
  </div>
  <form method="GET" class="d-flex mb-4">
    <input type="text" name="search" class="form-control me-2" placeholder="🔍 Search..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-primary">Search</button>
  </form>
  <div class="table-responsive mb-4">
    <table class="table table-striped table-bordered shadow-sm">
      <thead class="table-dark">
        <tr>
        <?php
            foreach ($valid_columns as $col) {
                $is_sorted = $sort_by === $col;
                $arrow = $is_sorted
                    ? ($sort_order === 'ASC' ? '▲' : '▼')
                    : '⬍';
                echo "<th class='sortable'>
                        <a href='?search=" . urlencode($search) . "&sort_by=$col&sort_order=" . ($is_sorted ? $next_order : 'asc') . "'>
                            " . ucfirst($col) . " $arrow
                        </a>
                    </th>";
            }
        ?>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr class="<?= (new DateTime($row['due_date']) < new DateTime() && $row['status'] !== 'Completed') ? 'table-danger' : '' ?>">
            <?php foreach ($valid_columns as $col): ?>
              <?php if ($col === 'priority'): ?>
                <td>
                  <span class="badge <?= $row['priority'] === 'High' ? 'bg-danger' : ($row['priority'] === 'Medium' ? 'bg-warning text-dark' : 'bg-success') ?>">
                      <?= $row['priority'] ?>
                  </span>
                </td>
              <?php elseif ($col === 'status'): ?>
                <td>
                  <span class="badge <?= $row['status'] === 'Completed' ? 'bg-success' : 'bg-warning text-dark' ?>">
                      <?= $row['status'] ?>
                  </span>
                </td>
              <?php else: ?>
                <td><?= htmlspecialchars($row[$col]) ?></td>
              <?php endif; ?>
            <?php endforeach; ?>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete task?')">🗑 Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?search=<?= urlencode($search) ?>&sort_by=<?= $sort_by ?>&sort_order=<?= strtolower($sort_order) ?>&page=<?= $i ?>"> <?= $i ?> </a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
