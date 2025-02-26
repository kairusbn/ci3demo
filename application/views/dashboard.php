<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap CSS (for modals) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
</head>
<body>
    <script>
        var loggedInUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
    </script>
    
    <header class="dashboard-header">
        <span class="welcome-text">Welcome, <?= htmlspecialchars($this->session->userdata('username')); ?>!</span>
        <a class="logout-btn" href="<?= site_url('auth/logout'); ?>">Logout</a>
    </header>

    <div class="table-container">
        <table id="userTable" class="display">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr data-id="<?= $user->id; ?>">
                    <td>
                        <?= htmlspecialchars($user->username); ?>
                        <?php if ($user->id == $_SESSION['user_id']) echo '<span style="color: green; font-weight: bold;"> (You)</span>'; ?>
                    </td>
                    <td><?= htmlspecialchars($user->email); ?></td>
                    <td><?= htmlspecialchars($user->created_at); ?></td>
                    <td>
                        <div class="action-btn-container">
                            <button class="action-btn edit-btn" onclick="editUser(<?= $user->id; ?>, '<?= htmlspecialchars($user->username); ?>', '<?= htmlspecialchars($user->email); ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn" onclick="deleteUser(<?= $user->id; ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php if ($user->id == $_SESSION['user_id']): ?>
                            <button class="action-btn change-password-btn" onclick="openChangePasswordModal()">
                                <i class="fas fa-key"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Editing User -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit User</h3>
            <input type="text" id="editUsername" placeholder="Username">
            <input type="email" id="editEmail" placeholder="Email">
            <button id="saveEditBtn">Save</button>
            <button id="closeModalBtn">Cancel</button>
        </div>
    </div>

    <!-- Modal for Changing Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="savePassword">Change</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery & Bootstrap JS -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], 
                "pageLength": 5 
            });

            $("#closeModalBtn").click(closeModal);
            $("#editModal").hide();

            // Bind change password button click
            $("#savePassword").click(changePassword);
        });

        let editUserId = null;

        function editUser(userId, currentUsername, currentEmail) {
            editUserId = userId;
            $("#editUsername").val(currentUsername);
            $("#editEmail").val(currentEmail);

            if (userId == loggedInUserId) {
                $("#editUsername").prop("disabled", true);
            } else {
                $("#editUsername").prop("disabled", false);
            }

            $("#editModal").fadeIn();
        }

        function closeModal() {
            $("#editModal").fadeOut();
        }

        function deleteUser(userId) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            $.post("<?= site_url('dashboard/delete_user'); ?>", 
                { user_id: userId }, 
                function(response) {
                    if (response.status === "success") {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }, "json"
            );
        }

        function openChangePasswordModal() {
            $("#changePasswordModal").modal("show");
        }

        function changePassword() {
            let currentPassword = $("#currentPassword").val().trim();
            let newPassword = $("#newPassword").val().trim();
            let confirmPassword = $("#confirmPassword").val().trim();

            if (!currentPassword || !newPassword || !confirmPassword) {
                alert("All fields are required!");
                return;
            }

            if (newPassword !== confirmPassword) {
                alert("New passwords do not match!");
                return;
            }

            if (newPassword.length < 6) {
                alert("New password must be at least 6 characters long!");
                return;
            }

            $.post("<?= site_url('dashboard/change_password'); ?>", 
                { user_id: loggedInUserId, current_password: currentPassword, new_password: newPassword, confirm_password: confirmPassword }, 
                function(response) {
                    if (response.status === "success") {
                        alert("Password changed successfully!");
                        $("#changePasswordModal").modal("hide");
                        $("#changePasswordForm")[0].reset();
                        // Log out the user and redirect to login page
                        $.post("<?= site_url('auth/logout'); ?>", function() {
                            window.location.href = "<?= site_url('auth/login'); ?>";
                        });
                    } else {
                        alert(response.message);
                    }
                }, "json"
            );
        }
    </script>
</body>
</html>
