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
		'tax_code' => __( 'Tax Code', 'svbk-rcp-company-details' ),
		'company_phone' => __( 'Phone', 'svbk-rcp-company-details' ),
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
			<p class="subtitle"><?php esc_html_e( 'Fields with * are required', 'svbk-rcp-company-details' ); ?></p>
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
	<fieldset id="billing-info">
		<legend><?php esc_html_e( 'Billing Info', 'svbk-rcp-company-details' ); ?></legend>
		<?php svbk_rcp_print_company_fields();
}

/**
 * Adds the custom fields to the registration form and profile editor
 */
function svbk_rcp_print_company_fields($user_id = null) {

	$fields = svbk_rcp_company_fields();

	foreach ( $fields as $field_name => $field_label ) {
		$field_value = get_user_meta( get_current_user_id(), $field_name, true ); ?>
		
			<?php do_action('svbk_rcp_company_field_before', $field_name, $field_value, $field_label); ?>
			 <p>
				<label for="rcp_<?php echo esc_attr( $field_name ); ?>"><?php echo esc_attr( $field_label ); ?></label>
				<?php 
				if ( ( 'billing_country' === $field_name ) && ( $countries = apply_filters( 'svbk_rcp_company_details_countries', array() ) ) ): ?>			
				<select  name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>">
					<option value="" <?php selected( '', $field_value ) ?>><?php echo esc_html__( '- Select -', 'svbk-rcp-company-details' ); ?></option>
					<?php foreach( $countries as $country ) : ?>
						<option value="<?php echo esc_attr( $country ); ?>" <?php selected( $country, $field_value ) ?>><?php echo esc_html( $country ) ?></option>
					<?php endforeach; ?>
				</select>
				<?php else: ?>
				<input name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>" type="text" value="<?php echo esc_attr( $field_value ); ?>"/>
				<?php endif; ?>
			</p>
			<?php do_action('svbk_rcp_company_field_after', $field_name, $field_value, $field_label); ?>
				
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

	$fields = svbk_rcp_company_fields();

	foreach ( $fields as $field_name => $field_label ) {

	$field_value = get_user_meta( $user_id, $field_name, true ); ?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_<?php echo esc_attr( $field_name ); ?>"><?php esc_html( $field_label ); ?></label>
		</th>
		<?php 
		if ( ( 'billing_country' === $field_name ) && ( $countries = apply_filters( 'svbk_rcp_company_details_countries', array() ) ) ): ?>
		<td>
			<select  name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>">
				<?php foreach( $countries as $country ) : ?>
					<option value="<?php echo esc_attr( $country ); ?>" <?php selected( $country, $field_value ) ?>><?php echo esc_html( $country ) ?></option>
				<?php endforeach; ?>
			</select>				
			<p class="description"><?php echo esc_attr( $field_label ); ?></p>
		</td>		
		<?php else: ?>
		<td>
			<input name="rcp_<?php echo esc_attr( $field_name ); ?>" id="rcp_<?php echo esc_attr( $field_name ); ?>" type="text" value="<?php echo esc_attr( $field_value ); ?>"/>
			<p class="description"><?php echo esc_attr( $field_label ); ?></p>
		</td>		
		<?php endif; ?>
	</tr>

	<?php
	}
}
add_action( 'rcp_edit_member_after', 'svbk_rcp_add_member_edit_company_fields' );

/**
 * Stores the information submitted during registration
 *
 * @param array $posted The form submission data.
 * @param int   $user_id The user ID.
 */
function svbk_rcp_save_company_fields_on_register( $posted, $user_id ) {

	$fields = svbk_rcp_company_fields();

	foreach ( $fields as $field_name => $field_label ) {
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

	$fields = svbk_rcp_company_fields();

	foreach ( $fields as $field_name => $field_label ) {
		if ( ! empty( $_POST[ 'rcp_' . $field_name ] ) ) {
			update_user_meta( $user_id, $field_name, sanitize_text_field( $_POST[ 'rcp_' . $field_name ] ) );
		}
	}

}
add_action( 'rcp_user_profile_updated', 'svbk_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'svbk_rcp_save_user_fields_on_profile_save', 10 );


/**
 * Require first and last names during registration
 * 
 * @param array $posted Array of information sent to the form.
 * 
 * @return void
 */
function svbk_rcp_require_first_and_last_names( $posted ) {
	if( is_user_logged_in() ) {
		return;
	}
	
	if( empty( $posted['rcp_user_first'] ) ) {
		rcp_errors()->add( 'first_name_required', __( 'Please enter your first name', 'svbk-rcp-company-details' ), 'register'  );
	}
	if( empty( $posted['rcp_user_last'] ) ) {
		rcp_errors()->add( 'last_name_required', __( 'Please enter your last name', 'svbk-rcp-company-details' ), 'register'  );
	}
}
add_action( 'rcp_form_errors', 'svbk_rcp_require_first_and_last_names' );

/**
 * Require the 5 billing data inputs during registration in any case
 * 
 * @param array $posted Array of information sent to the form.
 * 
 * @return void
 */
function svbk_rcp_require_billing_data( $posted ) {
	if( is_user_logged_in() ) {
		return;
	}
	
	if( empty( $posted['rcp_billing_address'] ) ) {                      
	    rcp_errors()->add( 'billing_address_required', __( 'Please enter your billing address', 'svbk-rcp-company-details' ), 'register'  );
	}
	if( empty( $posted['rcp_biling_city'] ) ) {                      
	    rcp_errors()->add( 'billing_city_required', __( 'Please enter your billing city', 'svbk-rcp-company-details' ), 'register'  );
	}
	if( empty( $posted['rcp_billing_state'] ) ) {                      
	    rcp_errors()->add( 'billing_state_required', __( 'Please enter your billing state', 'svbk-rcp-company-details' ), 'register'  );
	}
	if( empty( $posted['rcp_billing_postal_code'] ) ) {                      
	    rcp_errors()->add( 'billing_postal_code_required', __( 'Please enter your billing postal code', 'svbk-rcp-company-details' ), 'register'  );
	}
	if( empty( $posted['rcp_billing_country'] ) ) {                      
	    rcp_errors()->add( 'billing_country_required', __( 'Please select your billing country', 'svbk-rcp-company-details' ), 'register'  );
	}
}
add_action( 'rcp_form_errors', 'svbk_rcp_require_billing_data' );



/**
 * Require tax_id or tax_code
 * 
 * @param array $posted Array of information sent to the form.
 * 
 * @return void
 */

function svbk_rcp_require_one_tax_field( $posted ) {
	if( is_user_logged_in() ) {
		return;
	}
	
	if( empty( $posted['rcp_tax_id'] ) && empty( $posted['rcp_tax_code'] ) ) {                      
	    rcp_errors()->add( 'tax_parameter_required', __( 'Please enter your tax id or tax code', 'svbk-rcp-company-details' ), 'register'  );
	}
}
add_action( 'rcp_form_errors', 'svbk_rcp_require_one_tax_field' );


/**
 * Require company_name if tax_code is set
 * 
 * @param array $posted Array of information sent to the form.
 * 
 * @return void
 */

function svbk_rcp_require_company_name_if_vat( $posted ) {
	if( is_user_logged_in() ) {
		return;
	}
	
    if( ( $posted['rcp_tax_id'] ) && ( empty( $posted['rcp_company_name'] ) ) ) {                      
        rcp_errors()->add( 'company_name_required', __( 'Please enter your company name', 'svbk-rcp-company-details' ), 'register'  );
    }
}
add_action( 'rcp_form_errors', 'svbk_rcp_require_company_name_if_vat' );

add_filter('rcp_email_template_tags', 'svbk_rcp_company_fields_email_tags', 10, 2);

/**
 * Append company info email tags
 * 
 * @param array $email_tags Array of existing tags.
 * @param array $email_tags Array of existing tags.
 * 
 * @return array
 */
function svbk_rcp_company_fields_email_tags( $email_tags, $email ) {
	
	$fields = svbk_rcp_company_fields();
	
	foreach( $fields as $field_name => $field_label ) {
		
		$email_tags[] = array(
			'tag'         => $field_name,
			'description' => $field_label,
			'function'    => 'svbk_rcp_company_fields_email_tags_value'
		);
		
	}
	
	return $email_tags;
	
}

/**
 * Email template tag: firstname
 * Returns the requested tag value
 *
 * @since 2.7
 * @param int $member_id
 * @param int $payment_id
 * @param int $tag
 * @return string 
 */
function svbk_rcp_company_fields_email_tags_value( $member_id = 0, $payment_id = 0, $tag ) {
	
	return get_user_meta( $member_id, $tag, true );
	
}

add_filter( 'rcp_export_csv_cols_members' , 'svbk_rcp_export_fields_cols', 10 ,1 );

/**
 * Add Company Details columns to header
 *
 * @since 1.1
 * @param array $cols The existing member
 * @return array 
 */
function svbk_rcp_export_fields_cols( $cols ) {
	return $cols + svbk_rcp_company_fields();
}

add_filter( 'rcp_export_members_get_data_row' , 'svbk_rcp_export_member_row', 10, 2 );

/**
 * Add Company Details columns from member row
 *
 * @since 1.1
 * @param array $data The existing data
 * @param array $member The current row member object 
 * @return array 
 */
function svbk_rcp_export_member_row( $data, $member ) {
	
	$fields = svbk_rcp_company_fields();
	
	$all_user_meta = get_user_meta( $member->ID );
	
	foreach ($fields as $field_name => $field_label) {
		 $data[$field_name] = isset($all_user_meta[$field_name]) ? $all_user_meta[$field_name][0] : '';
	}
	
	return $data;
}











