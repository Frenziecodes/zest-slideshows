<?php
/**
 * Admin settings class
 */
if ( ! class_exists( 'Hizzle_Slideshows_Admin_Settings' ) ) {
	/**
	 * Class Hizzle_Slideshows_Admin_Settings
	 *
	 * This class is used to manage the settings of the plugin.
	 */
	class Hizzle_Slideshows_Admin_Settings {
		private $settings = array();

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_submenu' ) );
			$this->register_settings( array() );
			add_action( 'admin_init', array( $this, 'initialize_settings' ) );
		}

		/**
		 * Add submenu for settings.
		 */
		public function add_submenu() {
			add_submenu_page(
				hizzle_slideshows_top_level_menu_slug(),
				__( 'Settings', 'hizzle-slideshows' ),
				__( 'Settings', 'hizzle-slideshows' ),
				'manage_options',
				'hizzle_slideshows_settings',
				array( $this, 'display_settings_page' )
			);
		}

		/**
		 * Register settings.
		 *
		 * @param array $registered_settings Array of registered settings.
		 * @return array
		 */
		public function register_settings( $registered_settings ) {
			$new_settings = array(
				array(
					'id'      => 'slideshow_creator_role',
					'title'   => __( 'Slideshow creator role', 'hizzle-slideshows' ),
					'desc'    => __( 'Manage who can create your slideshows', 'hizzle-slideshows' ),
					'default' => '',
					'type'    => 'select',
					'choices' => $this->get_roles(),
					'tab'     => 'general',
					'section' => __( 'Admin', 'hizzle-slideshows' )
				),
				// Add more settings as needed in the future
			);

			// Allow modification of the settings array through a filter hook
			$new_settings = apply_filters( 'hizzle_slideshows_admin_settings', $new_settings );

			$this->settings = $new_settings;

			return $this->settings;
		}

		/**
		 * Initialize settings.
		 */
		public function initialize_settings() {
			foreach ( $this->settings as $setting ) {
				register_setting( 'hizzle_slideshows_settings', $setting['id'] );
			}
		}

		/**
		 * Callback function to display the settings page.
		 */
		public function display_settings_page() {
			// You can add your settings page content here.
			?>
			<div class="wrap">
				<h2><?php _e( 'Hizzle Slideshows Settings', 'hizzle-slideshows' ); ?></h2>
				<form method="post" action="options.php">

					<?php foreach ( $this->settings as $setting ) : ?>
						<div class="hizzle-settings-field">
							<label for="<?php echo $setting['id']; ?>"><?php echo $setting['title']; ?></label>
							<?php if ( $setting['type'] === 'select' ) : ?>
								<select name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>">
									<?php foreach ( $setting['choices'] as $value => $label ) : ?>
										<option value="<?php echo $value; ?>" <?php selected( get_option( $setting['id'] ), $value ); ?>><?php echo $label; ?></option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<input type="<?php echo $setting['type']; ?>" name="<?php echo $setting['id']; ?>" id="<?php echo $setting['id']; ?>" value="<?php echo esc_attr( get_option( $setting['id'], $setting['default'] ) ); ?>" />
							<?php endif; ?>
							<p class="description"><?php echo $setting['desc']; ?></p>
						</div>
					<?php endforeach; ?>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}
		
		/**
		 * Dummy function to retrieve roles.
		 * You should replace this with your actual function to get roles.
		 */
		private function get_roles() {
			return array(
				'hs-default' => 'No Default',
				'role2' => 'Role 2',
			);
		}
	}
}
new Hizzle_Slideshows_Admin_Settings();
