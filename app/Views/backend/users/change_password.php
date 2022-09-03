<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Profile <?= esc(auth()->user()->full_name ?? '') ?>
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
<?= $this->include('backend/users/components/user_validate') ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function () {
            const userChangePassword = document.getElementById('user-change-password'),
                newPasswordLabel = document.querySelector('label[for=new_password]')?.textContent,
                confirmPasswordLabel = document.querySelector('label[for=confirm_new_password]')?.textContent,
                requiredValidate = ' không được bỏ trống.'

            FormValidation.formValidation(userChangePassword, {
                fields: {
                    new_password: {
                        validators: {
                            notEmpty: {
                                message: newPasswordLabel + requiredValidate
                            },
                            stringLength: {
                                min: 6,
                                message: 'Mật khẩu phải có tối thiểu 6 ký tự.'
                            },
                        }
                    },
                    confirm_new_password: {
                        validators: {
                            notEmpty: {
                                message: confirmPasswordLabel + requiredValidate
                            },
                            stringLength: {
                                min: 6,
                                message: 'Mật khẩu phải có tối thiểu 6 ký tự.'
                            },
                            identical: {
                                compare: function () {
                                    return userChangePassword.querySelector('[name="new_password"]').value;
                                },
                                message: confirmPasswordLabel + ' không trùng khớp với ' + newPasswordLabel
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: '',
                        rowSelector: '.form-password-toggle'
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                },
                init: instance => {
                    instance.on('plugins.message.placed', function (e) {
                        if (e.element.parentElement.classList.contains('input-group')) {
                            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                        }
                    });
                }
            })
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
    <?= $this->include('backend/users/components/user_sidebar') ?>
    <!--/ User Sidebar -->

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <!-- User Pills -->
        <?= $this->include('backend/users/components/user_pills') ?>
        <!--/ User Pills -->

        <!-- Change Password -->
        <div class="card mb-4 change-password-page">
            <h5 class="card-header text-capitalize">Đổi mật khẩu</h5>
            <div class="card-body">
                <?= form_open(route_to('admin.users.postChangePassword', user_id()), ['id' => 'user-change-password', 'onsubmit' => 'return false']) ?>
                <?= $this->include('components/_message') ?>

                <div class="row">
                    <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                        <?= form_label('Mật khẩu mới', 'new_password', ['class' => 'form-label']) ?>
                        <div class="input-group input-group-merge">
                            <?= form_password(
                                'new_password',
                                '',
                                [
                                    'class' => 'form-control',
                                    'id' => 'new_password'
                                ])
                            ?>
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                        <?= form_label('Xác nhận mật khẩu mới', 'confirm_new_password', ['class' => 'form-label']) ?>
                        <div class="input-group input-group-merge">
                            <?= form_password(
                                'confirm_new_password',
                                '',
                                [
                                    'class' => 'form-control',
                                    'id' => 'confirm_new_password'
                                ])
                            ?>
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div>
                        <?= form_submit('submitButton', 'Lưu Lại', ['class' => 'btn btn-primary me-2']) ?>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <!-- /Change Password -->

        <!-- Recent Devices -->
        <div class="card">
            <h5 class="card-header">Recent Devices</h5>
            <div class="table-responsive">
                <table class="table border-top">
                    <thead>
                        <tr>
                            <th class="text-truncate">Thiết Bị</th>
                            <th class="text-truncate">Ngày Hoạt Động</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($getDeviceUser ?? [] as $item) : ?>
                            <tr>
                                <td class="text-truncate"><?= esc($item->user_agent) ?></td>
                                <td class="text-truncate"><?= esc($item->date->format(FORMAT_DATE)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Recent Devices -->
    </div>
    <!--/ User Content -->
</div>
<?= $this->endSection() ?>
