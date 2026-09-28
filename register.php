<?php
require_once __DIR__ . '/includes/auth.php';
if (current_user()) { header('Location: dashboard.php'); exit; }
$selectedRole = 'employee';
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $department = trim($_POST['department'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $selectedRole = 'employee';
    $password = $_POST['password'] ?? '';
    if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter a valid name, email, and password of at least 8 characters.';
    } else {
        try {
            $statement = db()->prepare('INSERT INTO users (name, email, password, role, department, title) VALUES (?, ?, ?, ?, ?, ?)');
            $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $selectedRole, $department, $title]);
            flash('success', 'Your account is ready. Sign in to enter your workspace.');
            header('Location: login.php?role=' . urlencode($selectedRole)); exit;
        } catch (PDOException $exception) {
            $error = $exception->getCode() === '23000' ? 'An account with that email already exists.' : 'Could not create your account. Check the database setup.';
        }
    }
}
$pageTitle = 'Join the team';
include __DIR__ . '/includes/header.php';
?>
<section class="form-layout"><div class="form-aside form-aside-warm"><p class="eyebrow">Start here</p><h1>Bring your<br><em>whole self.</em></h1><p>Every good collaboration starts with a hello. Tell us a little about yourself.</p></div><div class="form-panel"><h2>Create your account</h2><p class="muted">Create your employee account.</p><?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><form method="post"><div class="form-row"><label>Full name<input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" autocomplete="name" required></label><label>Work email<input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email" required></label></div><div class="form-row"><label>Department<input type="text" name="department" value="<?= e($_POST['department'] ?? '') ?>" placeholder="e.g. Product" required></label><label>Job title<input type="text" name="title" value="<?= e($_POST['title'] ?? '') ?>" placeholder="e.g. Designer" required></label></div><label>Password<input type="password" name="password" minlength="8" autocomplete="new-password" required><small>Use at least 8 characters.</small></label><button class="button button-dark button-full" type="submit">Create account <span>→</span></button></form><p class="form-foot">Already have an account? <a href="login.php?role=<?= e($selectedRole) ?>">Sign in</a></p></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>