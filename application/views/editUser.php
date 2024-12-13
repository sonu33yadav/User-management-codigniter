<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Edit User</h3>
            </div>
            <div class="card-body">
                <form action="<?=base_url();?>index.php/userController/updateUser" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?=$user['id'];?>" id="edit_user_id">
                    <!-- Hidden field for User ID -->

                    <!-- Personal Details -->
                    <h5 class="text-primary">Personal Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_first_name">First Name</label>
                                <input type="text" name="first_name" id="edit_first_name" class="form-control"
                                    value="<?=$user['first_name'];?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_last_name">Last Name</label>
                                <input type="text" name="last_name" id="edit_last_name" class="form-control"
                                    value="<?=$user['last_name'];?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <h5 class="text-primary mt-4">Account Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control"
                                    value="<?=$user['email'];?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_password">Password</label>
                                <input type="password" name="password" id="edit_password" class="form-control">
                                <small class="form-text text-muted">Leave blank if you don't want to change the
                                    password.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Role and Profile -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_role">Role</label>
                                <select name="role" id="edit_role" class="form-control" required>
                                    <option value="1" <?=$user['role_id'] == 1 ? 'selected' : '';?>>Admin</option>
                                    <option value="2" <?=$user['role_id'] == 2 ? 'selected' : '';?>>Customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_profile_image">Profile Image</label>
                                <input type="file" name="profile_image" id="edit_profile_image" class="form-control">
                                <small class="form-text text-muted">Upload a new image to replace the existing
                                    one.</small>
                            </div>
                        </div>
                    </div>
                    <!-- Education Details -->
                    <h5 class="text-primary mt-4">Education Details</h5>
                    <hr>
                    <div class="form-group">
                        <label for="degree">Degree</label>
                        <input type="text" name="degree" id="degree" class="form-control"
                            value="<?=$education['degree'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="institution">Institution</label>
                        <input type="text" name="institution" id="institution" class="form-control"
                            value="<?=$education['institution'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="year_of_completion">Year of Completion</label>
                        <input type="date" name="year_of_completion" id="year_of_completion" class="form-control"
                            value="<?=$education['year_of_completion'];?>" required>
                    </div>

                    <!-- Company Details -->
                    <h5 class="text-primary mt-4">Company Details</h5>
                    <hr>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" name="company" id="company" class="form-control"
                            value="<?=$company['company'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" id="designation" class="form-control"
                            value="<?=$company['designation'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="<?=$company['start_date'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="<?=$company['end_date'];?>">
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?=base_url();?>index.php/userController" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>