<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Product <?= isset($row) ? 'Update' : 'Create' ?> Page
<?= $this->endSection() ?>

<?= $this->section('pageCSS') ?>
<?= link_tag('assets/vendor/libs/quill/editor.css') ?>
<?= link_tag('assets/vendor/libs/quill/typography.css') ?>
<?= link_tag('assets/vendor/libs/flatpickr/flatpickr.css') ?>
<?= link_tag('assets/vendor/libs/uploader/image.uploader.css') ?>
<?= link_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.css') ?>
<?= link_tag('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') ?>
<?= $this->endSection() ?>

<?= $this->section('pageJS') ?>
<?= script_tag('assets/vendor/libs/quill/quill.js') ?>
<?= script_tag('assets/vendor/libs/cleavejs/cleave.js') ?>
<?= script_tag('assets/vendor/libs/flatpickr/flatpickr.js') ?>
<?= script_tag('assets/vendor/libs/uploader/image.uploader.js') ?>
<?= script_tag('assets/vendor/libs/jquery-repeater/jquery-repeater.js') ?>
<?= script_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') ?>

<script>
    let url_load_attribute = "<?= route_to('admin.product.attributeLoadList') ?>"

    document.addEventListener('DOMContentLoaded', function () {
        (function () {
            const productForm = document.getElementById('product-form'),
                url_exist_product = "<?= base_url(route_to('admin.product.productExistSlug')) ?>",
                meteCSRF = document.querySelector('meta[name="X-CSRF-TOKEN"]').getAttribute('content'),
                nameLabel = document.querySelector('label[for=name]')?.textContent,
                skuLabel = document.querySelector('label[for=sku]')?.textContent,
                quantityLabel = document.querySelector('label[for=quantity]')?.textContent,
                priceLabel = document.querySelector('label[for=price]')?.textContent,
                discountLabel = document.querySelector('label[for=discount]')?.textContent,
                categoryIDLabel = document.querySelector('label[for=category_id]')?.textContent,
                descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                metaTitleLabel = document.querySelector('label[for=meta_title]')?.textContent,
                metaKeywordLabel = document.querySelector('label[for=meta_keyword]')?.textContent,
                metaDescriptionLabel = document.querySelector('label[for=meta_description]')?.textContent,
                requiredValidate = ' không được bỏ trống.',
                maxValidate = ' không được vượt quá 160 ký tự.',
                numberValidate = ' không đúng định dạng.'

            FormValidation.formValidation(productForm, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: nameLabel + requiredValidate
                            },
                            stringLength: {
                                min: 2,
                                max: 100,
                                message: nameLabel + ' phải có độ dài từ 2 - 100 ký tự.'
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
                                        slug: productForm.querySelector('[name="slug"]').value,
                                        id: productForm.querySelector('[name="id"]').value
                                    };
                                },
                                url: url_exist_product
                            },
                        }
                    },
                    sku: {
                        validators: {
                            notEmpty: {
                                message: skuLabel + requiredValidate
                            },
                            stringLength: {
                                min: 2,
                                max: 30,
                                message: skuLabel + 'phải có độ dài từ 2 - 30 ký tự.',
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_]+$/,
                                message: skuLabel + ' chỉ chấp nhận chữ, số và dấu gạch dưới.',
                            },
                        }
                    },
                    quantity: {
                        validators: {
                            notEmpty: {
                                message: quantityLabel + requiredValidate
                            },
                            numeric: {
                                message: quantityLabel + numberValidate
                            }
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: priceLabel + requiredValidate
                            },
                            numeric: {
                                message: priceLabel + numberValidate
                            }
                        }
                    },
                    discount: {
                        validators: {
                            numeric: {
                                message: discountLabel + numberValidate
                            }
                        }
                    },
                    category_id: {
                        validators: {
                            notEmpty: {
                                message: categoryIDLabel + requiredValidate
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
                },
                init: instance => {
                    instance.on('plugins.message.placed', function (e) {
                        if (e.element.parentElement.classList.contains('input-group')) {
                            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                        }
                    });
                }
            });
        })();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('breadcrumbs') ?>
