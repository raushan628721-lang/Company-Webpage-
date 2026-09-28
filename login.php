<?php
require_once __DIR__ . '/includes/auth.php';
if (current_user()) { header('Location: dashboard.php'); exit; }
$selectedRole = in_array($_GET['role'] ?? $_POST['role'] ?? 'employee', ['admin', 'employee'], true) ? ($_GET['role'] ?? $_POST['role'] ?? 'employee') : 'employee';
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $selectedRole = in_array($_POST['role'] ?? 'employee', ['admin', 'employee'], true) ? $_POST['role'] : 'employee';
    try {
        $statement = db()->prepare('SELECT id, name, email, role, department, title, phone, password FROM users WHERE email = ? AND role = ? LIMIT 1');
        $statement->execute([$email, $selectedRole]);
        $account = $statement->fetch();
        if ($account && password_verify($password, $account['password'])) {
            unset($account['password']);
            $_SESSION['user'] = $account;
            header('Location: dashboard.php');
            exit;
        }
        $error = 'That email and password combination is not recognized for the selected role.';
    } catch (PDOException $exception) {
        $error = 'Database connection failed. Create the database with database/schema.sql first.';
    }
}
$pageTitle = 'Sign in';
include __DIR__ . '/includes/header.php';
?>
<section class="form-layout"><div class="form-aside"><p class="eyebrow">Welcome back</p><h1>Your work,<br><em>in focus.</em></h1><p>Sign in to your NextGen Digital workspace and connect with your team.</p></div><div class="form-panel"><h2>Sign in</h2><p class="muted">Use your company account to continue.</p><?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><form method="post"><label>Account type<select name="role"><option value="employee" <?= $selectedRole === 'employee' ? 'selected' : '' ?>>Employee</option><option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin</option></select></label><label>Email address<input type="email" name="email" autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="button button-dark button-full" type="submit">Enter workspace <span>→</span></button></form><p class="form-foot">New to NextGen Digital? <a href="register.php?role=<?= e($selectedRole) ?>">Create an account</a></p></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>