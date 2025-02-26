<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
</head>
<body>
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
                    <td><?= htmlspecialchars($user->username); ?></td>
                    <td><?= htmlspecialchars($user->email); ?></td>
                    <td><?= htmlspecialchars($user->created_at); ?></td>
                    <td>
                        <div class="action-btn-container">
                            <button class="action-btn edit-btn" onclick="editUser(<?= $user->id; ?>, '<?= htmlspecialchars($user->username); ?>')">Edit</button>
                            <button class="action-btn delete-btn" onclick="deleteUser(<?= $user->id; ?>)">Delete</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Editing Username -->
    <div id="editModal" style="display: none; position: fixed; background: rgba(0, 0, 0, 0.5); width: 100%; height: 100%; top: 0; left: 0; align-items: center; justify-content: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; width: 300px; text-align: center;">
            <h3>Edit Username</h3>
            <input type="text" id="editUsername" style="width: 100%; padding: 8px; margin-bottom: 10px;">
            <button onclick="saveEdit()" style="background: #4caf50; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Save</button>
            <button onclick="closeModal()" style="background: #757575; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Cancel</button>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // Set available options
                "pageLength": 5 // Set default to 5
            });
        });

        let editUserId = null;

        function editUser(userId, currentUsername) {
            editUserId = userId;
            $("#editUsername").val(currentUsername);
            $("#editModal").show();
        }

        function closeModal() {
            $("#editModal").hide();
        }

        function saveEdit() {
            let newUsername = $("#editUsername").val();
            if (newUsername.trim() === "") {
                alert("Username cannot be empty!");
                return;
            }

            $.post("<?= site_url('dashboard/edit_user'); ?>", { user_id: editUserId, username: newUsername }, function(response) {
                if (response.status === "success") {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }, "json");
        }

        function deleteUser(userId) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            $.post("<?= site_url('dashboard/delete_user'); ?>", { user_id: userId }, function(response) {
                if (response.status === "success") {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }, "json");
        }
    </script>
</body>
</html>
