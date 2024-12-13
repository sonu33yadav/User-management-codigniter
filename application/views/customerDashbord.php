<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="mb-0">Welcome, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
            </h3>
        </div>
        <div class="card-body">
            <!-- User Details -->
            <h5 class="text-primary mb-4">User Details</h5>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light text-center">Email</th>
                        <td class="text-center"><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-center">Role</th>
                        <td class="text-center"><?php echo $user['role_id'] == 2 ? 'Customer' : 'Unknown'; ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Education Details -->
            <h5 class="text-primary mt-5 mb-4">Education Details</h5>
            <?php if (!empty($education_details)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">Degree</th>
                            <th class="text-center">Institution</th>
                            <th class="text-center">Year of Completion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($education_details as $education): ?>
                        <tr>
                            <td class="text-center"><?php echo htmlspecialchars($education['degree']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($education['institution']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($education['year_of_completion']); ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No education details available.
            </div>
            <?php endif;?>

            <!-- Employment Details -->
            <h5 class="text-primary mt-5 mb-4">Employment Details</h5>
            <?php if (!empty($employment_details)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">Company</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Start Date</th>
                            <th class="text-center">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employment_details as $employment): ?>
                        <tr>
                            <td class="text-center"><?php echo htmlspecialchars($employment['company']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($employment['designation']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($employment['start_date']); ?></td>
                            <td class="text-center">
                                <?php echo htmlspecialchars($employment['end_date'] ?: 'Present'); ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                No employment details available.
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>