<?php
/*
Plugin Name: Custom New User Email
Description: Changes the copy in the email sent out to new users
*/

namespace J2\NewUserEmail;

// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {

    $blog_name = get_option('blogname');
    $login_page_slug = 'staff-portal';
    $login_page_name = 'Staff Portal';

    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(__('New user registration on %s:'), $blog_name) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail( get_option('admin_email'), sprintf( __('[%s] New User Registration'), $blog_name, $message ) );

        if ( empty($plaintext_pass) )
            return;

        $message  = __('Hello!') . "\r\n\r\n";
        $message .= sprintf(__("Welcome to the %1$s %2$s! Here's how to log in:"), get_option('blogname'), $login_page_name) . "\r\n\r\n";
        $message .= 'Visit the ' . $login_page_name . ' page: ' . $site_url . $login_page_slug . PHP_EOL;
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __('Adios!');

        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);

    }
}

// NEW USER EMAIL ADDRESS
// ----------------------
// https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_mail_from

add_filter( 'wp_mail_from', __NAMESPACE__ . '\\custom_wp_mail_from' );
function custom_wp_mail_from( $original_email_address ) {
  return 'congreso@congreso.net';
}

add_filter( 'wp_mail_from_name', __NAMESPACE__ . '\\custom_wp_mail_from_name' );
function custom_wp_mail_from_name( $original_email_from ) {
    return get_option('blogname');
}

?>
