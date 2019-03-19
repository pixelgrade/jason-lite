<?php
	/**
	 * Jason Lite Theme About Page logic.
	 *
	 * @package Jason Lite
	 */

	function jasonlite_admin_setup() {
		/**
		 * Load the About page class
		 */
		require_once 'ti-about-page/class-ti-about-page.php';

		/*
		* About page instance
		*/
		$config = array(
			// Menu name under Appearance.
			'menu_name'               => esc_html__( 'About Jason Lite', 'jason-lite' ),
			// Page title.
			'page_name'               => esc_html__( 'About Jason Lite', 'jason-lite' ),
			// Main welcome title
			'welcome_title'         => sprintf( esc_html__( 'Welcome to %s! - Version ', 'jason-lite' ), 'Jason Lite' ),
			// Main welcome content
			'welcome_content'       => esc_html__( ' Jason Lite is a free magazine-style theme with clean type, smart layouts and a design flexibility that makes it perfect for publishers of all kinds.', 'jason-lite' ),
			/**
			 * Tabs array.
			 *
			 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
			 * the will be the name of the function which will be used to render the tab content.
			 */
			'tabs'                    => array(
				'getting_started'  => esc_html__( 'Getting Started', 'jason-lite' ),
				'recommended_actions' => esc_html__( 'Recommended Actions', 'jason-lite' ),
				'recommended_plugins' => esc_html__( 'Useful Plugins','jason-lite' ),
				'support'       => esc_html__( 'Support', 'jason-lite' ),
				'changelog'        => esc_html__( 'Changelog', 'jason-lite' ),
				'free_pro'         => esc_html__( 'Free VS PRO', 'jason-lite' ),
			),
			// Support content tab.
			'support_content'      => array(
				'first' => array (
					'title' => esc_html__( 'Contact Support','jason-lite' ),
					'icon' => 'dashicons dashicons-sos',
					'text' => __( 'We want to make sure you have the best experience using Jason Lite. If you <strong>do not have a paid upgrade</strong>, please post your question in our community forums.','jason-lite' ),
					'button_label' => esc_html__( 'Contact Support','jason-lite' ),
					'button_link' => esc_url( 'https://wordpress.org/support/theme/jason-lite' ),
					'is_button' => true,
					'is_new_tab' => true
				),
				'second' => array(
					'title' => esc_html__( 'Documentation','jason-lite' ),
					'icon' => 'dashicons dashicons-book-alt',
					'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Jason Lite.','jason-lite' ),
					'button_label' => esc_html__( 'Read The Documentation','jason-lite' ),
					'button_link' => 'https://pixelgrade.com/jason-lite-documentation/',
					'is_button' => false,
					'is_new_tab' => true
				)
			),
			// Getting started tab
			'getting_started' => array(
				'first' => array(
					'title' => esc_html__( 'Go to Customizer','jason-lite' ),
					'text' => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.','jason-lite' ),
					'button_label' => esc_html__( 'Go to Customizer','jason-lite' ),
					'button_link' => esc_url( admin_url( 'customize.php' ) ),
					'is_button' => true,
					'recommended_actions' => false,
					'is_new_tab' => true
				),
				'second' => array (
					'title' => esc_html__( 'Recommended actions','jason-lite' ),
					'text' => esc_html__( 'We have compiled a list of steps for you, to take make sure the experience you will have using one of our products is very easy to follow.','jason-lite' ),
					'button_label' => esc_html__( 'Recommended actions','jason-lite' ),
					'button_link' => esc_url( admin_url( 'themes.php?page=jason-lite-welcome&tab=recommended_actions' ) ),
					'button_ok_label' => esc_html__( 'You are good to go!','jason-lite' ),
					'is_button' => false,
					'recommended_actions' => true,
					'is_new_tab' => false
				),
				'third' => array(
					'title' => esc_html__( 'Read the documentation','jason-lite' ),
					'text' => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Jason Lite.','jason-lite' ),
					'button_label' => esc_html__( 'Documentation','jason-lite' ),
					'button_link' => 'https://pixelgrade.com/jason-lite-documentation/',
					'is_button' => false,
					'recommended_actions' => false,
					'is_new_tab' => true
				)
			),
			// Free vs pro array.
			'free_pro'                => array(
				'free_theme_name'     => 'Jason Lite',
				'pro_theme_name'      => 'Jason PRO',
				'pro_theme_link'      => 'https://pixelgrade.com/themes/jason-lite/?utm_source=jason-lite-clients&utm_medium=about-page&utm_campaign=jason-lite#pro',
				'get_pro_theme_label' => sprintf( __( 'View %s', 'jason-lite' ), 'Jason Pro' ),
				'features'            => array(
					array(
						'title'       => esc_html__( 'Daring Design for Devoted Readers', 'jason-lite' ),
						'description' => esc_html__( 'With a unique grid layout, balanced range of diverse post layouts, and thoughtful choice of type and whitespace, Jason has a sharp and lightweight look. Precise animations set the right tone for your stories.', 'jason-lite' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Mobile-Ready For All Devices', 'jason-lite' ),
						'description' => esc_html__( 'Jason makes room for your readers to enjoy your articles on the go, no matter the device their using. We lend a hand by showcasing it beautifully to your audience.', 'jason-lite' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Widgetized Sidebar To Keep Attention', 'jason-lite' ),
						'description' => esc_html__( 'Jason comes with a widget-based flexible system which allows you to add your favorite widgets all over the Front Page and on the right side of your articles.', 'jason-lite' ),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Flexible Color Scheme', 'jason-lite' ),
						'description' => esc_html__( 'Match your unique style in an easy and smart way by using an intuitive interface that you can fine-tune it until it fully represents you. Browse our predefined color palettes or create your own and everything will be reflected live back to you.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Advanced Typography Settings', 'jason-lite' ),
						'description' => esc_html__( 'Adjust your fonts by taking advantage of a playground with 600+ fonts varieties you can wisely choose from at any moment.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Custom Logo, Styles, and Widgets', 'jason-lite' ),
						'description' => esc_html__( 'Jason includes a custom logo creator, optional color treatments for post titles, custom typography styles, and others. It also adds distinctive styling for various widgets in the sidebar (e.g. Quote or Popular posts).', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Interactive Content Layout', 'jason-lite' ),
						'description' => esc_html__( 'Stand out with the help of Jason&#39;s smart grid layout, strong use of white space, and sprinkling of colors that allows you to highlight a wide variety of content &#45; from posts and pictures to links and quotes.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Custom Archive Page and Content Filtering', 'jason-lite' ),
						'description' => esc_html__( 'Jason features a custom archive page that displays your latest posts and lets visitors sort posts by month, tag, or category. This acts similar to a sitemap, offering your readers extra ways to explore your content.','jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'Premium Customer Support', 'jason-lite' ),
						'description' => esc_html__( 'We offer customer support and assistance to help you get the best results in due time. We know our products inside-out and we can lend a hand to help you save resources of all kinds.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'In&#45;depth documentation', 'jason-lite' ),
						'description' => esc_html__( 'We give you full access to an in-depth documentation to get the job done as quickly as possible. We don\'t stay in your way because we know you can make it too.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__( 'No Credit Footer Link', 'jason-lite' ),
						'description' => esc_html__( 'You can easily remove the &#34;Theme: Jason Lite by Pixelgrade&#34; copyright from the footer area and make the theme yours from start to finish.', 'jason-lite' ),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
				),
			),
			// Plugins array.
			'recommended_plugins'        => array(
				'already_activated_message' => esc_html__( 'Already activated', 'jason-lite' ),
				'version_label' => esc_html__( 'Version: ', 'jason-lite' ),
				'install_label' => esc_html__( 'Install and Activate', 'jason-lite' ),
				'activate_label' => esc_html__( 'Activate', 'jason-lite' ),
				'deactivate_label' => esc_html__( 'Deactivate', 'jason-lite' ),
				'content'                   => array(
					array(
						'slug' => 'jetpack'
					),
					array(
						'slug' => 'wordpress-seo'
					),
//				array(
//					'slug' => 'gridable'
//				)
				),
			),
			// Required actions array.
			'recommended_actions'        => array(
				'install_label' => esc_html__( 'Install and Activate', 'jason-lite' ),
				'activate_label' => esc_html__( 'Activate', 'jason-lite' ),
				'deactivate_label' => esc_html__( 'Deactivate', 'jason-lite' ),
				'content'            => array(
					'jetpack' => array(
						'title'       => 'Jetpack',
						'description' => __( 'It is highly recommended that you install Jetpack so you can enable the <b>Portfolio</b> content type for adding and managing your projects. Plus, Jetpack provides a whole host of other useful things for you site.', 'jason-lite' ),
						'check'       => defined( 'JETPACK__VERSION' ),
						'plugin_slug' => 'jetpack',
						'id' => 'jetpack'
					),
				),
			),
		);
		TI_About_Page::init( $config );
	}
	add_action('after_setup_theme', 'jasonlite_admin_setup');