<?php

/**
 * @package All-in-One-SEO-Pack
 */
class aiosp_metaboxes {

	/**
	 * aiosp_metaboxes constructor.
	 */
	function __construct() {
		//construct

	}


	/**
	 * @param $add
	 * @param $meta
	 */
	static function display_extra_metaboxes( $add, $meta ) {
		echo "<div class='aioseop_metabox_wrapper' >";
		switch ( $meta['id'] ) {
			case 'aioseop-about':
				?>
				<div class="aioseop_metabox_text">
					<p><h2
						style="display:inline;"><?php echo AIOSEOP_PLUGIN_NAME; ?></h2><?php sprintf( __( 'by %s of %s.', 'all-in-one-seo-pack' ), 'Michael Torbert', '<a target="_blank" title="Semper Fi Web Design"
							href="https://semperfiwebdesign.com/">Semper Fi Web Design</a>' ); ?>.</p>
					<?php
					global $current_user;
					$user_id = $current_user->ID;
					$ignore  = get_user_meta( $user_id, 'aioseop_ignore_notice' );
					if ( ! empty( $ignore ) ) {
						$qa = Array();
						wp_parse_str( $_SERVER['QUERY_STRING'], $qa );
						$qa['aioseop_reset_notices'] = 1;
						$url                         = '?' . build_query( $qa );
						echo '<p><a href="' . $url . '">' . __( 'Reset Dismissed Notices', 'all-in-one-seo-pack' ) . '</a></p>';
					}
					if ( ! AIOSEOPPRO ) {
						?>
						<p>
							<strong><?php echo aiosp_common::get_upgrade_hyperlink( 'side', __( 'Pro Version', 'all-in-one-seo-pack' ), __( 'UPGRADE TO PRO VERSION', 'all-in-one-seo-pack' ), '_blank' ); ?></strong>
						</p>
					<?php } ?>
				</div>
				<?php
			case 'aioseop-donate':
				?>
				<div>

					<?php if ( ! AIOSEOPPRO ) { ?>
						<div class="aioseop_metabox_text">
							<p>
Put info about Pro here /placeholder
							</p>
						</div>
					<?php } ?>

					<div class="aioseop_metabox_feature">

						<a target="_blank" title="<?php _e( 'Follow us on Facebook', 'all-in-one-seo-pack' ); ?>"
						   href="https://www.facebook.com/aioseopack/"><span
								class="aioseop_follow_button aioseop_facebook_follow"></span></a>
						<a target="_blank" title="<?php _e( 'Follow us on Twitter', 'all-in-one-seo-pack' ); ?>"
						   href="https://twitter.com/aioseopack"><span
								class="aioseop_follow_button aioseop_twitter_follow"></span></a>
					</div><?php

					$aiosp_trans = new AIOSEOP_Translations();
					// Eventually if nothing is returned we should just remove this section.

					if ( get_locale() != 'en_US' ) { ?>
						<div><strong>
								<?php

								if ( $aiosp_trans->percent_translated < 100 ) {

									/* translators: %1$s expands to the number of languages All in One SEO Pack has been translated into. $2%s to the percentage translated of the current language, $3%s to the language name, %4$s and %5$s to anchor tags with link to translation page at translate.wordpress.org  */
									printf( __(
										'All in One SEO Pack has been translated into %1$s languages, but currently the %3$s translation is only %2$s percent complete. %4$s Click here %5$s to help get it to 100 percent.', 'all-in-one-seo-pack' ),
										$aiosp_trans->translated_count,
										$aiosp_trans->percent_translated,
										$aiosp_trans->name,
										"<a href=\"$aiosp_trans->translation_url\" target=\"_BLANK\">",
										'</a>' );
								}

								?>
							</strong></div>
					<?php } ?>
				</div>
				<?php
				break;
			case 'aioseop-list':
				?>
				<div class="aioseop_metabox_text">
					<form
						action="https://semperfiwebdesign.us1.list-manage.com/subscribe/post?u=794674d3d54fdd912f961ef14&amp;id=af0a96d3d9"
						method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
						target="_blank">
						<h2><?php _e( 'Join our mailing list for tips, tricks, and WordPress secrets.', 'all-in-one-seo-pack' ); ?></h2>
						<p>
							<i><?php _e( 'Sign up today and receive a free copy of the e-book 5 SEO Tips for WordPress ($39 value).', 'all-in-one-seo-pack' ); ?></i>
						</p>
						<p><input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL"
						          placeholder="Email Address">
							<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"
							       class="btn"></p>
					</form>
				</div>
				<?php
				break;
			case 'aioseop-support':
				?>
				<div class="aioseop_metabox_text">
					<p>
					<div class="aioseop_icon aioseop_file_icon"></div>
					<a target="_blank"
					   href="https://semperplugins.com/documentation/"><?php _e( 'Read the All in One SEO Pack user guide', 'all-in-one-seo-pack' ); ?></a></p>
					<p>
					<div class="aioseop_icon aioseop_support_icon"></div>
					<a target="_blank"
					   title="<?php _e( 'All in One SEO Pro Plugin Support Forum', 'all-in-one-seo-pack' ); ?>"
					   href="https://semperplugins.com/support/"><?php _e( 'Access our Premium Support Forums', 'all-in-one-seo-pack' ); ?></a></p>
					<p>
					<div class="aioseop_icon aioseop_cog_icon"></div>
					<a target="_blank" title="<?php _e( 'All in One SEO Pro Plugin Changelog', 'all-in-one-seo-pack' ); ?>"
					   href="<?php if ( AIOSEOPPRO ) {
						   echo 'https://semperplugins.com/documentation/all-in-one-seo-pack-pro-changelog/';
					   } else {
						   echo 'https://semperfiwebdesign.com/blog/all-in-one-seo-pack/all-in-one-seo-pack-release-history/';
					   } ?>"><?php _e( 'View the Changelog', 'all-in-one-seo-pack' ); ?></a></p>
					<p>
					<div class="aioseop_icon aioseop_youtube_icon"></div>
					<a target="_blank"
					   href="https://semperplugins.com/doc-type/video/"><?php _e( 'Watch video tutorials', 'all-in-one-seo-pack' ); ?></a></p>
					<p>
					<div class="aioseop_icon aioseop_book_icon"></div>
					<a target="_blank"
					   href="https://semperplugins.com/documentation/quick-start-guide/"><?php _e( 'Getting started? Read the Beginners Guide', 'all-in-one-seo-pack' ); ?></a></p>
				</div>
				<?php
				break;
		}
		echo '</div>';
	}


}
