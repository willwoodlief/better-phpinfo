<div class="phpinfo-wrapper">
	<div class="wrap">
		<h2>
			<?php echo $this->get_menu_title(); ?>
		</h2>

		<div class="phpinfo-content">
            <?php /** @uses Php_Info\Info::display_info() */?>
			<?php $this->display_info(); ?>
		</div><!-- /.content -->
	</div><!-- /.wrap -->
</div><!-- /.phpinfo-wrapper -->
