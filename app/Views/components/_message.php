<script>
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành Công!',
            html: "<?= session()->getFlashdata('success') ?>",
            confirmButtonClass: 'btn btn-success'
        })
    <?php endif;?>

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Thất Bại!',
            html: "<?= session()->getFlashdata('error') ?>",
            confirmButtonClass: 'btn btn-danger'
        })
    <?php endif;?>
</script>