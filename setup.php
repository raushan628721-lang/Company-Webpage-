<?php
require_once __DIR__ . '/includes/auth.php';
$message = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        db()->exec('CREATE TABLE IF NOT EXISTS users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, role ENUM("admin", "employee") NOT NULL DEFAULT "employee", department VARCHAR(100) NOT NULL, title VARCHAR(120) NOT NULL, phone VARCHAR(30) DEFAULT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $insert = db()->prepare('INSERT INTO users (name, email, password, role, department, title, phone) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), password = VALUES(password), role = VALUES(role), department = VALUES(department), title = VALUES(title), phone = VALUES(phone)');
        $insert->execute(['Avery Morgan', 'admin@nextgendigital.test', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'Operations', 'Chief Operating Officer', '+1 555 014 2020']);
        $insert->execute(['Sam Rivera', 'employee@nextgendigital.test', password_hash('employee123', PASSWORD_DEFAULT), 'employee', 'Product', 'Product Designer', '+1 555 014 2021']);
        $message = 'Setup complete. Admin: admin@nextgendigital.test / admin123. Employee: employee@nextgendigital.test / employee123.';
    } catch (PDOException $exception) { $error = 'Could not connect. Create the database first and check config/database.php.'; }
}
$pageTitle = 'Database setup';
include __DIR__ . '/includes/header.php';
?><section class="form-layout setup-layout"><div class="form-aside"><p class="eyebrow">One-time setup</p><h1>Set up<br><em>NextGen Digital.</em></h1><p>This creates the users table and two demo accounts. Run it once after importing the database schema.</p></div><div class="form-panel"><h2>Initialize database</h2><p class="muted">Make sure MySQL is running, then continue.</p><?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><a class="button button-dark button-full" href="login.php">Go to sign in <span>→</span></a><?php else: ?><?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?><form method="post"><button class="button button-dark button-full" type="submit">Create demo accounts <span>→</span></button></form><?php endif; ?></div></section><?php include __DIR__ . '/includes/footer.php'; ?>