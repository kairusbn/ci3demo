<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
</head>
<body>
    <script>
        var loggedInUserId = <?= json_encode($this->session->userdata('user_id')); ?>;
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
                        <?php if ($user->id == $this->session->userdata('user_id')): ?>
                            <span style="color: green; font-weight: bold;"> (You)</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user->email); ?></td>
                    <td><?= htmlspecialchars($user->created_at); ?></td>
                    <td>
                        <div class="action-btn-container">
                            <button class="action-btn edit-btn" onclick="editUser(<?= $user->id; ?>, '<?= htmlspecialchars($user->username); ?>', '<?= htmlspecialchars($user->email); ?>')">
                                Edit
                            </button>
                            <?php if ($user->id != $this->session->userdata('user_id')): ?>
                            <button class="action-btn delete-btn" onclick="deleteUser(<?= $user->id; ?>)">
                                Delete
                            </button>
                            <?php endif; ?>
                            <?php if ($user->id == $this->session->userdata('user_id')): ?>
                            <button class="action-btn change-password-btn" onclick="openChangePasswordModal()">
                                Change Password
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUserId">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="editUsername">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="editEmail">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" class="form-control" id="currentPassword">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Change</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({ "pageLength": 5 });
        });

        function editUser(id, username, email) {
            $('#editUserId').val(id);
            $('#editUsername').val(username);
            $('#editEmail').val(email);
            $('#editUserModal').modal('show');
        }

        function saveUserChanges() {
            let userId = $('#editUserId').val();
            let newUsername = $('#editUsername').val();
            let newEmail = $('#editEmail').val();
            
            $.post("<?= site_url('dashboard/edit_user'); ?>", 
                { user_id: userId, username: newUsername, email: newEmail }, 
                function(response) {
                    if (response.status === "success") location.reload();
                    else alert(response.message);
                }, "json"
            );
        }

        function deleteUser(id) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            $.post("<?= site_url('dashboard/delete_user'); ?>", 
                { user_id: id }, 
                function(response) {
                    if (response.status === "success") location.reload();
                    else alert(response.message);
                }, "json"
            );
        }

        function openChangePasswordModal() {
            $('#changePasswordModal').modal('show');
        }

        function changePassword() {
            let currentPassword = $("#currentPassword").val().trim();
            let newPassword = $("#newPassword").val().trim();
            let confirmPassword = $("#confirmPassword").val().trim();

            if (newPassword !== confirmPassword) return alert("New passwords do not match!");
            if (newPassword.length < 6) return alert("Password must be at least 6 characters!");

            $.post("<?= site_url('dashboard/change_password'); ?>", 
                { user_id: loggedInUserId, current_password: currentPassword, new_password: newPassword, confirm_password: confirmPassword}, 
                function(response) {
                    if (response.status === "success") {
                        alert("Password changed successfully! Redirecting to login.");
                        $("#changePasswordModal").modal("hide");
                        $("#changePasswordForm")[0].reset();
                        $.post("<?= site_url('auth/logout'); ?>", function(){
                            window.location.href = "<?= site_url('auth/login'); ?>";
                        })
                    } else {
                        alert(response.message);
                    }
                }, "json"
            );
        }
    </script>
</body>
</html>
