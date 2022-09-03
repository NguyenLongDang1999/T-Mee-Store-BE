<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Categories <?= isset($row) ? 'Update' : 'Create' ?> Page
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
            const categoryForm = document.getElementById('category-form'),
                url_exist_category = "<?= base_url(route_to('admin.category.categoryExistSlug')) ?>",
                meteCSRF = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content'),
                nameLabel = document.querySelector('label[for=name]')?.textContent,
                parentIDLabel = document.querySelector('label[for=parent_id]')?.textContent,
                descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                metaTitleLabel = document.querySelector('label[for=meta_title]')?.textContent,
                metaKeywordLabel = document.querySelector('label[for=meta_keyword]')?.textContent,
                metaDescriptionLabel = document.querySelector('label[for=meta_description]')?.textContent,
                requiredValidate = ' không được bỏ trống.',
                maxValidate = ' không được vượt quá 160 ký tự.'

            FormValidation.formValidation(categoryForm, {
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
                                        slug: categoryForm.querySelector('[name="slug"]').value,
                                        id: categoryForm.querySelector('[name="id"]').value
                                    };
                                },
                                url: url_exist_category
                            },
                        }
                    },
                    parent_id: {
                        validators: {
                            notEmpty: {
                                message: parentIDLabel + requiredValidate
                            }
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
                    meta_title: {
                        validators: {
                            stringLength: {
                                max: 60,
                                message: metaTitleLabel + ' không được vượt quá 60 ký tự.'
                            }
                        }
                    },
                    meta_keyword: {
                        validators: {
                            stringLength: {
                                max: 160,
                                message: metaKeywordLabel + maxValidate
                            }
                        }
                    },
                    meta_description: {
                        validators: {
                            stringLength: {
                                max: 160,
                                message: metaDescriptionLabel + maxValidate
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
    <a href="<?= route_to('admin.category.index') ?>">Danh mục sản phẩm</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($row) ? 'Cập nhật' : 'Thêm mới' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <a href="<?= route_to('admin.category.index') ?>" class="btn btn-label-danger mb-4">
            <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
        </a>
    </div>

    <div class="col-12">
        <?= form_open_multipart($routePost ?? '', ['id' => 'category-form']) ?>
        <?= form_hidden('imageRoot', $row->image ?? '') ?>
        <?= form_hidden('id', $row->id ?? '') ?>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông tin cơ bản</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Tiêu đề danh mục', 'name', ['class' => 'form-label']) ?>
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
                        <?= form_label('Danh mục cha', 'parent_id', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'parent_id',
                            $getCategoryList ?? [],
                            $row->parent_id ?? '',
                            [
                                'class' => 'bootstrap-select text-capitalize w-100',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'parent_id'
                            ])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Mô tả danh mục', 'description', ['class' => 'form-label']) ?>
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

                    <div class="col-md-6">
                        <?= form_label('Nổi bật', 'featured', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'featured',
                            featuredOption(),
                            $row->featured ?? '',
                            ['class' => 'bootstrap-select text-capitalize w-100',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'featured'])
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Hình đại diện danh mục</h5>

            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <?= img(
                        $row->image ?? PATH_IMAGE_DEFAULT,
                        false,
                        ['class' => 'd-block rounded',
                            'id' => 'uploaded-image',
                            'width' => '150',
                            'height' => '150',
                            'alt' => 'Category Image',
                            'title' => 'Category Image'])
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

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Meta SEO</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <?= form_label('Meta Title', 'meta_title', ['class' => 'form-label']) ?>
                        <?= form_textarea(
                            'meta_title',
                            $row->meta_title ?? '',
                            ['class' => 'form-control',
                                'id' => 'meta_title',
                                'rows' => 3])
                        ?>
                    </div>

                    <div class="col-12">
                        <?= form_label('Meta Keyword', 'meta_keyword', ['class' => 'form-label']) ?>
                        <?= form_textarea(
                            'meta_keyword',
                            $row->meta_keyword ?? '',
                            ['class' => 'form-control',
                                'id' => 'meta_keyword',
                                'rows' => 3])
                        ?>
                    </div>

                    <div class="col-12">
                        <?= form_label('Meta Description', 'meta_description', ['class' => 'form-label']) ?>
                        <?= form_textarea(
                            'meta_description',
                            $row->meta_description ?? '',
                            ['class' => 'form-control',
                                'id' => 'meta_description',
                                'rows' => 3])
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
