<script>
    const activityPage = $('.activity-page'),
        changePasswordPage = $('.change-password-page'),
        pillActivity = $('.pills-activity'),
        pillChangePassword = $('.pill-change-password')

    if (activityPage.length) {
        pillActivity.addClass('active')
    } else if (changePasswordPage.length) {
        pillChangePassword.addClass('active')
    }

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

                                if (getData !== null && userResp[data] !== null && getData.toLowerCase() !== userResp[data].toLowerCase()) {
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