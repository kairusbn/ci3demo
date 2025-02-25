<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <h2>Register</h2>

    <!-- Success/Error messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="success message"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error message"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?php echo validation_errors('<div class="error message">', '</div>'); ?>

    <?php echo form_open('auth/register'); ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>

    <p>Already have an account? <a href="<?= site_url('auth/login'); ?>">Login here</a></p>
</body>
</html>
