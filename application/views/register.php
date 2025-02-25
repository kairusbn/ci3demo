<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css'); ?>">

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/jquery.validate.min.js'); ?>"></script>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="success"> <?= $this->session->flashdata('success'); ?> </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="error"> <?= $this->session->flashdata('error'); ?> </div>
        <?php endif; ?>

        <?= validation_errors('<div class="error">', '</div>'); ?>

        <?= form_open('auth/register', ['id' => 'registerForm']); ?>
            <label>Username:</label>
            <input type="text" name="username" id="username" value="<?= set_value('username'); ?>" required>
            <span id="username-error" class="error-message"></span>

            <label>Email:</label>
            <input type="email" name="email" id="email" value="<?= set_value('email'); ?>" required>
            <span id="email-error" class="error-message"></span>

            <label>Password:</label>
            <input type="password" name="password" id="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="password_confirm" id="password_confirm" required>

            <button type="submit" id="register-btn">Register</button>
        </form>

        <p>Already have an account? <a href="<?= site_url('auth/login'); ?>">Login here</a></p>
    </div>

    <script>
    $(document).ready(function () {
        let usernameTaken = false;
        let emailTaken = false;

        function checkAvailability(field, value, url, callback) {
            $.ajax({
                type: "POST",
                url: url,
                data: { [field]: value },
                async: false,
                success: function(response) {
                    callback(response.trim() === "exists");
                }
            });
        }

        $.validator.addMethod("usernameAvailable", function(value, element) {
            checkAvailability("username", value, "<?= site_url('auth/check_username') ?>", function(isTaken) {
                usernameTaken = isTaken;
            });
            return !usernameTaken;
        }, "Username is already taken");

        $.validator.addMethod("emailAvailable", function(value, element) {
            checkAvailability("email", value, "<?= site_url('auth/check_email') ?>", function(isTaken) {
                emailTaken = isTaken;
            });
            return !emailTaken;
        }, "Email is already taken");

        $("#registerForm").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                    usernameAvailable: true
                },
                email: {
                    required: true,
                    email: true,
                    emailAvailable: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirm: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                username: {
                    required: "Please enter a username",
                    minlength: "Username must be at least 3 characters"
                },
                email: {
                    required: "Please enter your email",
                    email: "Enter a valid email"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 6 characters"
                },
                password_confirm: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            errorPlacement: function(error, element) {
                $("#" + element.attr("id") + "-error").html(error);
            },
            submitHandler: function(form) {
                if (usernameTaken || emailTaken) {
                    $(".error-message").html("");
                    if (usernameTaken) {
                        $("#username-error").html("<span class='error'>Username is already taken</span>");
                    }
                    if (emailTaken) {
                        $("#email-error").html("<span class='error'>Email is already taken</span>");
                    }
                    alert("Username or email is already taken, please try again.");
                    return false;
                }
                form.submit();
            }
        });
    });
    </script>
</body>
</html>
=======
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
>>>>>>> ce2787aded8ed07cc86d71b4ac74af7a59be57fd
