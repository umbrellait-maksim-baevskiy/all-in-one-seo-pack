<?php
/**
 * Class AIOSEOP_Notices_TestCase
 *
 * @package All_in_One_SEO_Pack
 * @subpackage AIOSEOP_Notices
 * @since 2.4.5.1
 *
 * @group AIOSEOP_Notices
 * @group Admin
 * @group Notices
 */

/**
 * AIOSEOP Notices Testcase
 */
require_once AIOSEOP_UNIT_TESTING_DIR . '/base/class-aioseop-notices-testcase.php';

/**
 * Class Test Notice - Sitemap Index
 *
 * Displays when sitemap index is over 1000 and is being indexed.
 *
 * @since 3.0
 *
 * @package classes\AIOSEOP_Notices\notices
 */
class Test_Notice_SitemapIndexes extends AIOSEOP_Notices_TestCase {

	/**
	 * Mock Single Notice
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function mock_notice() {
		return aioseop_notice_sitemap_indexes();
	}
}
