<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Attribute <?= isset($row) ? 'Update' : 'Create' ?> Page
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
            const attributeForm = document.getElementById('attribute-form'),
                nameLabel = document.querySelector('label[for=name]')?.textContent,
                categoryLabel = document.querySelector('label[for=category_id]')?.textContent,
                descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                requiredValidate = ' không được bỏ trống.',
                maxValidate = ' không được vượt quá 160 ký tự.'

            FormValidation.formValidation(attributeForm, {
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
                    category_id: {
                        validators: {
                            notEmpty: {
                                message: categoryLabel + requiredValidate
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
                    },
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
    <a href="<?= route_to('admin.attribute.index') ?>">Thuộc tính sản phẩm</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($row) ? 'Cập nhật' : 'Thêm mới' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <a href="<?= route_to('admin.attribute.index') ?>" class="btn btn-label-danger mb-4">
            <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
        </a>
    </div>

    <div class="col-12">
        <?= form_open_multipart($routePost ?? '', ['id' => 'attribute-form']) ?>
        <?= form_hidden('id', $row->id ?? '') ?>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông tin cơ bản</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Tên thuộc tính', 'name', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'name',
                            $row->name ?? '',
                            ['class' => 'form-control',
                                'id' => 'name'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Mô tả thuộc tính', 'description', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'description',
                            $row->description ?? '',
                            ['class' => 'form-control',
                                'id' => 'description'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Danh mục', 'category_id', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'category_id',
                            $getCategoryList ?? [],
                            $row->category_id ?? '',
                            ['class' => 'bootstrap-select text-capitalize w-100',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'category_id'])
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

        <?= form_submit('submitButton', 'Lưu Lại', ['class' => 'btn btn-primary']) ?>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
