<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Profile
<?= $this->endSection() ?>

<?= $this->section('pageCSS') ?>
<?= link_tag('assets/vendor/css/pages/page-user-view.css') ?>
<?= link_tag('assets/vendor/libs/flatpickr/flatpickr.css') ?>
<?= link_tag('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') ?>
<?= $this->endSection() ?>

<?= $this->section('pageJS') ?>
<?= script_tag('assets/vendor/libs/flatpickr/flatpickr.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function () {
            const editUsersForm = document.getElementById('edit-users-form'),
                fullNameLabel = document.querySelector('label[for=full_name]')?.textContent,
                phoneLabel = document.querySelector('label[for=phone]')?.textContent,
                jobLabel = document.querySelector('label[for=job]')?.textContent,
                addressLabel = document.querySelector('label[for=address]')?.textContent,
                requiredValidate = ' không được bỏ trống.'

            FormValidation.formValidation(editUsersForm, {
                fields: {
                    full_name: {
                        validators: {
                            notEmpty: {
                                message: fullNameLabel + requiredValidate
                            },
                            stringLength: {
                                max: 40,
                                message: fullNameLabel + ' không được vượt quá 40 ký tự.'
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: phoneLabel + requiredValidate
                            },
                            stringLength: {
                                max: 40,
                                message: phoneLabel + ' không được vượt quá 40 ký tự.'
                            }
                        }
                    },
                    job: {
                        validators: {
                            stringLength: {
                                max: 160,
                                message: jobLabel + ' không được vượt quá 160 ký tự.'
                            }
                        }
                    },
                    address: {
                        validators: {
                            stringLength: {
                                max: 160,
                                message: addressLabel + ' không được vượt quá 160 ký tự.'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: ''
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            }).on('core.form.valid', function () {
                const updateProfile = $.ajax({
                    type: "post",
                    processData: false,
                    contentType: false,
                    url: $(editUsersForm).attr('action'),
                    data: new FormData($(editUsersForm)[0])
                })
                updateProfile.done(function (resp) {
                    if (resp.result) {
                        const getPrefixUser = $('.user-show'),
                            userResp = resp.input

                        for (let i = 0; i < getPrefixUser.length; i++) {
                            for (const data in userResp) {
                                const getData = getPrefixUser[i].getAttribute('data-user-' + data)

                                if (getData !== null && getData.toLowerCase() !== userResp[data].toLowerCase()) {
                                    if (data === 'avatar') {
                                        $('input[name=imageRoot]').val(userResp[data])
                                        getPrefixUser[i].setAttribute('src', userResp[data])
                                    }

                                    getPrefixUser[i].setAttribute('data-user-' + data, userResp[data])
                                    getPrefixUser[i].textContent = userResp[data]
                                }
                            }
                        }

                        $('#editUser').modal('hide')
                    }
                })
            });
        })();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('breadcrumbs') ?>
<li class="breadcrumb-item text-capitalize active">Thông tin cá nhân</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row gy-4">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <!-- User Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        <?= img(
                            auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                            false,
                            [
                                'class' => 'img-fluid rounded my-4 user-show',
                                'data-user-avatar' => auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                                'height' => 110,
                                'width' => 110,
                                'alt' => esc(auth()->user()->full_name ?? ''),
                                'title' => esc(auth()->user()->full_name ?? '')
                            ]
                        ) ?>

                        <div class="user-info text-center">
                            <h5 class="mb-2 text-capitalize user-show"
                                data-user-full_name="<?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>">
                                <?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>
                            </h5>

                            <span class="badge bg-label-secondary">Author</span>
                        </div>
                    </div>
                </div>

                <h5 class="pb-2 border-bottom my-4 py-3">Thông Tin</h5>

                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-3 text-capitalize">
                            <span class="fw-bold me-2">Họ và tên:</span>
                            <span data-user-full_name="<?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>"
                                  class="user-show">
                                <?= esc(uppercaseFirstCharacter(auth()->user()->full_name ?? '')) ?>
                            </span>
                        </li>

                        <li class="mb-3">
                            <span class="fw-bold me-2">E-mail:</span>
                            <span><?= esc(auth()->user()->getEmail()) ?></span>
                        </li>

                        <li class="mb-3">
                            <span class="fw-bold me-2 text-capitalize">Số điện thoại:</span>
                            <span data-user-phone="<?= esc(auth()->user()->phone ?? '') ?>" class="user-show">
                                <?= esc(auth()->user()->phone ?? '') ?>
                            </span>
                        </li>

                        <li class="mb-3">
                            <span class="fw-bold me-2 text-capitalize">Giới tính:</span>
                            <span data-user-gender="<?= isset(auth()->user()->gender) ? esc(intval(auth()->user()->gender) === GENDER_MALE ? 'Nam' : 'Nữ') : '' ?>"
                                  class="user-show">
                                <?= isset(auth()->user()->gender) ? esc(intval(auth()->user()->gender) === GENDER_MALE ? 'Nam' : 'Nữ') : '' ?>
                            </span>
                        </li>

                        <li class="mb-3 text-capitalize">
                            <span class="fw-bold me-2">Nghề nghiệp:</span>
                            <span data-user-job="<?= esc(auth()->user()->job ?? '') ?>" class="user-show">
                                <?= esc(auth()->user()->job ?? '') ?>
                            </span>
                        </li>

                        <li class="mb-3 text-capitalize">
                            <span class="fw-bold me-2">Ngày sinh:</span>
                            <span data-user-birthdate="<?= esc(auth()->user()->birthdate ?? '') ?>" class="user-show">
                                <?= esc(dateFormat(auth()->user()->birthdate) ?? '') ?>
                            </span>
                        </li>

                        <li class="mb-3 text-capitalize">
                            <span class="fw-bold me-2">Địa chỉ:</span>
                            <span data-user-address="<?= esc(auth()->user()->address ?? '') ?>" class="user-show">
                                <?= esc(auth()->user()->address ?? '') ?>
                            </span>
                        </li>

                        <li class="mb-3">
                            <span class="fw-bold me-2">Status:</span>
                            <span class="badge bg-label-success">Active</span>
                        </li>

                        <li class="mb-3 text-capitalize">
                            <span class="fw-bold me-2">Vai trò:</span>
                            <span></span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center pt-3">
                        <a
                                href="javascript:void(0);"
                                class="btn btn-primary me-3"
                                data-bs-target="#editUser"
                                data-bs-toggle="modal"
                        >Chỉnh Sửa</a>

                        <!--                        <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspended</a>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /User Card -->
    </div>
    <!--/ User Sidebar -->

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <!-- User Pills -->
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="app-user-view-security.html"
                ><i class="bx bx-lock-alt me-1"></i>Security</a
                >
            </li>
            <li class="nav-item">
                <a class="nav-link" href="app-user-view-billing.html"
                ><i class="bx bx-detail me-1"></i>Billing & Plans</a
                >
            </li>
            <li class="nav-item">
                <a class="nav-link" href="app-user-view-notifications.html"
                ><i class="bx bx-bell me-1"></i>Notifications</a
                >
            </li>
            <li class="nav-item">
                <a class="nav-link" href="app-user-view-connections.html"
                ><i class="bx bx-link-alt me-1"></i>Connections</a
                >
            </li>
        </ul>
        <!--/ User Pills -->

        <!-- Activity Timeline -->
        <div class="card mb-4">
            <h5 class="card-header">User Activity Timeline</h5>
            <div class="card-body">
                <ul class="timeline">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">12 Invoices have been paid</h6>
                                <small class="text-muted">12 min ago</small>
                            </div>
                            <p class="mb-2">Invoices have been paid to the company</p>
                            <div class="d-flex">
                                <a href="javascript:void(0)" class="me-3">
                                    <img
                                            src="../../assets/img/icons/misc/pdf.png"
                                            alt="PDF image"
                                            width="20"
                                            class="me-2"
                                    />
                                    <span class="fw-bold text-body">invoices.pdf</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-warning"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Client Meeting</h6>
                                <small class="text-muted">45 min ago</small>
                            </div>
                            <p class="mb-2">Project meeting with john @10:15am</p>
                            <div class="d-flex flex-wrap">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div>
                                    <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                    <span>CEO of PIXINVENT</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-info"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Create a new project for client</h6>
                                <small class="text-muted">2 Day Ago</small>
                            </div>
                            <p class="mb-2">5 team members in a project</p>
                            <div class="d-flex align-items-center avatar-group">
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Vinnie Mostowy"
                                >
                                    <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Marrie Patty"
                                >
                                    <img src="../../assets/img/avatars/12.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Jimmy Jackson"
                                >
                                    <img src="../../assets/img/avatars/9.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Kristine Gill"
                                >
                                    <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Nelson Wilson"
                                >
                                    <img src="../../assets/img/avatars/14.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Design Review</h6>
                                <small class="text-muted">5 days Ago</small>
                            </div>
                            <p class="mb-0">Weekly review of freshly prepared design for our new app.</p>
                        </div>
                    </li>
                    <li class="timeline-end-indicator">
                        <i class="bx bx-check-circle"></i>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Activity Timeline -->
    </div>
    <!--/ User Content -->
</div>

<!-- Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <?= form_button(
                    '',
                    '',
                    [
                        'class' => 'btn-close',
                        'data-bs-dismiss' => 'modal',
                        'aria-label' => 'Close'
                    ]
                ) ?>

                <div class="text-center mb-4">
                    <h3 class="text-uppercase">Chỉnh sửa thông tin cá nhân</h3>
                </div>

                <?= form_open_multipart(route_to('admin.users.update', user_id()), ['class' => 'row g-3', 'id' => 'edit-users-form', 'onsubmit' => 'return false']) ?>
                <?= form_hidden('imageRoot', auth()->user()->avatar ?? '') ?>

                <div class="col-12 col-md-6">
                    <?= form_label('Họ và tên', 'full_name', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'full_name',
                        auth()->user()->full_name ?? '',
                        [
                            'class' => 'form-control',
                            'id' => 'full_name'
                        ])
                    ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= form_label('E-mail', 'email', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'email',
                        auth()->user()->getEmail() ?? '',
                        [
                            'class' => 'form-control',
                            'id' => 'email',
                            'readonly' => 'readonly'
                        ])
                    ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= form_label('Số điện thoại', 'phone', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'phone',
                        auth()->user()->phone ?? '',
                        [
                            'class' => 'form-control',
                            'id' => 'phone'
                        ])
                    ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= form_label('Nghề nghiệp', 'job', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'job',
                        auth()->user()->job ?? '',
                        [
                            'class' => 'form-control',
                            'id' => 'job'
                        ])
                    ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= form_label('Giới tính', 'gender', ['class' => 'form-label d-block']) ?>

                    <?php foreach (genderOption() as $k => $v) : ?>
                        <div class="form-check form-check-inline">
                            <?= form_radio(
                                'gender',
                                $k,
                                isset(auth()->user()->gender) && intval(auth()->user()->gender) === $k,
                                [
                                    'class' => 'form-check-input',
                                    'id' => 'gender-' . $k,
                                ])
                            ?>
                            <?= form_label($v, 'gender-' . $k, ['class' => 'form-check-label']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= form_label('Ngày sinh', 'birthdate', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'birthdate',
                        auth()->user()->birthdate ?? '',
                        [
                            'class' => 'form-control flatpickr-date',
                            'id' => 'birthdate'
                        ])
                    ?>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <?= img(
                            auth()->user()->avatar ?? PATH_IMAGE_DEFAULT,
                            false,
                            [
                                'class' => 'd-block rounded',
                                'id' => 'uploaded-image',
                                'width' => '150',
                                'height' => '150',
                                'alt' => 'Users Image',
                                'title' => 'Users Image'
                            ])
                        ?>

                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2" tabindex="0">
                                <span class="d-none d-sm-block">Chọn Hình</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>

                                <?= form_upload(
                                    'image',
                                    '',
                                    ['class' => 'image-file-input',
                                        'id' => 'upload',
                                        'hidden' => 'hidden',
                                        'accept' => 'image/png, image/jpeg'])
                                ?>
                            </label>

                            <button type="button" class="btn btn-label-secondary image-file-reset mt-1">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Làm Mới</span>
                            </button>

                            <p class="mb-0 mt-4">Chấp nhận ảnh JPG, GIF or PNG.</p>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <?= form_label('Địa chỉ', 'address', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'address',
                        auth()->user()->address ?? '',
                        [
                            'class' => 'form-control',
                            'id' => 'address'
                        ])
                    ?>
                </div>

                <div class="col-12 text-center mt-4">
                    <?= form_submit('submitButton', 'Lưu Lại', ['class' => 'btn btn-primary me-sm-3 me-1']) ?>
                    <?= form_button('', 'Đóng', ['class' => 'btn btn-label-secondary', 'data-bs-dismiss' => 'modal', 'aria-label' => 'Close']) ?>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- /Modal -->
<?= $this->endSection() ?>
