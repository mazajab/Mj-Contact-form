<?php 
/*Plugin Name: MJ20 Contact Form
Description: Contact Form for Wordpress.
Version: 1.0
License: GPLv2
Author: mazen ajab
Author URI: https://www.linkedin.com/in/mazen-ajab-87630a70/
*/

add_action( 'admin_menu', 'mj_contact_menu' );
function mj_contact_menu(){

  $page_title = 'MJ Setting';
  $menu_title = 'MJ20 Contact Form';
  $capability = 'manage_options';
  $menu_slug  = 'extra-contact-info';
  $function   = 'contact_info_page';
  $icon_url   = 'dashicons-media-code';
  $position   = 4;

  add_menu_page( $page_title,
                 $menu_title,
                 $capability,
                 $menu_slug,
                 $function,
                 $icon_url,
                 $position );

  // Call update_contact_info function to update database
  add_action( 'admin_init', 'update_contact_info' );

}


function contact_info_page(){
?>
  <h1 style="text-align:center">MJ20 Contact Form</h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'extra-contact-info-settings2' ); ?>
    <?php do_settings_sections( 'extra-contact-info-settings2' ); ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row">Receiver Email Address:</th>
      <td><input type="text" name="contact_field_info" value="<?php echo get_option('contact_field_info'); ?>"/></td>
      </tr>
	  <p> Use the Shortcode:&nbsp;&nbsp; [mj20_contact_form]</p>
    </table>
  <?php submit_button(); ?>
  </form>
<?php
}


function update_contact_info() {
  register_setting( 'extra-contact-info-settings2', 'contact_field_info' );
}


function html_form_code() {
	echo '<form id="clear_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Your Name (required) <br/>';
	echo '<input type="text" class="form-control" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input class="form-control" type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Subject (required) <br/>';
	echo '<input  class="form-control" type="text" name="cf-subject" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Message (required) <br/>';
	echo '<textarea class="form-control" rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input class="btn btn-dark btn-lg" type="submit" name="cf-submitted" value="Send"></p>';
	echo '</form>';}
	
	
	
	
function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = sanitize_text_field( $_POST["cf-subject"] );
		$message = esc_textarea( $_POST["cf-message"] );

		// you can use administrator's email address admin_email
		$to = get_option( 'contact_field_info' );

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p style="color:green">Thank You for contacting us, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo '<p style="color:red"> Please Fill in All the Required Fields</P>';
		}
	}}
function cf_shortcode() {
	ob_start();
	deliver_mail();
	html_form_code();

	return ob_get_clean();}
add_shortcode( 'mj20_contact_form', 'cf_shortcode' );
?>
