<?php

if ( ! class_exists( 'All_in_One_SEO_Pack_Front' ) ) {

	/**
	 * Class All_in_One_SEO_Pack_Front
	 */
	class All_in_One_SEO_Pack_Front {

		function __construct() {

			add_action( 'template_redirect', array( $this, 'noindex_rss' ) );

		}

		public function noindex_rss(){
			if ( is_feed() && headers_sent() === false ) {
				header( 'X-Robots-Tag: noindex, follow', true );
			}
		}
	}

}

$aiosp_front_class = new All_in_One_SEO_Pack_Front();

