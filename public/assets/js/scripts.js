$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').attr("content"),
        },
        async: true,
        cache: false
    });

    // Variables
    const bootstrapSelect = $('.bootstrap-select'),
        priceFormat = $('.price-format'),
        imageUploader = $('.image-uploader'),
        flatpickrDate = $('.flatpickr-date'),
        imageFileInput = $('.image-file-input'),
        imageFileReset = $('.image-file-reset'),
        name = $('#name'),
        slug = $('#slug'),
        quillJS = $('#quill-editor'),
        formRepeater = $('.form-repeater')

    let uploadedImage = $('#uploaded-image')

    // Plugins
    if (bootstrapSelect.length) {
        bootstrapSelect.selectpicker()
    }

    if (flatpickrDate.length) {
        flatpickrDate.flatpickr({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd-m-Y',
        });
    }

    if (imageUploader.length) {
        imageUploader.imageUploader({
            maxSize: 2 * 1024 * 1024,
            maxFiles: 6,
            mimes: ['image/jpeg', 'image/png'],
            label: 'Kéo & Thả hình vào đây hoặc nhấp chuột để chọn'
        });
    }

    if (priceFormat.length) {
        priceFormat.toArray().forEach(function (field) {
            new Cleave(field, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            })
        });
    }

    if (formRepeater.length) {
        let row = 2;
        let col = 1;

        formRepeater.on('submit', function (e) {
            e.preventDefault();
        });

        formRepeater.repeater({
            show: function () {
                const fromControl = $(this).find('.form-control, .form-select');
                const formLabel = $(this).find('.form-label');

                fromControl.each(function (i) {
                    const id = 'form-repeater-' + row + '-' + col;
                    $(fromControl[i]).attr('id', id);
                    $(formLabel[i]).attr('for', id);
                    col++;
                });

                row++;

                $(this).slideDown();
            },
            hide: function (e) {
                $(this).slideUp(e);
            }
        })
    }

    if (quillJS.length) {
        const quillEditor = '#quill-editor'

        new Quill(quillEditor, {
            bounds: quillEditor,
            placeholder: 'Nhập vào đây...',
            modules: {
                toolbar: [
                    [
                        {
                            font: []
                        },
                        {
                            size: []
                        }
                    ],
                    ['bold', 'italic', 'underline', 'strike'],
                    [
                        {
                            color: []
                        },
                        {
                            background: []
                        }
                    ],
                    [
                        {
                            script: 'super'
                        },
                        {
                            script: 'sub'
                        }
                    ],
                    [
                        {
                            header: '1'
                        },
                        {
                            header: '2'
                        },
                        'blockquote',
                        'code-block'
                    ],
                    [
                        {
                            list: 'ordered'
                        },
                        {
                            list: 'bullet'
                        },
                        {
                            indent: '-1'
                        },
                        {
                            indent: '+1'
                        }
                    ],
                    [
                        'direction',
                        {
                            align: []
                        }
                    ],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            theme: 'snow'
        });
    }

    // Methods
    if (uploadedImage) {
        const resetImage = uploadedImage.attr('src')

        imageFileInput.change(function () {
            const getFiles = $(this).prop('files')[0]

            if (getFiles) {
                uploadedImage.attr('src', window.URL.createObjectURL(getFiles))
            }
        })

        imageFileReset.click(function () {
            imageFileInput.val('')
            uploadedImage.attr('src', resetImage)
        })
    }

    name ? name.on('input', () => convertToSlug()) : ''

    $(document).on("click", ".btn-status", function () {
        const is_checked = isChecked()
        const status = $(this).data("status")
        const type = $(this).data("type")
        const recycle = $(this).data("recycle")
        return is_checked ? statusAllItem($("#frmTbList").serialize(), status, type, recycle) : notifyCancel()
    })

    $(document).on("click", ".btn-delete", function () {
        const is_checked = isChecked()
        const purge = $(this).data("purge")
        return is_checked ? deleteAllItem($("#frmTbList").serialize(), purge) : notifyCancel()
    })

    $(document).on("change", "select[class$=load-attribute]", function () {
        const categoryID = $(this).val()
        return attributeLoadList(categoryID)
    })

    $(document).on('change', 'select[name=attribute_id]', function () {
        console.log($(this).find('option:selected').text())
    })

    // Functions
    function convertToSlug() {
        let str = name.val()
        str = str.toLowerCase()

        str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a')
        str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e')
        str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i')
        str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o')
        str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u')
        str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y')
        str = str.replace(/(đ)/g, 'd')
        str = str.replace(/([^0-9a-z-\s])/g, '')
        str = str.replace(/(\s+)/g, '-')
        str = str.replace(/^-+/g, '')
        str = str.replace(/-+$/g, '')

        return slug.val(str)
    }

    function isChecked() {
        const checkAll = $("#chkAll").attr("checked")
        let flag = false

        $("input.checkboxes").each(function (index, element) {
            if (element.checked) {
                flag = true
            }
        });

        if (checkAll || flag) {
            flag = true
        }

        return flag
    }

    function notifyCancel(text = "Không Có Mục Nào Được Chọn", icon = 'warning', title = 'Cảnh Báo!') {
        Swal.fire({
            icon: icon,
            title: title,
            html: text
        })
    }

    function notifySuccess(html) {
        Swal.fire({
            icon: "success",
            title: "Thành Công!",
            html: html,
            confirmButtonClass: "btn btn-success"
        })
    }

    function statusAllItem(data, status, type, recycle) {
        Swal.fire({
            title: "Bạn Có Chắn Chắn Muốn Cập Nhật Trạng Thái Không ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Đồng Ý",
            confirmButtonClass: "btn btn-primary me-3",
            cancelButtonClass: "btn btn-label-secondary",
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                const updateAjax = $.ajax({
                    type: "post",
                    url: url_status_item,
                    data: {
                        data: data,
                        status: status,
                        type: type,
                        recycle: recycle
                    }
                })
                updateAjax.done(function (resp) {
                    oTable.draw();
                    return resp.result ? notifySuccess(resp.message) : notifyCancel(resp.message, 'error', 'Thất Bại!')
                })
            }
        })
    }

    function deleteAllItem(data, purge) {
        Swal.fire({
            title: "Bạn Có Chắn Chắn Muốn Xóa Dữ Liệu Này Không ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Đồng Ý",
            confirmButtonClass: "btn btn-primary me-3",
            cancelButtonClass: "btn btn-label-secondary",
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                const deleteAjax = $.ajax({
                    type: "post",
                    url: url_delete_item,
                    data: {
                        data: data,
                        purge: purge
                    }
                })
                deleteAjax.done(function (resp) {
                    oTable.draw()
                    return resp.result ? notifySuccess(resp.message) : notifyCancel(resp.message, 'error', 'Thất Bại!')
                })
            }
        })
    }

    function attributeLoadList(data) {
        const attributeList = $.ajax({
            type: "post",
            url: url_load_attribute,
            data: {
                data: data
            }
        })
        attributeList.done(function (resp) {
            $('#attribute_id').html(resp)
            $('#attribute_id').selectpicker('refresh');
        })
    }
})