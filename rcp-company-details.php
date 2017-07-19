<?php
/**
 * Adds the custom fields to the registration form and profile editor
 *
 * @package svbk-rcp-company-details
 * @author Brando Meniconi <b.meniconi@silverbackstudio.it>
 */

/*
Plugin Name: Restrict Content Pro - Company Details
Description: Add Company Fields to Registration Form
Author: Silverback Studio
Version: 1.1
Author URI: http://www.silverbackstudio.it/
Text Domain: svbk-rcp-company-details
*/


/**
 * Loads textdomain and main initializes main class
 *
 * @return void
 */
function svbk_rcp_company_details_init() {
	load_plugin_textdomain( 'svbk-rcp-company-details', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'svbk_rcp_company_details_init' );


/**
 * Adds the custom fields to the registration form and profile editor
 */
function svbk_rcp_company_fields() {
	return array(
		'tax_id' => __( 'Your Tax ID', 'svbk-rcp-company-details' ),
		'company_name' => __( 'Company Name', 'svbk-rcp-company-details' ),
		'billing_address' => __( 'Address', 'svbk-rcp-company-details' ),
		'biling_city' => __( 'City', 'svbk-rcp-company-details' ),
		'billing_state' => __( 'State/Province', 'svbk-rcp-company-details' ),
		'billing_postal_code' => __( 'Postal Code', 'svbk-rcp-company-details' ),
		'billing_country' => __( 'Country', 'svbk-rcp-company-details' ),
	);
}

/**
 * Adds the custom fields to the registration form and profile editor
 */
function svbk_rcp_add_company_fields() {
	?>
	
	<section id="billing">
		<header class="section-header">
			<h2><?php esc_html_e( 'Billing Info', 'svbk-rcp-company-details' ) ?></h2>		
			<p class="subtitle">Lorem ipsum</p>
		</header>
		
		<fieldset>
		<?php svbk_rcp_print_company_fields(); ?>
		</fieldset>

	</section>	
	<?php
}

/**
 * Company fields for Profile Page
 */
function svbk_rcp_profile_company_fields() {
	?>
	
	</fieldset>
	<fieldset>
		<legend><?php esc_html_e( 'Billing Info', 'svbk-rcp-company-details' ); ?></legend>
		<?php svbk_rcp_print_company_fields();
}

/**
 * Adds the custom fields to the registration form and profile editor
 */
function svbk_rcp_print_company_fields() {
	foreach ( svbk_rcp_company_fields() as $field_name => $field_label ) {
		$field_value = get_user_meta( get_current_user_id(), $field_name, true ); ?>
		<p>
			<label for="rcp_<?php echo esc_attr( $field_name ); ?>"><?php echo esc_attr( $field_label ); ?></label>
			<input name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>" type="text" value="<?php echo esc_attr( $field_value ); ?>"/>
		</p>								
	<?php }
}

add_action( 'rcp_after_register_form_fields', 'svbk_rcp_add_company_fields', 8 );
add_action( 'rcp_before_subscription_form_fields', 'svbk_rcp_add_company_fields', 8 );
add_action( 'rcp_profile_editor_after', 'svbk_rcp_profile_company_fields', 8 );

/**
 * Adds the custom fields to the member edit screen
 *
 * @param int $user_id The user ID.
 */
function svbk_rcp_add_member_edit_company_fields( $user_id = 0 ) {

	foreach ( svbk_rcp_company_fields() as $field_name => $field_label ) {

	$field_value = get_user_meta( $user_id, 'rcp_' . $field_name, true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_<?php echo esc_attr( $field_name ); ?>"><?php esc_html_e( 'Your Tax ID', 'svbk-rcp-company-details' ); ?></label>
		</th>
		<td>
			<input name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>" type="text" value="<?php echo esc_attr( $field_value ); ?>"/>
			<p class="description"><?php echo esc_attr( $field_label ); ?></p>
		</td>
	</tr>

	<?php
	}
}
add_action( 'rcp_edit_member_after', 'svbk_rcp_add_member_edit_fields' );

/**
 * Stores the information submitted during registration
 *
 * @param array $posted The form submission data.
 * @param int   $user_id The user ID.
 */
function svbk_rcp_save_company_fields_on_register( $posted, $user_id ) {

	foreach ( svbk_rcp_company_fields() as $field_name => $field_label ) {
		if ( ! empty( $posted[ 'rcp_' . $field_name ] ) ) {
			update_user_meta( $user_id, $field_name, sanitize_text_field( $posted[ 'rcp_' . $field_name ] ) );
		}
	 }

}
add_action( 'rcp_form_processing', 'svbk_rcp_save_company_fields_on_register', 10, 2 );

/**
 * Stores the information submitted profile update
 *
 * @param int $user_id The user ID.
 */
function svbk_rcp_save_user_fields_on_profile_save( $user_id ) {

	if ( ! wp_verify_nonce( $_POST['rcp_profile_editor_nonce'], 'rcp-profile-editor-nonce' ) ) {
		return false;
	}

	foreach ( svbk_rcp_company_fields() as $field_name => $field_label ) {
		if ( ! empty( $_POST[ 'rcp_' . $field_name ] ) ) {
			update_user_meta( $user_id, $field_name, sanitize_text_field( $_POST[ 'rcp_' . $field_name ] ) );
		}
	}

}
add_action( 'rcp_user_profile_updated', 'svbk_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'svbk_rcp_save_user_fields_on_profile_save', 10 );
