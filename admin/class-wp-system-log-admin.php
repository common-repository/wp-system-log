<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wp_System_Log
 * @subpackage Wp_System_Log/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_System_Log
 * @subpackage Wp_System_Log/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wp_System_Log_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$admin_page = filter_input( INPUT_GET, 'page' );
		if ( isset( $admin_page ) && ( 'wpsl-setting-log' === $admin_page ) ) {
			wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-system-log-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$admin_page = filter_input( INPUT_GET, 'page' );
		if ( isset( $admin_page ) && ( 'wpsl-setting-log' === $admin_page ) ) {
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-system-log-admin.js', array( 'jquery' ) );
				wp_localize_script(
					$this->plugin_name,
					'wpsl_admin_js_object',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'nonce'   => wp_create_nonce( 'ajax-nonce' ),
					)
				);
		}

	}

	/**
	 * Actions performed to add an admin menu page
	 */
	public function wpsl_add_admin_page() {
		// add_menu_page( __( 'WordPress Complete System Log', 'wp-system-log' ), __( 'System Log', 'wp-system-log' ), 'manage_options', $this->plugin_name, array( $this, 'wpsl_admin_menu_page_content' ), 'dashicons-welcome-write-blog' );

		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'wp-system-log' ), esc_html__( 'WB Plugins', 'wp-system-log' ), 'manage_options', 'wbcomplugins', array( $this, 'wpsl_admin_menu_page_content' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'wp-system-log' ), esc_html__( 'General', 'wp-system-log' ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'WordPress System Log', 'wp-system-log' ), esc_html__( 'System Log', 'wp-system-log' ), 'manage_options', 'wpsl-setting-log', array( $this, 'wpsl_admin_menu_page_content' ) );
	}

	/**
	 * Actions performed to create a menu page content
	 */
	public function wpsl_admin_menu_page_content() {
		// $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wp-welcome-log';
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wp-welcome-log';
		?>
		<div class="wrap">
			<div class="blpro-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<h1 class="wbcom-plugin-heading">
					<?php esc_attr_e( 'WordPress System Log', 'wp-system-log' ); ?>
				</h1>
			</div>
			<div class="wbcom-admin-settings-page">
				<div class="wbcom-tabs-section">
					<?php $this->wpsl_plugin_settings_tabs(); ?>
				</div>
				<div class="wbcom-tab-content">
					<?php do_settings_sections( $tab ); ?>
				</div>
			</div>
		</div> 
		<?php
	}

	/**
	 * Actions performed to create tabs on the submenu page
	 */
	public function wpsl_plugin_settings_tabs() {
		// $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'wp-welcome-log';
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wp-welcome-log';
		$tab_html    = '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $this->plugin_settings_tabs as $wss_tab => $wss_name ) {
			$class = ( $wss_tab === $current_tab ) ? 'nav-tab-active' : '';
			$page  = 'wpsl-setting-log';
			if ( 'email' === $wss_tab ) {
				$page = 'wc-settings';
			}
			$tab_html .= '<li><a id="' . $wss_tab . '" class="nav-tab ' . $class . '" href="?page=' . $page . '&tab=' . $wss_tab . '">' . $wss_name . '</a></li>';
		}
		$tab_html .= '</div></ul></div>';
		echo wp_kses_post( $tab_html );
	}

	/**
	 * Welcome Log Tab
	 */
	public function wpsl_welcome_info() {
		$this->plugin_settings_tabs['wp-welcome-log'] = __( 'Welcome', 'wp-system-log' );
		register_setting( 'wp-welcome-log', 'wp-welcome-log' );
		add_settings_section( 'wp-welcome-log-server-section', ' ', array( &$this, 'wpsl_welcome_log_content' ), 'wp-welcome-log' );
	}

	/**
	 * Welcome Log Tab Content
	 */
	public function wpsl_welcome_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-welcome-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-welcome-log.php';
		}
	}

	/**
	 * Server Log Tab
	 */
	public function wpsl_server_info() {
		$this->plugin_settings_tabs['wp-system-log'] = __( 'Server', 'wp-system-log' );
		register_setting( 'wp-system-log', 'wp-system-log' );
		add_settings_section( 'wp-system-log-server-section', ' ', array( &$this, 'wpsl_server_log_content' ), 'wp-system-log' );
	}

	/**
	 * Server Log Tab Content
	 */
	public function wpsl_server_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-server-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-server-log.php';
		}
	}

	/**
	 * WP Log Tab
	 */
	public function wpsl_wp_info() {
		$this->plugin_settings_tabs['wordpress-log'] = __( 'WordPress', 'wp-system-log' );
		register_setting( 'wordpress-log', 'wordpress-log' );
		add_settings_section( 'wp-system-log-wp-section', ' ', array( &$this, 'wpsl_wp_log_content' ), 'wordpress-log' );
	}

	/**
	 * WP Log Tab Content
	 */
	public function wpsl_wp_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-wp-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-wp-log.php';
		}
	}

	/**
	 * WPDB Log Tab
	 */
	public function wpsl_wpdb_info() {
		$this->plugin_settings_tabs['wpdb-log'] = __( 'WPDB', 'wp-system-log' );
		register_setting( 'wpdb-log', 'wpdb-log' );
		add_settings_section( 'wp-system-log-wpdb-section', ' ', array( &$this, 'wpsl_wpdb_log_content' ), 'wpdb-log' );
	}

	/**
	 * WPDB Log Tab Content
	 */
	public function wpsl_wpdb_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-wpdb-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-wpdb-log.php';
		}
	}

	/**
	 * WP Plugins Log Tab
	 */
	public function wpsl_wp_plugins_info() {
		$this->plugin_settings_tabs['wp-plugins-log'] = __( 'Plugins', 'wp-system-log' );
		register_setting( 'wp-plugins-log', 'wp-plugins-log' );
		add_settings_section( 'wp-system-log-plugins-section', ' ', array( &$this, 'wpsl_plugins_log_content' ), 'wp-plugins-log' );
	}

	/**
	 * WP Plugins Log Tab Content
	 */
	public function wpsl_plugins_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-plugins-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-plugins-log.php';
		}
	}

	/**
	 * WP Themes Log Tab
	 */
	public function wpsl_wp_themes_info() {
		$this->plugin_settings_tabs['wp-themes-log'] = __( 'Themes', 'wp-system-log' );
		register_setting( 'wp-themes-log', 'wp-themes-log' );
		add_settings_section( 'wp-system-log-themes-section', ' ', array( &$this, 'wpsl_themes_log_content' ), 'wp-themes-log' );
	}

	/**
	 * WP Themes Log Tab Content
	 */
	public function wpsl_themes_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-themes-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-themes-log.php';
		}
	}

	/**
	 * WP Database Log Tab
	 */
	public function wpsl_wp_database_info() {
		$this->plugin_settings_tabs['wp-database-log'] = __( 'Database', 'wp-system-log' );
		register_setting( 'wp-database-log', 'wp-database-log' );
		add_settings_section( 'wp-system-log-database-section', ' ', array( &$this, 'wpsl_database_log_content' ), 'wp-database-log' );
	}

	/**
	 * WP Database Log Tab Content
	 */
	public function wpsl_database_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-database-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-database-log.php';
		}
	}

	/**
	 * WP Post Count Log Tab
	 */
	public function wpsl_wp_post_info() {
		$this->plugin_settings_tabs['wp-post-log'] = __( 'Post Type Count', 'wp-system-log' );
		register_setting( 'wp-post-log', 'wp-post-log' );
		add_settings_section( 'wp-system-log-post-section', ' ', array( &$this, 'wpsl_post_log_content' ), 'wp-post-log' );
	}

	/**
	 * WP Post Log Tab Content
	 */
	public function wpsl_post_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-post-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-post-log.php';
		}
	}


	/**
	 * WP Security Count Log Tab
	 */
	public function wpsl_wp_security_info() {
		$this->plugin_settings_tabs['wp-security-log'] = __( 'Security', 'wp-system-log' );
		register_setting( 'wp-security-log', 'wp-security-log' );
		add_settings_section( 'wp-system-log-security-section', ' ', array( &$this, 'wpsl_security_log_content' ), 'wp-security-log' );
	}

	/**
	 * WP Security Log Tab Content
	 */
	public function wpsl_security_log_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-security-log.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-security-log.php';
		}
	}

	/**
	 * Enquiry Tab
	 */
	public function wpsl_enquiry() {
		$this->plugin_settings_tabs['log-enquiry'] = __( 'Enquiry', 'wp-system-log' );
		register_setting( 'log-enquiry', 'log-enquiry' );
		add_settings_section( 'wp-system-log-enquiry-section', ' ', array( &$this, 'wpsl_enquiry_content' ), 'log-enquiry' );
	}

	/**
	 * Enquiry Tab Content
	 */
	public function wpsl_enquiry_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-enquiry.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-enquiry.php';
		}
	}

	/**
	 * Support Tab
	 */
	public function wpsl_support() {
		$this->plugin_settings_tabs['wp-system-log-support'] = __( 'Support', 'wp-system-log' );
		register_setting( 'wp-system-log-support', 'wp-system-log-support' );
		add_settings_section( 'wp-system-log-support-section', ' ', array( &$this, 'wpsl_support_content' ), 'wp-system-log-support' );
	}

	/**
	 * Support Tab Content
	 */
	public function wpsl_support_content() {
		if ( file_exists( dirname( __FILE__ ) . '/includes/wp-system-log-support.php' ) ) {
			require_once dirname( __FILE__ ) . '/includes/wp-system-log-support.php';
		}
	}

	/**
	 * Ajax served, to turn off the debug mode
	 */
	public function wpsl_turn_debug_off() {
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			exit();
		}
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'wpsl_turn_debug_off' ) {

			$config_file          = dirname( getcwd() ) . '/wp-config.php';
			$config_file_contents = file_get_contents( $config_file );
			$new_contents         = str_replace( "define('WP_DEBUG', true)", "define('WP_DEBUG', false)", $config_file_contents );
			$put_content          = file_put_contents( $config_file, $new_contents );

			$html  = '';
			$html .= '<span><i class="fa fa-times" aria-hidden="true"></i></span>';
			$html .= '<button type="button" class="button button-primary" id="wpsl-turn-debug-on">' . __( 'Turn On', 'wp-system-log' ) . '</button>';

			$response = array(
				'html' => $html,
				'msg'  => __( 'Debug mode turned on.', 'wp-system-log' ),
			);
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * Ajax served, to turn on the debug mode
	 */
	public function wpsl_turn_debug_on() {
		if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			exit();
		}
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'wpsl_turn_debug_on' ) {

			$config_file          = dirname( getcwd() ) . '/wp-config.php';
			$config_file_contents = file_get_contents( $config_file );
			$new_contents         = str_replace( "define('WP_DEBUG', false)", "define('WP_DEBUG', true)", $config_file_contents );
			$put_content          = file_put_contents( $config_file, $new_contents );

			$html  = '';
			$html .= '<span><i class="fa fa-check" aria-hidden="true"></i></span>';
			$html .= '<button type="button" class="button button-primary" id="wpsl-turn-debug-off">' . __( 'Turn Off', 'wp-system-log' ) . '</button>';

			$response = array(
				'html' => $html,
				'msg'  => __( 'Debug mode turned off.', 'wp-system-log' ),
			);
			wp_send_json_success( $response );
			die;
		}
	}

}
