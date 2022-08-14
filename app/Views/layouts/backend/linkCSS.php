<!-- Favicon -->
<?= link_tag('assets/img/favicon/favicon.ico', 'icon', 'image/x-icon') ?>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<?= link_tag('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap') ?>

<!-- Icons -->
<?= link_tag('assets/vendor/fonts/boxicons.css') ?>

<!-- Core CSS -->
<?= link_tag('assets/vendor/css/rtl/core.css') ?>
<?= link_tag('assets/vendor/css/rtl/theme-default.css') ?>
<?= link_tag('assets/css/demo.css') ?>

<!-- Vendors CSS -->
<?= link_tag('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>

<!-- Page CSS -->
<?= $this->renderSection('pageCSS') ?>

<!-- Helpers -->
<?= script_tag('assets/vendor/js/helpers.js') ?>
<?= script_tag('assets/js/config.js') ?>
