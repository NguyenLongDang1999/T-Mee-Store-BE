<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Slider <?= isset($row) ? 'Update' : 'Create' ?> Page
<?= $this->endSection() ?>

<?= $this->section('pageCSS') ?>
<?= link_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.css') ?>
<?= link_tag('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') ?>
<?= $this->endSection() ?>

<?= $this->section('pageJS') ?>
<?= script_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function () {
            const sliderForm = document.getElementById('slider-form'),
                url_exist_slider = "<?= base_url(route_to('admin.slider.validateExistSlug')) ?>",
                meteCSRF = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content'),
                nameLabel = document.querySelector('label[for=name]')?.textContent,
                urlLabel = document.querySelector('label[for=url]')?.textContent,
                descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                requiredValidate = ' không được bỏ trống.',
                maxValidate = ' không được vượt quá 160 ký tự.'

            FormValidation.formValidation(sliderForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: nameLabel + requiredValidate
                            },
                            stringLength: {
                                min: 2,
                                max: 30,
                                message: nameLabel + ' phải có độ dài từ 2 - 30 ký tự.'
                            },
                        }
                    },
                    url: {
                        validators: {
                            stringLength: {
                                max: 255,
                                message: urlLabel + ' không được vượt quá 255 ký tự.'
                            },
                            uri: {
                                message: urlLabel + ' không đúng định dạng.',
                            },
                            remote: {
                                headers: {
                                    "X-CSRF-TOKEN": meteCSRF,
                                },
                                async: true,
                                cache: false,
                                message: urlLabel + ' đã tồn tại. Vui lòng kiểm tra lại!',
                                method: 'POST',
                                data: function () {
                                    return {
                                        url: sliderForm.querySelector('[name="url"]').value,
                                        id: sliderForm.querySelector('[name="id"]').value
                                    };
                                },
                                url: url_exist_slider
                            },
                        }
                    },
                    description: {
                        validators: {
                            stringLength: {
                                max: 160,
                                message: descriptionLabel + maxValidate
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
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        })();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('breadcrumbs') ?>
<li class="breadcrumb-item text-capitalize">
    <a href="<?= route_to('admin.slider.index') ?>">Slider</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($row) ? 'Cập nhật' : 'Thêm mới' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <a href="<?= route_to('admin.slider.index') ?>" class="btn btn-label-danger mb-4">
            <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
        </a>
    </div>

    <div class="col-12">
        <?= form_open_multipart($routePost ?? '', ['id' => 'slider-form']) ?>
        <?= form_hidden('imageRoot', $row->image ?? '') ?>
        <?= form_hidden('id', $row->id ?? '') ?>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông tin cơ bản</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Tiêu đề slider', 'name', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'name',
                            $row->name ?? '',
                            ['class' => 'form-control',
                                'id' => 'name'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Đường dẫn URL', 'url', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'url',
                            $row->url ?? '',
                            ['class' => 'form-control',
                                'id' => 'url'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Vị trí', 'sort', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'sort',
                            $row->sort ?? '',
                            ['class' => 'form-control',
                                'id' => 'sort'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Trạng thái', 'status', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'status',
                            statusOption(),
                            $row->status ?? '',
                            ['class' => 'bootstrap-select text-capitalize w-100',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'status'])
                        ?>
                    </div>

                    <div class="col-12">
                        <?= form_label('Mô tả Slider', 'description', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'description',
                            $row->description ?? '',
                            ['class' => 'form-control',
                                'id' => 'description'])
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Hình đại diện Slider</h5>

            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <?= img(
                        $row->image ?? PATH_IMAGE_DEFAULT,
                        false,
                        ['class' => 'd-block rounded',
                            'id' => 'uploaded-image',
                            'width' => '150',
                            'height' => '150',
                            'alt' => 'Slider Image',
                            'title' => 'Slider Image'])
                    ?>

                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
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

                        <button type="button" class="btn btn-label-secondary image-file-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Làm Mới</span>
                        </button>

                        <p class="mb-0">Chấp nhận ảnh JPG, GIF or PNG.</p>
                    </div>
                </div>
            </div>
        </div>

        <?= form_submit('submitButton', 'Lưu Lại', ['class' => 'btn btn-primary']) ?>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
