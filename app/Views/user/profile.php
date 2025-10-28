<?= $this->include('layouts/usermain') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <h2 class="card-title mb-4">Edit Profile</h2>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <?php if (isset($validation)) : ?>
                        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('user/updateProfile') ?>">
                        <?= csrf_field() ?>
                        
                        <h5 class="mb-3">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                            value="<?= old('name', $user['name'] ?? '') ?>" 
                                            readonly disabled> <!-- ADDED readonly and disabled -->
                                    <small class="form-text text-muted">This field cannot be changed.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                            value="<?= old('email', $user['email'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Student Details</h5>
                        <div class="row">
                             <div class="col-md-6">
                                 <div class="mb-3">
                                     <label for="student_id" class="form-label">Matric Number (Cannot Change)</label>
                                     <input type="text" id="student_id" class="form-control" 
                                             value="<?= esc($user['student_id'] ?? '') ?>" readonly disabled>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="mb-3">
                                     <label for="ic_number" class="form-label">IC Number (Cannot Change)</label>
                                     <input type="text" id="ic_number" class="form-control" 
                                             value="<?= esc($user['ic_number'] ?? '') ?>" readonly disabled>
                                 </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control" 
                                            value="<?= old('phone', $user['phone'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class" class="form-label">Class</label>
                                    <input type="text" name="class" id="class" class="form-control" 
                                            value="<?= old('class', $user['class'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Change Password</h5>
                        <small class="form-text text-muted d-block mb-2">
                            Leave both fields blank to keep your current password.
                        </small>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control" 
                                            placeholder="Enter new password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" 
                                            placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                            <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary ms-2">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footerT') ?>
