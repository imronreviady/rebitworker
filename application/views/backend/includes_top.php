	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
	</script>
	<!--end::Web font -->
	<!--begin::Base Styles -->
	<link href="<?= base_url(); ?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url(); ?>assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Base Styles -->
	<link rel="shortcut icon" href="<?= base_url(); ?>assets/demo/default/media/img/logo/favicon.ico" />