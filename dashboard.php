<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$user = current_user();
$isAdmin = $user['role'] === 'admin';
$pageTitle = 'Workspace';
$people = [];
try {
    $query = $isAdmin ? 'SELECT name, email, department, title, phone FROM users WHERE role = "employee" ORDER BY name' : 'SELECT name, email, department, title, phone FROM users WHERE role = "admin" ORDER BY name';
    $people = db()->query($query)->fetchAll();
} catch (PDOException $exception) {
    $error = 'Connect your database to load team details.';
}
include __DIR__ . '/includes/header.php';
?>
<section class="dashboard-head"><div><p class="eyebrow">Personal workspace</p><h1>Good morning, <em><?= e(explode(' ', $user['name'])[0]) ?>.</em></h1><p class="muted">Here is the people and information you need today.</p></div><div class="role-badge"><span class="status-dot"></span><?= e(ucfirst($user['role'])) ?> account</div></section>
<section class="profile-banner"><div class="avatar"><?= e(strtoupper(substr($user['name'], 0, 1))) ?></div><div><p class="eyebrow">Your profile</p><h2><?= e($user['name']) ?></h2><p><?= e($user['title']) ?> · <?= e($user['department']) ?></p></div><a class="button button-light" href="logout.php">Sign out <span>↗</span></a></section>
<section class="people-section"><div class="section-heading"><div><p class="eyebrow">Directory</p><h2><?= $isAdmin ? 'Your team' : 'Leadership team' ?></h2></div><span class="count-label"><?= count($people) ?> <?= count($people) === 1 ? 'person' : 'people' ?></span></div><?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php elseif (!$people): ?><div class="empty-state">No directory records are available yet.</div><?php else: ?><div class="people-grid"><?php foreach ($people as $person): ?><article class="person-card"><div class="avatar avatar-small"><?= e(strtoupper(substr($person['name'], 0, 1))) ?></div><div class="person-info"><h3><?= e($person['name']) ?></h3><p><?= e($person['title']) ?></p><div class="person-meta"><span><?= e($person['department']) ?></span><a href="mailto:<?= e($person['email']) ?>"><?= e($person['email']) ?></a></div><?php if (!empty($person['phone'])): ?><small><?= e($person['phone']) ?></small><?php endif; ?></div></article><?php endforeach; ?></div><?php endif; ?></section>
<?php include __DIR__ . '/includes/footer.php'; ?>