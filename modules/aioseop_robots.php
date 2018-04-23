<?php
/**
 * @package All-in-One-SEO-Pack
 */
/**
 * The Robots class.
 */
if ( ! class_exists( 'All_in_One_SEO_Pack_Robots' ) ) {
	class All_in_One_SEO_Pack_Robots extends All_in_One_SEO_Pack_Module {

		function __construct() {
			$this->name   = __( 'Robots.txt', 'all-in-one-seo-pack' );    // Human-readable name of the plugin
			$this->prefix = 'aiosp_robots_';                        // option prefix
			$this->file   = __FILE__;                                    // the current file
			parent::__construct();

			$help_text = array(
				'type' => __( 'Rule Type', 'all-in-one-seo-pack' ),
				'agent'  => __( 'User Agent', 'all-in-one-seo-pack' ),
				'path'       => __( 'Directory Path', 'all-in-one-seo-pack' ),
			);

			$this->default_options = array(
				'usage'              => array(
					'type'    => 'html',
					'label'   => 'none',
					'default' => __( 'Use the rule builder below to add/delete rules.', 'all-in-one-seo-pack' ),
					'save'    => false,
				),
			);

			$this->rule_fields		= array(
				'agent'         => array(
					'name'            => __( 'User Agent', 'all-in-one-seo-pack' ),
					'type'            => 'text',
					'label'           => 'top',
					'save'            => false,
				),
				'type'          => array(
					'name'  => __( 'Rule', 'all-in-one-seo-pack' ),
					'type'  => 'select',
					'initial_options' => array( 'allow' => __( 'Allow', 'all-in-one-seo-pack' ), 'disallow' => __( 'Block', 'all-in-one-seo-pack' ) ),
					'label' => 'top',
					'save'  => false,
				),
				'path'         => array(
					'name'            => __( 'Directory Path', 'all-in-one-seo-pack' ),
					'type'            => 'text',
					'label'           => 'top',
					'save'            => false,
				),
				'Submit'            => array(
					'type'  => 'submit',
					'class' => 'button-primary',
					'name'  => __( 'Add Rule', 'all-in-one-seo-pack' ) . ' &raquo;',
					'style' => 'margin-left: 20px;',
					'label' => 'none',
					'save'  => false,
					'value' => 1,
				),
				'rules'        => array(
					'name' => __( 'Configured Rules', 'all-in-one-seo-pack' ),
					'type' => 'custom',
					'save' => true,
				),
				'robots.txt'        => array(
					'name' => __( 'Robots.txt', 'all-in-one-seo-pack' ),
					'type' => 'custom',
					'save' => true,
				),
			);

			if ( $this->has_physical_file() ) {
				if ( ( is_multisite() && is_network_admin() ) || ( ! is_multisite() && current_user_can( 'manage_options') ) ) {
					$this->default_options['usage']['default'] .= '<p>' . sprintf( __( 'A physical file exists. Do you want to %simport and delete%s it, %sdelete%s it or continue using it?', 'all-in-one-seo-pack' ), '<a href="#" class="aiosp_robots_physical aiosp_robots_import" data-action="import">', '</a>', '<a href="#" class="aiosp_robots_physical aiosp_robots_delete" data-action="delete">', '</a>' ) . '</p>';
				} else {
					$this->default_options['usage']['default'] .= '<p>' . __( 'A physical file exists. This feature cannot be used.', 'all-in-one-seo-pack' ) . '</p>';
				}

				add_action( 'wp_ajax_aioseop_ajax_robots_physical', array( $this, 'ajax_action_physical_file' ) );

				return;
			}

			$this->default_options = array_merge( $this->default_options, $this->rule_fields );

			if ( ! empty( $help_text ) ) {
				foreach ( $help_text as $k => $v ) {
					$this->default_options[ $k ]['help_text'] = $v;
				}
			}

			$this->layout             = array(
				'default' => array(
					'name'    => __( 'Create a Robots.txt File', 'all-in-one-seo-pack' ),
					'options' => array_merge( array( 'usage' ), array_keys( $this->rule_fields ) ),
				),
			);

			// load initial options / set defaults
			$this->update_options();

			add_filter( $this->prefix . 'output_option', array( $this, 'display_custom_options' ), 10, 2 );
			add_filter( $this->prefix . 'update_options', array( $this, 'filter_options' ) );
			add_action( 'wp_ajax_aioseop_ajax_delete_rule', array( $this, 'ajax_delete_rule' ) );
			add_filter( 'robots_txt', array( $this, 'robots_txt' ), 10, 2 );
			add_filter( $this->prefix . 'submit_options', array( $this, 'submit_options'), 10, 2 );
		}

		function submit_options( $submit_options, $location ) {
			unset( $submit_options['Submit'] );
			unset( $submit_options['Submit_Default'] );
			return $submit_options;
		}

		function ajax_action_physical_file() {
			aioseop_ajax_init();
			$action = $_POST['options'];

			switch ( $action ) {
				case 'import':
					if ( ! $this->import_physical_file() ) {
						wp_send_json_success( array( 'message' => __( 'Unable to read file', 'all-in-one-seo-pack' ) ) );
					}
					// fall-through.
				case 'delete':
					if ( ! $this->delete_physical_file() ) {
						wp_send_json_success( array( 'message' => __( 'Unable to delete file', 'all-in-one-seo-pack' ) ) );
					}
					break;
			}

			wp_send_json_success();
		}

		private function import_physical_file() {
			$wp_filesystem = $this->get_filesystem_object();
			$file = trailingslashit( $wp_filesystem->abspath() ) . 'robots.txt';
			if ( ! $wp_filesystem->is_readable( $file ) ) {
				return false;
			}

			$lines = $wp_filesystem->get_contents_array( $file );
			if ( ! $lines ) {
				return true;
			}
			$rules = array();
			$user_agent = null;
			$rule = array();
			foreach ( $lines as $line ) {
				if ( empty( $line ) ) {
					continue;
				}
				$array = array_map( 'trim', explode( ':', $line ) );
				if ( $array && count( $array ) !== 2 ) {
					error_log( "Ignoring $line from robots.txt" );
					continue;
				}
				$operand = $array[0];
				switch ( strtolower( $operand ) ) {
					case 'user-agent':
						$user_agent = $array[1];
						break;
					case 'disallow':
						// fall-through.
					case 'allow':
						$rule[ 'agent' ] = $user_agent;
						$rule[ 'type' ] = $operand;
						$rule[ 'path' ] = $array[1];
						$rule[ 'id' ] = $this->create_rule_id( $rule[ 'type' ], $rule[ 'agent' ], $rule[ 'path' ] );
						break;
				}
				if ( $rule ) {
					$rules[] = $rule;
					$rule = array();
				}
			}

			global $aioseop_options;
			$aioseop_options['modules']["{$this->prefix}options"]["{$this->prefix}rules"] = $rules;
			update_option( 'aioseop_options', $aioseop_options );
			return true;
		}

		private function delete_physical_file() {
			$wp_filesystem = $this->get_filesystem_object();
			$file = trailingslashit( $wp_filesystem->abspath() ) . 'robots.txt';
			return $wp_filesystem->delete( $file );
		}

		private function has_physical_file() {
			$wp_filesystem = $this->get_filesystem_object();
			$file = trailingslashit( $wp_filesystem->abspath() ) . 'robots.txt';
			return $wp_filesystem->exists( $file );
		}

		function robots_txt( $output, $public ) {
			return $output . "\r\n" . $this->get_rules();
		}

		private function get_rules() {
			$rules = '';

			if ( is_multisite() ) {
				// get the network admin rules first.
				$rules .= $this->get_rules_for_blog( $this->get_network_id() );
			} else {
				$rules .= "\r\n" . $this->get_rules_for_blog();
			}

			if ( $this->get_network_id() != get_current_blog_id() ) {
				$rules .= "\r\n" . $this->get_rules_for_blog();
			}

			return $rules;
		}

		private function get_network_id() {
			if ( is_multisite() ) {
				return get_network()->site_id;
			}
			return get_current_blog_id();
		}

		private function get_option_for_blog( $id = null ) {
			if ( is_null( $id ) ) {
				$id = get_current_blog_id();
			}
			if ( is_multisite() ) {
				switch_to_blog( $id );
			}
			$options = get_option('aioseop_options');
			if ( is_multisite() ) {
				restore_current_blog();
			}
			return array_key_exists( "{$this->prefix}options", $options['modules'] ) ? $options['modules']["{$this->prefix}options"] : array();
		}

		private function get_all_rules( $id = null ) {
			$options = $this->get_option_for_blog( $id );
			return array_key_exists( "{$this->prefix}rules", $options ) ? $options[ "{$this->prefix}rules" ] : array();
		}

		private function get_rules_for_blog( $id = null ) {
			$robots		= array();
			$blog_rules	= $this->get_all_rules( $id );
			$rules		= array();
			foreach ( $blog_rules as $rule ) {
				$condition	= sprintf( '%s: %s', $rule['type'], $rule['path'] );
				$agent		= $rule['agent'];
				if ( ! array_key_exists( $agent, $rules ) ) {
					$rules[$agent]	= array();
				}
				$rules[ $agent ][]	= $condition;
			}

			foreach( $rules as $agent => $conditions ) {
				$robots[]	= sprintf( 'User-agent: %s', $agent );
				$robots[]	= implode( "\r\n", $conditions );
				$robots[]	= "";
			}
			return implode( "\r\n", $robots );
		}

		function ajax_delete_rule() {
			aioseop_ajax_init();
			$id = $_POST['options'];

			$blog_rules	= $this->get_all_rules();
			$rules = array();
			foreach ( $blog_rules as $rule ) {
				if ( $id === $rule['id'] ) {
					continue;
				}
				$rules[] = $rule;
			}
			global $aioseop_options;
			$aioseop_options['modules']["{$this->prefix}options"]["{$this->prefix}rules"] = $rules;
			update_option( 'aioseop_options', $aioseop_options );
		}


		/**
		 * Filter options.
		 *
		 * @param $options
		 *
		 * @return mixed
		 */
		function filter_options( $options ) {
			if ( ! empty( $_POST[ $this->prefix . "path" ] ) ) {
				//$blog_rules = json_decode( html_entity_decode( $this->get_all_rules( get_current_blog_id() ) ), true );
				$blog_rules = $this->get_all_rules();
				foreach ( array_keys( $this->rule_fields ) as $field ) {
					$post_field	= $this->prefix . "" . $field;
					if ( ! empty( $_POST[ $post_field ] ) ) {
						$_POST[ $post_field ] = esc_attr( wp_kses_post( $_POST[ $post_field ] ) );
					} else {
						$_POST[ $post_field ] = '';
					}
				}
				$rule	= $this->validate_rule( $blog_rules );
				if ( $rule ) {
					$blog_rules[] = $rule;
					$options[ $this->prefix . "rules" ] = $blog_rules;
				}
			}
			// testing only - to clear the rules.
			//$options[ $this->prefix . "rules" ]=array();
			return $options;
		}

		private function sanitize_path( $path ) {
			// if path does not have a trailing wild card (*), add trailish slash.
			if ( '*' !== substr( $path, -1 ) ) {
				$path = trailingslashit( $path );
			}

			// if path does not have a leading slash, add it.
			if ( '/' !== substr( $path, 0, 1 ) ) {
				$path = '/' . $path;
			}

			// convert everything to lower case.
			$path = strtolower( $path );

			return $path;
		}

		private function create_rule_id( $type, $agent, $path ) {
			return md5( $type . $agent . $path );
		}

		private function validate_rule( $rules ) {
			$network = $this->get_all_rules( $this->get_network_id() );

			// sanitize path.
			$path = $this->sanitize_path( $_POST[ $this->prefix . "path" ] );

			// generate id to check uniqueness and also for purposes of deletion.
			$id = $this->create_rule_id( $_POST[ $this->prefix . "type" ],  $_POST[ $this->prefix . "agent" ],  $path );
			$ids = wp_list_pluck( $rules, 'id' );
			if ( in_array( $id, $ids ) ) {
				return null;
			}

			if ( $network ) {
				$nw_agent_paths = array();
				foreach ( $network as $n ) {
					$nw_agent_paths[] = $n['agent'] . $n['path'];
				}
				// the same rule cannot be duplicated by the Admin.
				$agent_path =  $_POST[ $this->prefix . "agent" ] . $path;
				if ( in_array( $agent_path, $nw_agent_paths ) ) {
					return null;
				}

				// an identical path as specified by Network Admin cannot be overriden by Admin.
				$nw_paths = wp_list_pluck( $network, 'path' );
				if ( in_array( $path, $nw_paths ) ) {
					return null;
				}

				// a wild-carded path specified by the Admin cannot override a path specified by Network Admin.
				$path_no_wildcards = str_replace( '*', '', $path );
				foreach ( $nw_paths as $nw_path ) {
					if ( strpos( $nw_path, $path_no_wildcards ) !== false ) {
						return null;
					}
				}
			}

			$rule	= array(
					'type' => ucwords( $_POST[ $this->prefix . "type" ] ),
					'agent' => $_POST[ $this->prefix . "agent" ],
					'path' => $path,
					'id' => $id,
			);
			return $rule;
		}

		private function reorder_rules( $rules ) {
			uasort( $rules, array( $this, 'sort_rules' ) );
			return $rules;
		}

		function sort_rules( $a, $b ) {
			return $a['agent'] > $b['agent'];
		}

		private function get_display_rules( $rules ) {
			$buf = '';
			if ( ! empty( $rules ) ) {
				$rules = $this->reorder_rules( $rules );
				$buf = "<table class='aioseop_table' cellpadding=0 cellspacing=0>\n";
				foreach ( $rules as $v ) {
					$buf .= "\t<tr><td><a href='#' class='aiosp_delete aiosp_robots_delete_rule' data-id='{$v['id']}'></a></td><td>{$v['agent']}</td><td>{$v['type']}</td><td>{$v['path']}</td></tr>\n";
				}
				$buf .= "</table>\n";
			}
			return $buf;
		}

		/**
		 * Custom settings.
		 *
		 * Displays boxes in a table layout.
		 *
		 * @param $buf
		 * @param $args
		 *
		 * @return string
		 */
		function display_custom_options( $buf, $args ) {
			if ( "{$this->prefix}rules" === $args['name'] ) {
				$buf .= "<div id='{$this->prefix}rules'>";
				$buf .= $this->get_display_rules( $args['value'] );
				$buf .= '</div>';
			}

			if ( "{$this->prefix}robots.txt" === $args['name'] ) {
				$buf .= "<textarea disabled id='{$this->prefix}robot-txt' class='large-text robots-text' rows='15'>";
				// disable header warnings.
				error_reporting(0);
				ob_start();
				do_action( 'do_robots' );
				$buf .= ob_get_clean();
				$buf .= "</textarea>";
			}

			$args['options']['type'] = 'hidden';
			if ( ! empty( $args['value'] ) ) {
				$args['value'] = wp_json_encode( $args['value'] );
			} else {
				$args['options']['type'] = 'html';
			}
			if ( empty( $args['value'] ) ) {
				$args['value'] = '';
			}
			$buf .= $this->get_option_html( $args );

			return $buf;
		}
	}
}
