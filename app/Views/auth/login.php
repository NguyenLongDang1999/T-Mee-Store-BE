<?php helper(['html', 'form']); ?>

<!DOCTYPE html>

<html
        lang="en"
        class="light-style customizer-hide"
        dir="ltr"
        data-theme="theme-default"
        data-assets-path="../../assets/"
        data-template="vertical-menu-template-no-customizer"
>
    <head>
        <meta charset="utf-8"/>
        <meta
                name="viewport"
                content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        />

        <title>CMS - Đăng nhập quản trị Administrator</title>

        <?= $this->include('layouts/backend/linkCSS') ?>
        <?= link_tag('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') ?>
        <?= link_tag('assets/vendor/css/pages/page-auth.css') ?>
    </head>

    <body>
        <!-- Content -->
        <div class="container-xxl">
            <div class="authentication-wrapper authentication-basic container-p-y">
                <div class="authentication-inner py-4">
                    <!-- Login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-2 text-uppercase text-center">Đăng nhập</h4>
                            <p class="mb-4 text-center">Vui lòng đăng nhập để vào trang quản trị CMS</p>

                            <?= $this->include('layouts/component/_message') ?>

                            <?= form_open(route_to('admin.login'), ['id' => 'login-form']) ?>

                            <div class="mb-3">
                                <?= form_label('E-mail', 'email', ['class' => 'form-label']) ?>
                                <?= form_input(
                                    'email',
                                    old('email') ?? '',
                                    [
                                        'class' => 'form-control',
                                        'id' => 'email',
                                        'placeholder' => 'Nhập E-mail của bạn',
                                        'autofocus' => 'autofocus'
                                    ])
                                ?>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <?= form_label('Mật khẩu', 'password', ['class' => 'form-label']) ?>
                                <div class="input-group input-group-merge">
                                    <?= form_password(
                                        'password',
                                        '',
                                        [
                                            'class' => 'form-control',
                                            'id' => 'password',
                                            'placeholder' => '***************',
                                            'aria-describedby' => 'password'
                                        ])
                                    ?>

                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <?= form_checkbox('remember', '', false, ['class' => 'form-check-input', 'remember']) ?>
                                    <?= form_label('Giữ Đăng Nhập', 'remember', ['class' => 'form-check-label']) ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <?= form_submit('submitButton', 'Đăng Nhập', ['class' => 'btn btn-primary d-grid w-100']) ?>
                            </div>

                            <?= form_close() ?>
                        </div>
                    </div>
                    <!-- /Login -->
                </div>
            </div>
        </div>
        <!-- / Content -->

        <?= $this->include('layouts/backend/linkJS') ?>
        <?= script_tag('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') ?>
        <?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') ?>
        <?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                (function () {
                    const loginForm = document.getElementById('login-form'),
                        emailLabel = document.querySelector('label[for=email]')?.textContent,
                        passwordLabel = document.querySelector('label[for=password]')?.textContent,
                        requiredValidate = ' không được bỏ trống.'

                    FormValidation.formValidation(loginForm, {
                        fields: {
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: emailLabel + requiredValidate
                                    },
                                    emailAddress: {
                                        message: emailLabel + ' không đúng định dạng.'
                                    }
                                }
                            },
                            password: {
                                validators: {
                                    notEmpty: {
                                        message: passwordLabel + requiredValidate
                                    }
                                }
                            },
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap5: new FormValidation.plugins.Bootstrap5({
                                eleValidClass: '',
                                rowSelector: '.mb-3'
                            }),
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                            autoFocus: new FormValidation.plugins.AutoFocus()
                        }
                    });
                })();
            });
        </script>
    </body>
</html>
