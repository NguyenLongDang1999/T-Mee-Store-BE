<!DOCTYPE html>

<html
        lang="en"
        class="light-style layout-navbar-fixed layout-menu-fixed"
        dir="ltr"
        data-theme="theme-default"
        data-assets-path="../../assets/"
        data-template="vertical-menu-template-no-customizer"
>
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>CMS - <?= $this->renderSection('title') ?></title>
    <?= $this->include('layouts/backend/linkCSS') ?>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <?= $this->include('layouts/backend/menu') ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            <?= $this->include('layouts/backend/header') ?>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <?= $this->renderSection('content') ?>
                </div>
                <!-- / Content -->
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->
<?= $this->include('layouts/backend/linkJS') ?>

</body>
</html>
