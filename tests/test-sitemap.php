<?php
/**
 * Class Test_Sitemap
 *
 * @package 
 */

/**
 * Sitemap test case.
 */
class Test_Sitemap extends WP_Ajax_UnitTestCase {

	private function _has_in_sitemap( $link, $column, $value ) {
		$this->go_to(site_url( '/sitemap.xml'));
		$output = ob_get_contents();
		ob_clean();
		print_r($output);
	}

	private function _setup_options() {
        $this->_setRole( 'administrator' );
		// init the general options.
		do_action( 'init' );
		global $aioseop_options;

		// activate the sitemap module.
		$aioseop_options['modules'] = array(
			'aiosp_feature_manager_options'	=> array(
				'aiosp_feature_manager_enable_sitemap'	=> 'on'
			),
		);
		update_option( 'aioseop_options', $aioseop_options );

		$_POST		= array(
			'action'				=> 'aiosp_update_module',
			'Submit_All_Default'	=> 'blah',
			'Submit'				=> 'blah',
			'nonce-aioseop'			=> wp_create_nonce( 'aioseop-nonce' ),
			'module'				=> 'All_in_One_SEO_Pack',
		);

		$this->go_to( admin_url( 'admin.php?page=all-in-one-seo-pack%2Fmodules%2Faioseop_sitemap.php' ) );
		do_action( 'admin_menu' );

		$aioseop_options = get_option( 'aioseop_options' );
//print_r($aioseop_options);
return;

		unset( $aioseop_options['aiosp_sitemap_indexes'] );
		unset( $aioseop_options['aiosp_sitemap_images'] );
		unset( $aioseop_options['aiosp_sitemap_gzipped'] );
		$aioseop_options['aiosp_posttypecolumns'] = array( 'post' );
		update_option( 'aioseop_options', $aioseop_options );
	}

	private function _upload_image( $image, $id = 0 ) {
		/* this factory method has a bug so we have to be a little clever.
		$this->factory->attachment->create( array( 'file' => $image, 'post_parent' => $id ) );
		*/
		$attachment_id = $this->factory->attachment->create_upload_object( $image, $id );
		if ( 0 !== $id ) {
			update_post_meta( $id, '_thumbnail_id', $attachment_id );
		}
	}

	public function test_featured_image() {
		$this->factory->post->create_many( 2, array( 'post_content' => 'content without image', 'post_title' => 'title without image' ) );
		$ids	= $this->factory->post->create_many( 2, array( 'post_content' => 'content with image', 'post_title' => 'title with image' ) );
		foreach ( $ids as $id ) {
			$this->_upload_image( str_replace( '\\', '/', trailingslashit( __DIR__ ) . 'images/footer-logo.png' ), $id );
		}

		$posts	= get_posts(
			array(
				'post_type' => 'post',
				'fields'	=> 'ids',
		) );

		// 4 posts created?
		$this->assertEquals(4, count( $posts ) );

		$attachments	= get_posts(
			array(
				'post_type' => 'attachment',
				'fields'	=> 'ids',
		) );

		// 2 attachments created?
		$this->assertEquals(2, count( $attachments ) );

		$featured	= 0;
		foreach ( $posts as $id ) {
			if ( has_post_thumbnail( $id ) ) {
				$featured++;
			}
		}

		// 2 posts have featured image?
		$this->assertEquals(2, $featured);

		$this->_setup_options();

		$this->_has_in_sitemap( '', '', '' );
	}


}


