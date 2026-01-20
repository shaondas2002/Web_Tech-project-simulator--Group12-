<?php
require_once('../Model/Auth.php');
require_once('../Model/UserModel.php');
start_session_safe();
require_role(['admin']);

$id = (int)($_GET['id'] ?? 0);
$to = (int)($_GET['to'] ?? 1);
if ($id > 0 && $id !== (int)$_SESSION['user_id']) {
    set_user_active($id, $to ? 1 : 0);
}
header('Location: ../View/admin_users.php');
exit;
?>
