<?php
/**
 * Class Test_Sitemap
 *
 * @package All_in_One_SEO_Pack
 * @since ?
 */

/**
 * Sitemap test case.
 */
require_once AIOSEOP_UNIT_TESTING_DIR . '/base/class-aioseop-test-base.php';

/**
 * Class Sitemap_Test_Base
 *
 * @since 2.4.3.1
 */
class Sitemap_Test_Base extends AIOSEOP_Test_Base {

	/**
	 * Fires the hooks that create the sitemap.
	 *
	 * @return string
	 */
	private function create_sitemap() {
		add_filter( 'aioseo_sitemap_ping', '__return_false' );
		update_option( 'blog_public', 0 );

		// sitemap will be created in the root of the folder.
		do_action( 'aiosp_sitemap_settings_update' );

		$file = ABSPATH . '/sitemap.xml';

		$this->assertFileExists( $file );

		return $file;
	}

	/**
	 * Check whether the sitemap is valid on the basis of given conditions.
	 *
	 * @param array $elements this is the array that is used to compare with the sitemap. It can have a variety of structures but the key is always the URL:
	 * The values can be
	 * 1) a null - this will check for existence of the url
	 * 2) a boolean - true will check if the url exists, false if the url does not exist.
	 * 3) an array - each element of the array will be the name of the XML node. The value, again, can be
	 *      i) a boolean - true will check if the node exists, false if the node does not exist.
	 *      ii) a string - the value of the node should be the same as this value.
	 */
	final protected function validate_sitemap( $elements, $debug = false ) {
		$file = $this->create_sitemap();

		if ( $debug ) {
			error_log( file_get_contents( $file ) );
		}

		// validate file according to schema.
		$this->validate_sitemap_schema( $file, 'combined' );

		$xml = simplexml_load_file( $file );
		$ns  = $xml->getNamespaces( true );

		$sitemap = array();
		foreach ( $xml->url as $url ) {
			// this array will contain an array of arrays, each element corresponding to one "image:*".
			// Each item will have its corresponding array of values.
			$element = array();
			if ( array_key_exists( 'image', $ns ) && count( $url->children( $ns['image'] ) ) > 0 ) {
				$images = array();
				foreach ( $url->children( $ns['image'] ) as $image ) {
					$images['loc'][] = (string) $image->loc;
					if ( property_exists( $image, 'title' ) ) {
						$images['title'][] = (string) $image->title;
					}
					if ( property_exists( $image, 'caption' ) ) {
						$images['caption'][] = (string) $image->caption;
					}
				}
				$element['image'] = $images['loc'];
				if ( array_key_exists( 'title', $images ) ) {
					$element['image:title'] = $images['title'];
					if ( is_array( $images['title'] ) ) {
						// TODO: this does not work because wptexturize mangles the single and double quotes into the typewriter equivalents ' to ‘ or ’ and " to “ or ”.
						// phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
						// $this->assertContains( strip_tags( $this->_spl_chars ), $images['title'][0] );
					}
				}
				if ( array_key_exists( 'caption', $images ) ) {
					$element['image:caption'] = $images['caption'];
					if ( is_array( $images['caption'] ) ) {
						// TODO: this does not work because wptexturize mangles the single and double quotes into the typewriter equivalents ' to ‘ or ’ and " to “ or ”.
						// phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
						// $this->assertContains( strip_tags( $this->_spl_chars ), $images['caption'][0] );
					}
				}
			}
			$sitemap[ (string) $url->loc ] = $element;
		}

		if ( $debug ) {
			error_log( print_r( $sitemap, true ) );
		}

		foreach ( $elements as $url => $attributes ) {
			if ( is_null( $attributes ) ) {
				// no attributes? then just test if the url exists.
				$this->assertArrayHasKey( $url, $sitemap );
				continue;
			}
			if ( is_bool( $attributes ) ) {
				if ( true === $attributes ) {
					// just test if the url exists.
					$this->assertArrayHasKey( $url, $sitemap );
					continue;
				}
				// just test if the url NOT exists.
				$this->assertArrayNotHasKey( $url, $sitemap );
				continue;
			}

			foreach ( $attributes as $name => $value ) {
				if ( $debug ) {
					error_log( "Testing for $url " . print_r( $sitemap[ $url ], true ) );
				}
				if ( is_bool( $value ) ) {
					// just check if this element exists/not-exists.
					if ( true === $value ) {
						$this->assertArrayHasKey( $name, $sitemap[ $url ] );
						continue;
					}
					$this->assertArrayNotHasKey( $name, $sitemap[ $url ] );
					continue;
				}
				$this->assertEquals( $value, $sitemap[ $url ][ $name ] );
			}
		}

		$contents = file_get_contents( $file );

		// @codingStandardsIgnoreStart
		@unlink( $file );
		// @codingStandardsIgnoreEnd

		return $contents;
	}

	/**
	 * Check whether the sitemap index is valid.
	 *
	 * @param array $types All the types of sitemaps that should exist besides the regular one.
	 */
	final protected function validate_sitemap_index( $types = array(), $debug = false ) {
		add_filter( 'aioseo_sitemap_ping', '__return_false' );
		update_option( 'blog_public', 0 );

		// sitemap will be created in the root of the folder.
		do_action( 'aiosp_sitemap_settings_update' );

		$types[] = '';
		foreach ( $types as $type ) {
			$schema = 'index';
			if ( ! empty( $type ) ) {
				$type   = "{$type}-";
				$schema = 'combined';
			}
			$file = ABSPATH . "/{$type}sitemap.xml";

			$this->assertFileExists( $file );
			if ( $debug ) {
				echo file_get_contents( $file );
			}
			$this->validate_sitemap_schema( $file, $schema );
		}
	}

	/**
	 * Check whether the sitemap is valid according to its schema.
	 *
	 * @param string $file The path of the file.
	 * @param string $schema The schema type.
	 */
	final protected function validate_sitemap_schema( $file, $schema ) {
		// validate file according to schema.
		libxml_use_internal_errors( true );
		$dom = new DOMDocument();
		$dom->load( $file );

		$this->assertTrue( $dom->schemaValidate( AIOSEOP_UNIT_TESTING_DIR . "/resources/xsd/{$schema}.xsd" ) );
	}

	/**
	 * Counts how many times the given string(s) occur in the sitemap.
	 *
	 * @param array $elements The elements/attribute/string to search for.
	 * @return array
	 */
	final protected function count_sitemap_elements( $elements ) {
		$file     = $this->create_sitemap();
		$contents = file_get_contents( $file );

		$map = array();
		foreach ( $elements as $string ) {
			$map[ $string ] = substr_count( $contents, $string );
		}

		// @codingStandardsIgnoreStart
		@unlink( $file );
		// @codingStandardsIgnoreEnd

		return $map;

	}
}
