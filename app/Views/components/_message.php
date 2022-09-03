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

<?php if (session('error') !== null) : ?>
    <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-info-circle me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (session('errors') !== null) : ?>
    <div class="alert alert-danger" role="alert">
        <?php if (is_array(session('errors'))) : ?>
            <?php foreach (session('errors') as $error) : ?>
                <?= $error ?>
                <br>
            <?php endforeach ?>
        <?php else : ?>
            <?= session('errors') ?>
        <?php endif ?>
    </div>
<?php endif ?>

<?php if (session('message') !== null) : ?>
    <div class="alert alert-solid-success alert-dismissible d-flex align-items-center" role="alert">
        <i class="bx bx-xs bx-check me-2"></i>
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>