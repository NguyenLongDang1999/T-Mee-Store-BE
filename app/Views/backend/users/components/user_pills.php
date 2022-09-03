<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link text-capitalize pills-activity" href="<?= route_to('admin.users.activity') ?>">
            <i class="bx bx-user me-1"></i>
            Hoạt động
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-capitalize pill-change-password" href="<?= route_to('admin.users.changePassword') ?>"
        ><i class="bx bx-lock-alt me-1"></i>Đổi mật khẩu</a>
    </li>
</ul>