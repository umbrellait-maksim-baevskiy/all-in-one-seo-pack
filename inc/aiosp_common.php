<?php

/**
 * @package All-in-One-SEO-Pack
 */

class aiosp_common {
	
	function __construct(){
		//construct
	}
	
	
	static function get_blog_page( $p = null ) {
		static $blog_page = '';
		static $page_for_posts = '';
		if ( $p === null ) {
			global $post;
		} else {
			$post = $p;
		}
		if ( $blog_page === '' ) {
			if ( $page_for_posts === '' ) $page_for_posts = get_option( 'page_for_posts' );
			if ( $page_for_posts && is_home() && ( !is_object( $post ) || ( $page_for_posts != $post->ID ) ) )
				$blog_page = get_post( $page_for_posts );
		}
		return $blog_page;
	}
	
	
}