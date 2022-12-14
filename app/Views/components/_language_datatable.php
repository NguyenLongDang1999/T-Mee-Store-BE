<script>
    $.extend(true, $.fn.dataTable.defaults, {
        "language": {
            "sProcessing": '<div class="spinner-border spinner-border-lg text-primary" role="status"> <span class="visually-hidden">Loading...</span> </div>',
            "sLengthMenu": "Hiển Thị _MENU_ Kết Quả",
            "sZeroRecords": "Không Có Dữ Liệu Nào Được Hiển Thị",
            "sEmptyTable": "Không Có Dữ Liệu Nào Được Hiển Thị",
            "sInfoEmpty": "Hiển Thị Từ 0 Đến 0 Trong Tổng Số 0",
            "sInfo": "Hiển Thị Từ _START_ Đến _END_ Trong Tổng Số _TOTAL_",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "<",
                "sNext": ">",
                "sLast": "Cuối"
            }
        }
    });
</script>