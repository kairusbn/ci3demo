$(document).ready(function () {
    $("#login-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: "Please enter your email",
                email: "Enter a valid email"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
