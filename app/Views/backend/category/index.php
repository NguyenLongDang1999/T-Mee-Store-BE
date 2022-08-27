<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Categories List <?= isset($recyclePage) ? 'Recycle' : '' ?> Page
<?= $this->endSection() ?>

<?= $this->section('pageCSS') ?>
<?= link_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.css') ?>
<?= link_tag('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>
<?= link_tag('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') ?>
<?= link_tag('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') ?>
<?= link_tag('assets/vendor/libs/sweetalert2/sweetalert2.css') ?>
<?= $this->endSection() ?>

<?= $this->section('pageJS') ?>
<?= script_tag('assets/vendor/libs/bootstrap-select/bootstrap-select.js') ?>
<?= script_tag('assets/vendor/libs/datatables/jquery.dataTables.js') ?>
<?= script_tag('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') ?>
<?= script_tag('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') ?>
<?= script_tag('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') ?>
<?= script_tag('assets/vendor/libs/sweetalert2/sweetalert2.js') ?>
<?= $this->include('components/_language_datatable') ?>
<?= $this->include('components/_message') ?>

<script>
    let categoryTable = $('#category-table'),
        url_delete_item = "<?= route_to('admin.category.multiDelete') ?>",
        url_status_item = "<?= route_to('admin.category.multiStatus') ?>",
        click_mode = 0,
        aLengthMenuGeneral = [
            [20, 50, 100, 500, 1000],
            [20, 50, 100, 500, 1000]
        ];

    if (categoryTable.length) {
        var oTable = categoryTable.DataTable({
            "bServerSide": true,
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "<?= isset($recyclePage) ? route_to('admin.category.getListRecycle') : route_to('admin.category.getList') ?>",
            "bDeferRender": true,
            "bFilter": false,
            "bDestroy": true,
            "aLengthMenu": aLengthMenuGeneral,
            "iDisplayLength": 20,
            "bSort": true,
            "aaSorting": [
                [7, "desc"]
            ],
            columns: [
                {
                    data: 'checkbox',
                    "bSortable": false
                },
                {
                    data: 'responsive_id',
                    "bSortable": false
                },
                {
                    data: 'image',
                    "bSortable": false
                },
                {
                    data: 'name'
                },
                {
                    data: 'parent_id',
                    "bSortable": false
                },
                {
                    data: 'status',
                    "bSortable": false
                },
                {
                    data: 'featured',
                    "bSortable": false
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'action',
                    "bSortable": false
                },
            ],
            "fnServerParams": function (aoData) {
                if (click_mode === 0) {
                    aoData.push({
                        "name": "search[name]",
                        "value": $('#frmSearch input[name="search[name]"]').val()
                    });
                    aoData.push({
                        "name": "search[parent_id]",
                        "value": $('#frmSearch select[name="search[parent_id]"]').val()
                    });
                    aoData.push({
                        "name": "search[status]",
                        "value": $('#frmSearch select[name="search[status]"]').val()
                    });
                    aoData.push({
                        "name": "search[featured]",
                        "value": $('#frmSearch select[name="search[featured]"]').val()
                    });
                }
            },
            columnDefs: [
                {
                    className: 'control',
                    targets: 0,
                    render: function () {
                        return '';
                    }
                },
                {
                    targets: 1,
                    render: function (data) {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input checkboxes" name="chk[]" value="' + $('<div/>').text(data).html() + '">';
                    },
                    checkboxes: {
                        selectAllRender: '<input type="checkbox" class="form-check-input" id="chkAll">'
                    },
                },
                {
                    targets: 3,
                    render: function (data, type, full) {
                        const $editPages = full['edit_pages'];
                        const $name = full['name'];

                        return (
                            '<a href=' + $editPages + ' class="text-capitalize">' + $name + '</a>'
                        );
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, full) {
                        const $status_number = full['status'];
                        const $status = {
                            <?= STATUS_ACTIVE ?>: {
                                title: 'Hiển thị',
                                class: 'bg-label-primary'
                            },
                            <?= STATUS_INACTIVE ?>: {
                                title: 'Không hiển thị',
                                class: ' bg-label-secondary'
                            },
                        };
                        if (typeof $status[$status_number] === 'undefined') {
                            return data;
                        }
                        return (
                            '<span class="badge rounded-pill text-capitalize ' +
                            $status[$status_number].class +
                            '">' +
                            $status[$status_number].title +
                            '</span>'
                        );
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, full) {
                        const $featured_number = full['featured'];
                        const $featured = {
                            <?= FEATURED_ACTIVE ?>: {
                                title: 'Nổi bật',
                                class: 'bg-label-primary'
                            },
                            <?= FEATURED_INACTIVE ?>: {
                                title: 'Không nổi bật',
                                class: ' bg-label-secondary'
                            },
                        };
                        if (typeof $featured[$featured_number] === 'undefined') {
                            return data;
                        }
                        return (
                            '<span class="badge rounded-pill text-capitalize ' +
                            $featured[$featured_number].class +
                            '">' +
                            $featured[$featured_number].title +
                            '</span>'
                        );
                    }
                },
                {
                    targets: -1,
                    title: 'Thao Tác',
                    render: function (data, type, full) {
                        const $editPages = full['edit_pages'];

                        return (
                            '<a href=' + $editPages + ' class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>'
                        );
                    }
                }
            ],
            dom: 'r <"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            select: {
                style: 'multi'
            },
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            const data = row.data();
                            return 'Chi Tiết Thông Tin: ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        const data = $.map(columns, function (col, i) {
                            return col.title !== ''
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td class="text-capitalize">' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            }
        });
    }

    $(document).ready(function () {
        $('#btnFrmSearch').on('click', function () {
            click_mode = 0;
            oTable.draw();
        });

        $('#btnReset').on('click', function () {
            click_mode = 1;
            oTable.draw();
            $('.bootstrap-select').selectpicker('val', '');
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('breadcrumbs') ?>
<li class="breadcrumb-item text-capitalize">
    <a href="<?= route_to('admin.category.index') ?>">Danh mục sản phẩm</a>
</li>

<li class="breadcrumb-item text-capitalize active"><?= isset($recyclePage) ? 'Thùng rác' : 'Danh sách' ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <?php if (isset($recyclePage)) : ?>
            <a href="<?= route_to('admin.category.index') ?>" class="btn btn-label-danger mb-4">
                <span class="tf-icons bx bx-arrow-back"></span> Quay Lại
            </a>

        <?php else: ?>
            <a href="<?= route_to('admin.category.create') ?>" class="btn btn-label-primary mb-4">
                <span class="tf-icons bx bx-plus"></span> Thêm Mới
            </a>

            <a href="<?= route_to('admin.category.recycle') ?>" class="btn btn-label-secondary mb-4">
                <span class="tf-icons bx bx-trash"></span> Thùng Rác
            </a>
        <?php endif; ?>
    </div>

    <div class="col-12">
        <div class="card mb-4">
            <h5 class="card-header text-capitalize">Danh sách <?= isset($recyclePage) ? 'Thùng rác' : '' ?> danh
                mục</h5>

            <div class="card-body">
                <?= form_open(
                    isset($recyclePage) ? route_to('admin.category.getListRecycle') : route_to('admin.category.getList'),
                    [
                        'class' => 'row g-3',
                        'id' => 'frmSearch',
                        'method' => 'GET',
                        'onsubmit' => 'return false;'
                    ]) ?>
                <div class="col-md-6">
                    <?= form_label('Tiêu đề danh mục', 'search[name]', ['class' => 'form-label']) ?>
                    <?= form_input(
                        'search[name]',
                        '',
                        [
                            'class' => 'form-control'
                        ])
                    ?>
                </div>

                <div class="col-md-6">
                    <?= form_label('Danh mục cha', 'search[parent_id]', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'search[parent_id]',
                        $getCategoryList ?? [],
                        '',
                        [
                            'class' => 'bootstrap-select text-capitalize w-100',
                            'data-style' => 'btn-default text-capitalize',
                            'data-size' => 8
                        ])
                    ?>
                </div>

                <div class="col-md-6">
                    <?= form_label('Trạng thái', 'search[status]', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'search[status]',
                        statusOption(),
                        '',
                        [
                            'class' => 'bootstrap-select text-capitalize w-100',
                            'data-style' => 'btn-default text-capitalize',
                            'data-size' => 8
                        ])
                    ?>
                </div>

                <div class="col-md-6">
                    <?= form_label('Nổi bật', 'search[featured]', ['class' => 'form-label']) ?>
                    <?= form_dropdown(
                        'search[featured]',
                        featuredOption(),
                        '',
                        [
                            'class' => 'bootstrap-select text-capitalize w-100',
                            'data-style' => 'btn-default text-capitalize',
                            'data-size' => 8
                        ])
                    ?>
                </div>

                <div class="col-12 text-center">
                    <?= form_button(
                        'search',
                        '<span class="tf-icons bx bx-search"></span> Tìm Kiếm',
                        [
                            'class' => 'btn btn-sm btn-primary',
                            'id' => 'btnFrmSearch'
                        ])
                    ?>

                    <?= form_button([
                        'name' => 'reset',
                        'id' => 'btnReset',
                        'class' => 'btn btn-sm btn-warning',
                        'type' => 'reset',
                        'content' => '<span class="tf-icons bx bx-refresh"></span> Làm Mới',
                    ])
                    ?>

                    <hr/>
                </div>
                <?= form_close() ?>

                <div class="col-12">
                    <?php if (isset($recyclePage)) : ?>
                        <?= form_button(
                            '',
                            'Khôi Phục',
                            [
                                'class' => 'btn btn-outline-primary btn-status',
                                'data-type' => 'deleted_at',
                                'data-status' => 'NULL',
                                'data-recycle' => 'true'
                            ])
                        ?>

                        <?= form_button(
                            '',
                            'Xóa Vĩnh Viễn',
                            [
                                'class' => 'btn btn-outline-secondary btn-delete',
                                'data-purge' => 'true'
                            ])
                        ?>
                    <?php else: ?>
                        <div class="btn-group" role="group">
                            <?= form_button(
                                '',
                                'Trạng Thái',
                                [
                                    'class' => 'btn btn-outline-primary dropdown-toggle',
                                    'id' => 'btnGroupDrop1',
                                    'data-bs-toggle' => 'dropdown',
                                    'aria-haspopup' => 'true',
                                    'aria-expanded' => 'false'
                                ])
                            ?>

                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item btn-status" href="javascript:void(0);"
                                   data-status="<?= STATUS_ACTIVE ?>" data-type="status"
                                   data-recycle="false">Hiển Thị</a>

                                <a class="dropdown-item btn-status" href="javascript:void(0);"
                                   data-status="<?= STATUS_INACTIVE ?>" data-type="status"
                                   data-recycle="false">Không Hiển Thị</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item btn-status" href="javascript:void(0);"
                                   data-status="<?= FEATURED_ACTIVE ?>" data-type="featured"
                                   data-recycle="false">Nổi Bật</a>

                                <a class="dropdown-item btn-status" href="javascript:void(0);"
                                   data-status="<?= FEATURED_INACTIVE ?>" data-type="featured"
                                   data-recycle="false">Không Nổi Bật</a>
                            </div>
                        </div>

                        <?= form_button(
                            '',
                            'Xóa Tạm Thời',
                            [
                                'class' => 'btn btn-outline-secondary btn-delete',
                                'data-purge' => 'false'
                            ])
                        ?>
                    <?php endif; ?>

                    <hr/>
                </div>

                <div class="col-12">
                    <?= form_open('', ['id' => 'frmTbList']) ?>
                    <table class="dt-responsive table table-bordered text-nowrap" id="category-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Hình đại diện</th>
                                <th>Tên danh mục</th>
                                <th>Danh mục cha</th>
                                <th>Trạng thái</th>
                                <th>Nổi bật</th>
                                <th>Ngày tạo</th>
                                <th>Ngày sửa</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                    </table>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
