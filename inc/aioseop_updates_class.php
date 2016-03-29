<?php
/**
 * @package All-in-One-SEO-Pack
 */

/**
 * AIOSEOP Updates class. 
 *
 * Handle detection of new plugin version updates, migration of old settings,
 * new WP core feature support, etc.
 */
class aioseop_updates {
	/**
	 * Constructor
	 */
	function __construct() {
	}

	function version_updates() {
		global $aiosp, $aioseop_options;

		// See if we are running a newer version than last time we checked.
		if ( !isset( $aioseop_options ) || empty( $aioseop_options ) || !isset( $aioseop_options['update_version'] ) || version_compare( $aioseop_options['update_version'], AIOSEOP_VERSION, '<' ) ) {
			// Last known running plugin version
			$last_updated_version = isset( $aioseop_options['update_version'] ) ? $aioseop_options['update_version'] : '0.0';

			// Do upgrades based on previous version
			$this->do_version_updates( $last_updated_version );

			// Save the current plugin version as the new update_version
			$aioseop_options['update_version'] = AIOSEOP_VERSION;
			update_option( 'aioseop_options', $aioseop_options );
		}

		/**
		 * Perform updates that are dependent on external factors, not 
		 * just the plugin version.
		 */
		$this->do_feature_updates();

	}

	function do_version_updates( $old_version ) {
		global $aioseop_options;

		if (  
			( !AIOSEOPPRO && version_compare( $old_version, '2.3.3', '<' ) ) ||
			( AIOSEOPPRO && version_compare( $old_version, '2.4.3', '<' ) ) 
		   ) {
	   		$this->bad_bots_201603();
		}

		/*
		if ( 
			( !AIOSEOPPRO && version_compare( $old_version, '2.4', '<' ) ) ||
			( AIOSEOPPRO && version_compare( $old_version, '2.5', '<' ) ) 
		   ) {
			// Do changes needed for 2.4/2.5... etc
		}
		*/
	}

	function do_feature_updates() {
		global $aioseop_options;

		// We don't need to check all the time. Use a transient to limit frequency.
		if ( get_site_transient( 'aioseop_update_check_time' ) ) return;

		// We haven't checked recently. Reset the timestamp, timeout 6 hours.
		set_site_transient( 'aioseop_update_check_time', time(), apply_filters( 'aioseop_update_check_time', 3600 * 6 ) );

		if ( ! ( isset( $aioseop_options['update_options']['term_meta_migrated'] ) && 
		 $aioseop_options['update_options']['term_meta_migrated'] === true ) ) {
	   		$this->migrate_term_meta_201603();
		}
	}

	/*
	 * Functions for specific version milestones
	 */

	/**
	 * Remove overzealous 'DOC' entry which is causing false-positive bad 
	 * bot blocking.
	 */
	function bad_bots_201603() {
		global $aiosp, $aioseop_options;
		// Remove 'DOC' from bad bots list to avoid false positives
		if ( isset( $aioseop_options['modules']['aiosp_bad_robots_options']['aiosp_bad_robots_blocklist'] ) ) {
			$list = $aioseop_options['modules']['aiosp_bad_robots_options']['aiosp_bad_robots_blocklist'];
			$list = str_replace(array( "DOC\n", "DOC\r\n"), '', $list);
			$aioseop_options['modules']['aiosp_bad_robots_options']['aiosp_bad_robots_blocklist'] = $list;
			update_option( 'aioseop_options', $aioseop_options );
			$aiosp->update_class_option( $aioseop_options );

			if ( isset( $aioseop_options['modules']['aiosp_bad_robots_options']['aiosp_bad_robots_htaccess_rules'] ) ){
				$aiosp_reset_htaccess = new All_in_One_SEO_Pack_Bad_Robots;
				$aiosp_reset_htaccess->generate_htaccess_blocklist();
			}
			
			if ( !isset( $aioseop_options['modules']['aiosp_bad_robots_options']['aiosp_bad_robots_htaccess_rules'] ) && extract_from_markers( get_home_path() . '.htaccess', 'Bad Bot Blocker' ) ){
				insert_with_markers( get_home_path() . '.htaccess', 'Bad Bot Blocker', '' );
			}
		}
	}

	/**
	 * Migrate old term meta to use native WP functions.
	 */
	function migrate_term_meta_201603() {
		global $wpdb;
		/*
		// Check WP db version to be sure term meta tables exist
		// if not, bail
		// if yes:
		//   migrate old meta
		//   update 'term_meta_migrated' option flag
		*/
		// Pro-only feature
		if ( ! AIOSEOPPRO ) {
			return false;
		}

		// Bail if native WP term meta table is not installed.
        if ( intval( get_option( 'db_version' ) ) < 34370 ) {
            return false;
        }

        /**
         * Migrate tax_meta_% entries from options table
         */
        $query = "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'tax_meta_%'";
        $taxmeta = $wpdb->get_results( $query );
        if ( is_array( $taxmeta ) ) {
	        foreach ( $taxmeta as $meta ) {
	        	$name = $meta->option_name;
	        	$mvals = maybe_unserialize( $meta->option_value );
	        	$termid = intval( str_replace( 'tax_meta_', '', $name ) );
	        	foreach( $mvals as $mkey => $mval ) {
	        		// Set 'unique' param to TRUE so we don't overwrite existing
	        		add_term_meta( $termid, $mkey, $mval, true );
	        	}
	        	// Done with the old 'tax_meta_foo' option now.
	        	delete_option( $name );
	        }
	    }

        /**
         * Compat with Taxonomy Metadata plugin. Use an outer join with exclusion
         * to migrate entries from the plugin's 'taxonomymeta' table to the 
         * native WordPress 'termmeta' table, if a corresponding entry doesn't
         * already exist.
         */
        $table_name = "{$wpdb->prefix}taxonomymeta";
        if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ) {
        	$query = "INSERT INTO {$wpdb->termmeta} (term_id, meta_key, meta_value)
        	  SELECT taxm.taxonomy_id as term_id, taxm.meta_key as meta_key, taxm.meta_value as meta_value 
        	  FROM {$wpdb->termmeta} termm 
        	  RIGHT JOIN {$wpdb->taxonomymeta} taxm 
        	  ON (termm.term_id=taxm.taxonomy_id AND termm.meta_key=taxm.meta_key) 
        	  WHERE termm.meta_id IS NULL";

        	@$wpdb->query( $query );
    	}

        $aioseop_options['update_options']['term_meta_migrated'] = true;
        update_option('aioseop_options', $aioseop_options);
	}

}
