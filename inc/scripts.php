<?php
/**
 * Outputs BugHerd script in footer.
 *
 * @package rkv-bugherd
 */

add_action( 'wp_footer', 'rkv_bugherd_scripts' );
/**
 * Outputs the bugherd script if the API key has been set in the settings.
 */
function rkv_bugherd_scripts() {
	$rkv_bugherd_api_key = get_option( 'rkv_bugherd_api_key' );

	if ( empty( $rkv_bugherd_api_key ) ) {
		return; // API Key not set, nothing to do here.
	}

	$rkv_bugherd_src = esc_url( add_query_arg( 'apikey', $rkv_bugherd_api_key, 'https://www.bugherd.com/sidebarv2.js' ) );

	if ( empty( $rkv_bugherd_src ) ) {
		return;
	}

	?>
<script type='text/javascript'>
	(function (d, t) {
		var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
		bh.type = 'text/javascript';
		bh.src = '<?php echo $rkv_bugherd_src; // WPCS: XSS ok. ?>';
		s.parentNode.insertBefore(bh, s);
	})(document, 'script');
</script>
<?php
}
