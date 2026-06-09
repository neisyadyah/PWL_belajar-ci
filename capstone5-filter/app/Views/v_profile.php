<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Profile Information</h5>

        <table class="table table">
            <tr>
                <td>
                    Username
                </td>
                <td>
                    <?= session()->get('username') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Role
                </td>
                <td>
                    <span class="badge bg-danger">
                        <?= session()->get('role') ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    Email
                </td>
                <td>
                    <?= session()->get('email') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Login Time
                </td>
                <td>
                    <?= session()->get('time') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Status
                </td>
                <td>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i>
                        Sudah login
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

<?= $this->endsection() ?>