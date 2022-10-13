<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Verificador
 * @subpackage Verificador/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Verificador
 * @subpackage Verificador/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Verificador_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/verificador-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/verificador-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	function admin_menu_pages(){
		add_menu_page("Verificador", "Verificador", "manage_options", "verificador", [$this, "verificador_menupage"], "dashicons-yes", 45 );
		add_submenu_page("verificador", "Settings", "Settings", "manage_options", "verficador-settings", [$this, "verificador_settings"] );
		add_settings_section( 'verificador_setting_section', '', '', 'verificador_setting_page' );
		// Thank you page
		add_settings_field( 'vc_shortcode_show', 'Shortcode', [$this, 'vc_shortcode_show_cb'], 'verificador_setting_page','verificador_setting_section' );
		add_settings_field( 'verificador_thank_page', 'Thank you page', [$this, 'verificador_thank_page_cb'], 'verificador_setting_page','verificador_setting_section' );
		register_setting( 'verificador_setting_section', 'verificador_thank_page' );
		// Email subject
		add_settings_field( 'verificador_email_subject', 'Email subject', [$this, 'verificador_email_subject_cb'], 'verificador_setting_page','verificador_setting_section' );
		register_setting( 'verificador_setting_section', 'verificador_email_subject' );
		// Email body
		add_settings_field( 'verificador_email_body', 'Email body', [$this, 'verificador_email_body_cb'], 'verificador_setting_page','verificador_setting_section' );
		register_setting( 'verificador_setting_section', 'verificador_email_body' );
	}

	function vc_shortcode_show_cb(){
		echo '<p>Use this shortcode to show the form <code>[verificador]</code></p>';
	}

	function verificador_thank_page_cb(){
		$dropdown_args = array(
			'post_type'        => 'page',
			'selected'         => get_option('verificador_thank_page'),
			'name'             => 'verificador_thank_page',
			'show_option_none' => 'Select a page',
			'echo'             => 0,
		);
		
		echo wp_dropdown_pages( $dropdown_args );
	}

	function verificador_email_subject_cb(){
		echo '<input class="widefat" type="text" placeholder="Coupon verification" name="verificador_email_subject" value="'.get_option('verificador_email_subject').'">';
	}

	function verificador_email_body_cb(){
		echo '<textarea name="verificador_email_body" style="resize: none; height: 200px;" class="widefat">'.get_option('verificador_email_body').'</textarea>';
		echo '<p>Use these placeholders <code>%coupon_code%</code>, <code>%user_name%</code>, <code>%manager_name%</code> to show the values of them in the text.</p>';
	}

	function verificador_settings(){
		?>
		<h3>Settings</h3>
		<hr>
		<div class="vc-settings" style="width: 50%">
            <form method="post" action="options.php">
                <?php
                settings_fields( 'verificador_setting_section' );
                do_settings_sections('verificador_setting_page');
                echo get_submit_button( 'Save Changes', 'secondary', 'save-vc-setting' );
                ?>
            </form>
        </div>
		<?php
	}

	function verificador_menupage(){
		$records = new Verificador_list();
		?>
		<div class="wrap" id="records-table">
			<h3 class="heading3">Records</h3>
			<hr>
			
			<form action="" method="post">
				<?php
				$records->prepare_items();
				$records->display();
				?>
			</form>
		</div>
		<?php
	}
}
