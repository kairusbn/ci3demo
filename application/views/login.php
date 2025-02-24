<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Success/Error messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div style="color: green;"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div style="color: red;"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?php echo validation_errors(); // Display form validation errors ?>

    <?php echo form_open('auth/login'); ?>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>

    <p>Don't have an account? <a href="<?= site_url('auth/register'); ?>">Register here</a></p>
</body>
</html>
