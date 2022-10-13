<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Verificador
 * @subpackage Verificador/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Verificador
 * @subpackage Verificador/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Verificador_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode("verificador", [$this, "verificador_callback"] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/verificador-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/verificador-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name,'verificador',array(
			'ajaxurl' => admin_url('admin-ajax.php')
		) );

	}

	function verificador_callback(){
		ob_start();

		require_once plugin_dir_path(__FILE__ )."partials/verificador-public-display.php";
		return ob_get_clean();
	}

	function verficador_coupon_validation(){
		if(isset($_GET['coupon'])){
			$coupon_code = sanitize_text_field($_GET['coupon'] );

			$coupon = new \WC_Coupon( $coupon_code );   
			$discounts = new \WC_Discounts( WC()->cart );
			$response = $discounts->is_coupon_valid( $coupon );
			$response = is_wp_error( $response ) ? false : true;

			if($response) {
				global $wpdb;
				$exit = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}verificador WHERE coupon = '$coupon_code'");

				if(!$exit){
					echo json_encode(array('success' => 'Valid'));
					die;
				}else{
					echo json_encode(array('invalid' => 'Invalid'));
					die;
				}
			}else{
				echo json_encode(array('invalid' => 'Invalid'));
				die;
			}
			
			die;
		}
	}

	function email_template($bodytext){
		$template = '<!doctype html>
		<html lang="en-US">
			<head>
				<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
				<title>Coupon verification</title>
				<meta name="description" content="Coupon verification">
			</head>
			<style>
				a:hover {text-decoration: underline !important;}
			</style>
			
			<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
				<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
					style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;">
					<tr>
						<td>
							<table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
								align="center" cellpadding="0" cellspacing="0">
								<!-- Email Content -->
								<tr>
									<td>
										<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
											style="max-width:670px; background:#fff; border-radius:3px;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);padding:0 40px;">
											<!-- Details Table -->
											<tr>
												<td>
													<table cellpadding="0" cellspacing="0"
														style="width: 100%; border: 1px solid #ededed">
														<tbody>
															<tr>
																<td
																	style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">
																	'.$bodytext.'
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</body>
		</html>';

		return $template;
	}

	function send_verficador_confirmation($email, $coupon, $manager_name, $user_name){
		$bodytext = stripcslashes(get_option('verificador_email_body'));
		$bodytext = str_replace("%coupon_code%", "<strong>$coupon</strong>", $bodytext);
		$bodytext = str_replace("%manager_name%", "<strong>$manager_name</strong>", $bodytext);
		$bodytext = str_replace("%user_name%", "<strong>$user_name</strong>", $bodytext);

		$template = $this->email_template($bodytext);
		$adminEmail = get_option( 'admin_email' );
		$adminEmail = get_bloginfo( 'name' );

		$to = $email;
		$subject = ((get_option('verificador_email_subject'))?get_option('verificador_email_subject'):'Coupon verification');
		$body = $template;
		$headers = array('Content-Type: text/html; charset=UTF-8','From: '.$adminEmail.' <'.$adminEmail.'>');

		if(wp_mail( $to, $subject, $body, $headers )){
			return true;
		}
	}

	function send_validation_data(){
		if(isset($_POST['data'])){
			$data = $_POST['data'];

			$coupon = sanitize_text_field($data['coupon'] );
			$coupon = strtolower($coupon);
			$username = sanitize_text_field($data['username']);
			$useremail = sanitize_email($data['useremail'] );
			$manager_name = sanitize_text_field($data['manager_name']);

			date_default_timezone_set(get_option('timezone_string'));
			
			global $wpdb;
			$dbId = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}verificador WHERE coupon = '$coupon'");
			if(!$dbId){
				$wpdb->insert($wpdb->prefix.'verificador', array(
					'username' => $username,
					'useremail' => $useremail,
					'coupon' => $coupon,
					'manager_name' => $manager_name,
					'created' => date("Y-m-d H:i:s")
				));
			}

			
			if($wpdb->insert_id){
				$coupon_id = wc_get_coupon_id_by_code($coupon);
				if(!empty($coupon_id)){
					wp_delete_post($coupon_id);
				}

				$this->send_verficador_confirmation($useremail, $coupon, $manager_name, $username);
				echo json_encode(array('success' => get_the_permalink( get_option('verificador_thank_page') )));
				die;
			}

			echo json_encode(array('error' => 'Faild!'));
			die();
		}
	}
}
