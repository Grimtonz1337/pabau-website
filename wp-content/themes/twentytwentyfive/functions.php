<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

function get_api_config() {
    $config_path = WP_CONTENT_DIR . '/api.ini';
    if (!file_exists($config_path)) {
		return false;
    }
    return parse_ini_file($config_path, true);
}

function add_jquery() {
    if (!wp_script_is('jquery', 'enqueued')) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'add_jquery');

function add_bootstrap() {
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap.bundle.min.js');
}
add_action('wp_enqueue_scripts', 'add_bootstrap');

function add_custom_style() {
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css');
}
add_action('wp_enqueue_scripts', 'add_custom_style');

function handle_custom_form_submission() {
    check_ajax_referer('custom_form_nonce', 'nonce');

	$config = get_api_config();
    if (empty($config)) {
        wp_send_json_error(['message' => 'Error: Internal server error.']);
    }

    $Fname = isset($_POST['Fname']) ? sanitize_text_field($_POST['Fname']) : '';
    $Lname = isset($_POST['Lname']) ? sanitize_text_field($_POST['Lname']) : '';
    if (empty($Fname)) {
        wp_send_json_error(['message' => 'Error: First name is required.']);
    }
    if (empty($Lname)) {
        wp_send_json_error(['message' => 'Error: Last name is required.']);
    }
    $dob = isset($_POST['dob']) ? sanitize_text_field($_POST['dob']) : '';
    if (!empty($dob) && strtotime($dob) === false) {
        wp_send_json_error(['message' => 'Error: Invalid date.']);
    }
	$dob = date('d.m.Y', strtotime($dob)); // Convert the date to d.m.Y instead of Y-m-d
	
    $mobile = isset($_POST['mobile']) ? sanitize_text_field($_POST['mobile']) : '';
    $telephone = isset($_POST['telephone']) ? sanitize_text_field($_POST['telephone']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $lead_source = isset($_POST['lead_source']) ? sanitize_text_field($_POST['lead_source']) : '';
    $salutation = isset($_POST['salutation']) ? sanitize_text_field($_POST['salutation']) : '';
    $county = isset($_POST['county']) ? sanitize_text_field($_POST['county']) : '';
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
    $post_code = isset($_POST['post_code']) ? sanitize_text_field($_POST['post_code']) : '';
    $treatment_interest = isset($_POST['treatment_interest']) ? sanitize_text_field($_POST['treatment_interest']) : '';
    $opt_email = empty($_POST['opt_email']) ? 0 : 1;
    $opt_letter = empty($_POST['opt_letter']) ? 0 : 1;
    $opt_sms = empty($_POST['opt_sms']) ? 0 : 1;
    $opt_newsletter = empty($_POST['opt_newsletter']) ? 0 : 1;
    $opt_phone = empty($_POST['opt_phone']) ? 0 : 1;

	$api_url = $config['api']['url'];
	$api_key = $config['api']['key'];

	$redirect_page = get_page_by_path('thank-you');
	$redirect_link = get_permalink($redirect_page->ID);

	$body = [
		'api_key' => $api_key,
		'redirect_link' => $redirect_link,
		'Fname' => $Fname,
		'Lname' => $Lname,
		'mobile' => $mobile,
		'telephone' => $telephone,
		'lead_source' => $lead_source,
		'salutation' => $salutation,
		'dob' => $dob,
		'county' => $county,
		'country' => $country,
		'city' => $city,
		'address' => $address,
		'post_code' => $post_code,
		'treatment_interest' => $treatment_interest,
		'opt_email' => $opt_email,
		'opt_letter' => $opt_letter,
		'opt_sms' => $opt_sms,
		'opt_newsletter' => $opt_newsletter,
		'opt_phone' => $opt_phone,
	];

	$response = wp_remote_post($api_url, ['body' => $body]);
	if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Error sending data to API: ' . $response->get_error_message()]);
	}
	
	$response_data = json_decode($response['body']);
	if (!$response_data->success) {
        wp_send_json_error(['message' => 'Error sending data to API: ' . $response['body']]);
	}

    wp_send_json_success(['message' => 'Form submitted successfully!']);
}
add_action('wp_ajax_custom_form_submit', 'handle_custom_form_submission');
add_action('wp_ajax_nopriv_custom_form_submit', 'handle_custom_form_submission');
