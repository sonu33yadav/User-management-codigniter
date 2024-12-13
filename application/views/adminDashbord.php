<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Dashboard</h2>

        <!-- Display Last Login -->
        <div class="card mb-4">
            <div class="card-header">Welcome!</div>
            <div class="card-body">
                <p>Last login:
                    <?php echo $last_login ? $last_login : 'No login details available.'; ?>
                </p>
            </div>
        </div>
        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif;?>
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif;?>
        <!-- Display User Count -->
        <div class="card mb-4">
            <div class="card-header">User Statistics</div>
            <div class="card-body">
                <p>Total Customers Count:<?php echo $user_count ?> </p>
            </div>
        </div>

        <!-- Display Last 5 Added Users -->
        <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addUserModal">Add
            User</button>
        <div class="card">
            <div class="card-header">Last 5 Added Users</div>
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Degree</th>
                            <th>Institution</th>
                            <th>Company</th>
                            <th>Designation</th>
                            <th>Profile Image</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($last_five_users as $user): ?>
                        <tr>
                            <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                            <td><?php echo $user['email'] ? $user['email'] : 'No Details Found.'; ?></td>
                            <td><?php echo $user['degree'] ? $user['degree'] : 'No Details Found.'; ?></td>

                            <td><?php echo $user['institution'] ? $user['institution'] : 'No Details Found.'; ?></td>
                            <td><?php echo $user['company'] ? $user['company'] : 'No Details Found.'; ?></td>
                            <td><?php echo $user['designation'] ? $user['designation'] : 'No Details Found.'; ?></td>
                            <td>
                                <?php if ($user['profile_image']): ?>
                                <img src="<?php echo base_url(); ?>uploads/profile_images/<?php echo $user['profile_image']; ?>"
                                    alt="Profile Image" width="100" height="100">
                                <?php else: ?>
                                No image Found.
                                <?php endif;?>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            <td>

                                <!-- Edit Button -->
                                <a href="<?=base_url('index.php/userController/editUser/' . $user['id']);?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <!-- Delete Button -->
                                <a href="<?=base_url('index.php/userController/deleteUser/' . $user['id']);?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal for Adding New User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" id="addUserButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?=base_url();?>index.php/userController/addUser" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="2">Customer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="profile_image">Profile Image</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="degree">Degree</label>
                            <input type="text" name="degree" id="degree" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="institution">Institution</label>
                            <input type="text" name="institution" id="institution" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="year_of_completion">Year of Completion</label>
                            <input type="date" name="year_of_completion" id="year_of_completion" class="form-control"
                                min="1900" max="<?=date('Y');?>">
                        </div>

                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" name="company" id="company" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('addUserButton').addEventListener('click', function() {
        $('#addUserModal').modal('show');
    });
    </script>
</body>

</html>