<li class="breadcrumb-item text-capitalize">
    <a href="<?= route_to('admin.product.index') ?>">Sản phẩm</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($row) ? 'Cập nhật' : 'Thêm mới' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <a href="<?= route_to('admin.product.index') ?>" class="btn btn-label-danger mb-4">
            <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
        </a>
    </div>

    <div class="col-12">
        <?= form_open_multipart($routePost ?? '', ['id' => 'product-form', 'class' => 'form-repeater']) ?>
        <?= form_hidden('imageRoot', $row->image ?? '') ?>
        <?= form_hidden('id', $row->id ?? '') ?>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông tin cơ bản</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Tên sản phẩm', 'name', ['class' => 'form-label']) ?>
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
                        <?= form_label('SKU (Mã định danh sản phẩm)', 'sku', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'sku',
                            $row->sku ?? '',
                            ['class' => 'form-control',
                                'id' => 'sku'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Số lượng', 'quantity', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'quantity',
                            $row->quantity ?? 0,
                            ['class' => 'form-control',
                                'id' => 'quantity'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Giá tiền (VNĐ)', 'price', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'price',
                            $row->price ?? '',
                            ['class' => 'form-control price-format',
                                'id' => 'price'])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Giảm giá sản phẩm', 'discount', ['class' => 'form-label']) ?>

                        <div class="input-group">
                            <?= form_dropdown(
                                'type_discount',
                                typeDiscountOption(),
                                $row->type_discount ?? '',
                                [
                                    'class' => 'bootstrap-select text-capitalize',
                                    'data-style' => 'btn-default text-capitalize',
                                    'data-size' => 8,
                                    'id' => 'type-discount'
                                ])
                            ?>

                            <?= form_input(
                                'discount',
                                $row->discount ?? '',
                                ['class' => 'form-control price-format',
                                    'id' => 'discount'])
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Danh mục', 'category_id', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'category_id',
                            $getCategoryList ?? [],
                            $row->category_id ?? '',
                            [
                                'class' => 'bootstrap-select text-capitalize w-100 load-attribute',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'category_id'
                            ])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Thương hiệu', 'brand_id', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'brand_id',
                            $getBrandList ?? [],
                            $row->brand_id ?? '',
                            [
                                'class' => 'bootstrap-select text-capitalize w-100',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'brand_id'
                            ])
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

                    <div class="col-12">
                        <?= form_label('Mô tả ngắn sản phẩm', 'description', ['class' => 'form-label']) ?>
                        <?= form_textarea(
                            'description',
                            $row->description ?? '',
                            ['class' => 'form-control',
                                'id' => 'description',
                                'rows' => 3])
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thuộc tính sản phẩm</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <?= form_label('Thuộc tính', 'attribute_id', ['class' => 'form-label']) ?>
                        <?= form_dropdown(
                            'attribute_id',
                            ['' => '[-- Chọn Thuộc Tính --]'],
                            $row->attribute_id ?? '',
                            [
                                'class' => 'bootstrap-select text-capitalize w-px-250',
                                'data-style' => 'btn-default text-capitalize',
                                'data-size' => 8,
                                'id' => 'attribute_id'
                            ])
                        ?>
                    </div>

                    <div class="col-12">
                        <div class="row g-3" id="add-attribute-value">
                            <div class="col-md-5 col-4">
                                <?= form_label('Tên thuộc tính', 'attribute_id_name', ['class' => 'form-label']) ?>
                                <?= form_input(
                                    'attribute_id_name',
                                    '',
                                    ['class' => 'form-control',
                                        'disabled' => 'disabled',
                                        'id' => 'attribute_id_name'])
                                ?>
                            </div>

                            <div class="col-md-5 col-6">
                                <?= form_label('Giá trị thuộc tính', 'attribute_values', ['class' => 'form-label']) ?>
                                <?= form_input(
                                    'attribute_values',
                                    '',
                                    ['class' => 'form-control',
                                        'id' => 'attribute_values'])
                                ?>
                            </div>

                            <div class="col-md-2 col-2">
                                <button type="button" class="btn btn-icon btn-primary">
                                    <span class="tf-icons bx bx-pie-chart-alt"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Mô tả chi tiết sản phẩm</h5>

            <div class="card-body">
                <div id="quill-editor"></div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Thông số kỹ thuật</h5>

            <div class="card-body">
                <div data-repeater-list="group-a">
                    <div data-repeater-item>
                        <div class="row">
                            <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                <?= form_label('Tiêu đề thông số', 'form-repeater-1-1', ['class' => 'form-label']) ?>
                                <?= form_input(
                                    'form-repeater-1-1',
                                    '',
                                    ['class' => 'form-control',
                                        'id' => 'form-repeater-1-1'])
                                ?>
                            </div>

                            <div class="mb-3 col-lg-6 col-xl-5 col-12 mb-0">
                                <?= form_label('Mô tả thông số', 'form-repeater-1-1', ['class' => 'form-label']) ?>
                                <?= form_input(
                                    'form-repeater-1-1',
                                    '',
                                    ['class' => 'form-control',
                                        'id' => 'form-repeater-1-1'])
                                ?>
                            </div>

                            <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                <a href="javascript:void(0)" class="btn btn-label-danger mt-4" data-repeater-delete>
                                    <i class="bx bx-x"></i>
                                    <span class="align-middle">Xóa</span>
                                </a>
                            </div>
                        </div>

                        <hr/>
                    </div>
                </div>

                <div class="mb-0">
                    <a href="javascript:void(0)" class="btn btn-primary" data-repeater-create>
                        <i class="bx bx-plus"></i>
                        <span class="align-middle">Thêm</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Hình đại diện sản phẩm</h5>

            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <?= img(
                        $row->image ?? PATH_IMAGE_DEFAULT,
                        false,
                        ['class' => 'd-block rounded',
                            'id' => 'uploaded-image',
                            'width' => '150',
                            'height' => '150',
                            'alt' => 'Product Image',
                            'title' => 'Product Image'])
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

                <div class="mt-4">
                    <div class="image-uploader"></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Ngày áp dụng</h5>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= form_label('Ngày bắt đầu', 'start_at', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'start_at',
                            $row->start_at ?? '',
                            [
                                'class' => 'form-control flatpickr-date',
                                'id' => 'start_at'
                            ])
                        ?>
                    </div>

                    <div class="col-md-6">
                        <?= form_label('Ngày kết thúc', 'end_at', ['class' => 'form-label']) ?>
                        <?= form_input(
                            'end_at',
                            $row->end_at ?? '',
                            [
                                'class' => 'form-control flatpickr-date',
                                'id' => 'end_at'
                            ])
                        ?>
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
