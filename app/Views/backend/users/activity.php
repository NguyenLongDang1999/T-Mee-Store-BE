<?= $this->extend('layouts/backend/index') ?>

<?= $this->section('title') ?>
Profile <?= esc(auth()->user()->full_name ?? '') ?>
<?= $this->endSection() ?>

<?= $this->section('pageCSS') ?>
<?= link_tag('assets/vendor/css/pages/page-user-view.css') ?>
<?= link_tag('assets/vendor/libs/flatpickr/flatpickr.css') ?>
<?= link_tag('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') ?>
<?= $this->endSection() ?>

<?= $this->section('pageJS') ?>
<?= script_tag('assets/vendor/libs/flatpickr/flatpickr.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') ?>
<?= script_tag('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') ?>
<?= $this->include('backend/users/components/user_validate') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumbs') ?>
<li class="breadcrumb-item text-capitalize active">Thông tin cá nhân</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row gy-4">
    <!-- User Sidebar -->
    <?= $this->include('backend/users/components/user_sidebar') ?>
    <!--/ User Sidebar -->

    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <!-- User Pills -->
        <?= $this->include('backend/users/components/user_pills') ?>
        <!--/ User Pills -->

        <!-- Activity Timeline -->
        <div class="card mb-4 activity-page">
            <h5 class="card-header">User Activity Timeline</h5>
            <div class="card-body">
                <ul class="timeline">
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">12 Invoices have been paid</h6>
                                <small class="text-muted">12 min ago</small>
                            </div>
                            <p class="mb-2">Invoices have been paid to the company</p>
                            <div class="d-flex">
                                <a href="javascript:void(0)" class="me-3">
                                    <img
                                            src="../../assets/img/icons/misc/pdf.png"
                                            alt="PDF image"
                                            width="20"
                                            class="me-2"
                                    />
                                    <span class="fw-bold text-body">invoices.pdf</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-warning"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Client Meeting</h6>
                                <small class="text-muted">45 min ago</small>
                            </div>
                            <p class="mb-2">Project meeting with john @10:15am</p>
                            <div class="d-flex flex-wrap">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div>
                                    <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                    <span>CEO of PIXINVENT</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-info"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Create a new project for client</h6>
                                <small class="text-muted">2 Day Ago</small>
                            </div>
                            <p class="mb-2">5 team members in a project</p>
                            <div class="d-flex align-items-center avatar-group">
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Vinnie Mostowy"
                                >
                                    <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Marrie Patty"
                                >
                                    <img src="../../assets/img/avatars/12.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Jimmy Jackson"
                                >
                                    <img src="../../assets/img/avatars/9.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Kristine Gill"
                                >
                                    <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                                <div
                                        class="avatar pull-up"
                                        data-bs-toggle="tooltip"
                                        data-popup="tooltip-custom"
                                        data-bs-placement="top"
                                        title="Nelson Wilson"
                                >
                                    <img src="../../assets/img/avatars/14.png" alt="Avatar" class="rounded-circle"/>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">Design Review</h6>
                                <small class="text-muted">5 days Ago</small>
                            </div>
                            <p class="mb-0">Weekly review of freshly prepared design for our new app.</p>
                        </div>
                    </li>
                    <li class="timeline-end-indicator">
                        <i class="bx bx-check-circle"></i>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Activity Timeline -->
    </div>
    <!--/ User Content -->
</div>
<?= $this->endSection() ?>
