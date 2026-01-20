<?php
require_once('../Model/Auth.php');
require_once('../Model/UserModel.php');
start_session_safe();
require_role(['admin']);

$users = list_users();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
<div class="container">
  <h2>Manage Users</h2>
  <a class="btn" href="dashboard.php">Dashboard</a>

  <table class="table">
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Action</th></tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo htmlspecialchars($u['name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo htmlspecialchars($u['role']); ?></td>
        <td><?php echo ((int)$u['is_active']===1)?'Yes':'No'; ?></td>
        <td>
          <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
            <a href="../Controller/admin_toggle_user.php?id=<?php echo (int)$u['id']; ?>&to=<?php echo ((int)$u['is_active']===1)?0:1; ?>">
              Set <?php echo ((int)$u['is_active']===1)?'Inactive':'Active'; ?>
            </a>
          <?php else: ?>
            (you)
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
