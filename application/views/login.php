<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
<<<<<<< HEAD

    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/validate.js'); ?>"></script>  <!-- Your custom validation script -->
</head>
<body>
    <div class="login-container">  <!-- Added the container -->
        <h2>Login</h2>

        <!-- Success/Error messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div style="color: green;"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div style="color: red;"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php echo validation_errors(); // Display form validation errors ?>

        <?php echo form_open('auth/login', ['id' => 'login-form']); ?>
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
    </div>  <!-- Close the container -->
    <script>
    console.log("Checking jQuery...");
    if (typeof jQuery !== "undefined") {
        console.log("✅ jQuery is loaded:", jQuery.fn.jquery);
    } else {
        console.log("❌ jQuery is NOT loaded!");
    }
</script>

</body>
</html>  
=======
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
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
