<div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'wpcf7-welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
	<a class="welcome-panel-close" href="<?php echo esc_url( menu_page_url( 'wpcf7', false ) ); ?>"><?php echo esc_html( __( 'Dismiss', 'contact-form-7' ) ); ?></a>

	<div class="welcome-panel-content">
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Support All in One SEO Pack', 'contact-form-7' ) ); ?></h3>
				<p class="message"><?php echo esc_html( __( "There are may ways you can help support All in One SEO Pack.", 'contact-form-7' ) ); ?></p>
				<p class="message aioseop-message"><?php echo esc_html( __( "If you enjoy using our plugin and find it useful, please consider making a donation.", 'contact-form-7' ) ); ?></p>
				<p class="call-to-action"><?php echo wpcf7_link( __( 'http://contactform7.com/donate/', 'contact-form-7' ), __( 'Donate', 'contact-form-7' ), array( 'class' => 'button button-primary' ) ); ?></p>
				<p class="message aioseop-message"><?php echo esc_html( __( "You can sign up to help translate All in One SEO Pack into your language.", 'contact-form-7' ) ); ?></p>
				<p class="call-to-action"><?php echo wpcf7_link( __( 'https://translate.wordpress.org/projects/wp-plugins/all-in-one-seo-pack', 'contact-form-7' ), __( 'Sign Up', 'contact-form-7' ), array( 'class' => 'button button-primary' ) ); ?></p>
				<p class="message aioseop-message"><?php echo esc_html( __( "Or you can register to become a beta tester and help test new features.", 'contact-form-7' ) ); ?></p>
				<p class="call-to-action"><?php echo wpcf7_link( __( 'https://semperplugins.com/contact/', 'contact-form-7' ), __( 'Register', 'contact-form-7' ), array( 'class' => 'button button-primary' ) ); ?></p>
			</div>

			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Get Started', 'contact-form-7' ) ); ?></h3>
				<ul>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/quick-start-guide/', 'contact-form-7' ), __( 'Beginners Guide for All in One SEO Pack', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/beginners-guide-to-xml-sitemaps/', 'contact-form-7' ), __( 'Beginners Guide for XML Sitemap module', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/beginners-guide-to-social-meta/', 'contact-form-7' ), __( 'Beginners Guide for Social Meta module', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/top-tips-for-good-on-page-seo/', 'contact-form-7' ), __( 'Tips for good on-page SEO', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/quality-guidelines-for-seo-titles-and-descriptions/', 'contact-form-7' ), __( 'Quality guidelines for SEO titles and descriptions', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/quality-guidelines-for-seo-titles-and-descriptions/', 'contact-form-7' ), __( 'Submit an XML Sitemap to Google', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/setting-up-google-analytics/', 'contact-form-7' ), __( 'Set up Google Analytics', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>

			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Did You Know?', 'contact-form-7' ) ); ?></h3>
				<ul>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/documentation/', 'contact-form-7' ), __( 'We have complete documentation on every setting and feature', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/videos/', 'contact-form-7' ), __( 'You can get access to video tutorials about SEO with the Pro version', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'https://semperplugins.com/all-in-one-seo-pack-pro-version/?loc=aio_welcome', 'contact-form-7' ), __( 'You can control SEO on categories, tags and custom taxonomies with the Pro version', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
