<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Brand <?= isset($row) ? 'Update' : 'Create' ?> Page
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
            const brandForm = document.getElementById('brand-form'),
                url_exist_brand = "<?= base_url(route_to('admin.brand.validateExistSlug')) ?>",
                meteCSRF = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content'),
                nameLabel = document.querySelector('label[for=name]')?.textContent,
                descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                requiredValidate = ' không được bỏ trống.',
                maxValidate = ' không được vượt quá 160 ký tự.'

            FormValidation.formValidation(brandForm, {
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
                            remote: {
                                headers: {
                                    "X-CSRF-TOKEN": meteCSRF,
                                },
                                async: true,
                                cache: false,
                                message: nameLabel + ' đã tồn tại. Vui lòng kiểm tra lại!',
                                method: 'POST',
                                data: function () {
                                    return {
                                        slug: brandForm.querySelector('[name="slug"]').value,
                                        id: brandForm.querySelector('[name="id"]').value
                                    };
                                },
                                url: url_exist_brand
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
    <a href="<?= route_to('admin.brand.index') ?>">Thương hiệu</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($row) ? 'Cập nhật' : 'Thêm mới' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <a href="<?= route_to('admin.brand.index') ?>" class="btn btn-label-danger mb-4">
            <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
        </a>
    </div>

    <div class="col-12">
        <?= form_open_multipart($routePost ?? '', ['id' => 'brand-form']) ?>
        <?= form_hidden('imageRoot', $row->image ?? '') ?>
        <?= form_hidden('id', $row->id ?? '') ?>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông tin cơ bản</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Tiêu đề thương hiệu', 'name', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'name',
                            $row->name ?? '',
                            ['class' => 'form-control',
                                'id' => 'name'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Đường dẫn URL', 'slug', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'slug',
                            $row->slug ?? '',
                            ['class' => 'form-control',
                                'id' => 'slug',
                                'readonly' => 'readonly'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Mô tả thương hiệu', 'description', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'description',
                            $row->description ?? '',
                            ['class' => 'form-control',
                                'id' => 'description'])
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
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Hình đại diện thương hiệu</h5>

            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <?= img(
                        $row->image ?? PATH_IMAGE_DEFAULT,
                        false,
                        ['class' => 'd-block rounded',
                            'id' => 'uploaded-image',
                            'width' => '150',
                            'height' => '150',
                            'alt' => 'Brand Image',
                            'title' => 'Brand Image'])
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
