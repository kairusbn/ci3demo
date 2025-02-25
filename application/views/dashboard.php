<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Redirect if user is not logged in
if (!$this->session->userdata('user_id')) {
    redirect('auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($this->session->userdata('username')); ?>!</h2>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user->username); ?></td>
                <td><?= htmlspecialchars($user->email); ?></td>
                <td><?= htmlspecialchars($user->created_at); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a class="logout-btn" href="<?= site_url('auth/logout'); ?>">Logout</a></p>
</body>
</html>
