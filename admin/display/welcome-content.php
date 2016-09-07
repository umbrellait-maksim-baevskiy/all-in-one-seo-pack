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
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/getting-started-with-contact-form-7/', 'contact-form-7' ), __( 'Getting Started with Contact Form 7', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/admin-screen/', 'contact-form-7' ), __( 'Admin Screen', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/tag-syntax/', 'contact-form-7' ), __( 'How Tags Work', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/setting-up-mail/', 'contact-form-7' ), __( 'Setting Up Mail', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>

			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Did You Know?', 'contact-form-7' ) ); ?></h3>
				<ul>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/spam-filtering-with-akismet/', 'contact-form-7' ), __( 'Spam Filtering with Akismet', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/save-submitted-messages-with-flamingo/', 'contact-form-7' ), __( 'Save Messages with Flamingo', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/selectable-recipient-with-pipes/', 'contact-form-7' ), __( 'Selectable Recipient with Pipes', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/tracking-form-submissions-with-google-analytics/', 'contact-form-7' ), __( 'Tracking with Google Analytics', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
