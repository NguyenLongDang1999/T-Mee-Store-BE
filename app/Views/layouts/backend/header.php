<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                       data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <?= img(
                                auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                                false,
                                [
                                    'class' => 'rounded-circle user-show',
                                    'data-user-avatar' => auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                                    'alt' => esc(auth()->user()->full_name ?? ''),
                                    'title' => esc(auth()->user()->full_name ?? '')
                                ]
                            ) ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <?= img(
                                                auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                                                false,
                                                [
                                                    'class' => 'rounded-circle user-show',
                                                    'data-user-avatar' => auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                                                    'alt' => esc(auth()->user()->full_name ?? ''),
                                                    'title' => esc(auth()->user()->full_name ?? '')
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block lh-1 text-capitalize user-show"
                                              data-user-full_name="<?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>">
                                            <?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>
                                        </span>
                                        <small>Admin</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= route_to('admin.users.activity') ?>">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle text-capitalize">Thông tin cá nhân</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bx bx-support me-2"></i>
                                <span class="align-middle">Help</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bx bx-help-circle me-2"></i>
                                <span class="align-middle">FAQ</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bx bx-dollar me-2"></i>
                                <span class="align-middle">Pricing</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item text-capitalize" href="<?= route_to('logout') ?>">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>