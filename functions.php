<?php
//need to require thsese so that they are loaded
require get_theme_file_path('/includes/lesson-stats-route.php');
require get_theme_file_path('/includes/change-email.php');
require get_theme_file_path('/includes/change-password.php');
require get_theme_file_path('/includes/change-notifications.php');


function load_game_js(){
  wp_register_script('game-js', get_theme_file_uri('/js/game.js'), array('jquery'), '0.1', true);
  wp_enqueue_script('game-js');
  wp_localize_script('game-js', 'kanjiAppData', array(
    'root_url'=> get_site_url(),
    'nonce' => wp_create_nonce('wp_rest'),
    'user_id'=>get_current_user_id()
  ));

}
add_action('wp_enqueue_scripts', 'load_game_js');

function load_correct_run_js(){
  wp_register_script('correct-run-game-js', get_theme_file_uri('/js/correct-run-game.js'), array('jquery'), '0.1', true);
  wp_enqueue_script('correct-run-game-js');
  wp_localize_script('correct-run-game-js', 'kanjiAppData', array(
    'root_url'=> get_site_url(),
    'nonce' => wp_create_nonce('wp_rest'),
    'user_id'=>get_current_user_id()
  ));

}
add_action('wp_enqueue_scripts', 'load_correct_run_js');

function load_scripts_bundled(){
  wp_register_script('sb-js', get_theme_file_uri('/scripts-bundled.js'), array('jquery'), '0.1', true);
  wp_enqueue_script('sb-js');
}
add_action('wp_enqueue_scripts', 'load_scripts_bundled');


function kanji_files(){

//this neads a call to wp_head() to work
wp_enqueue_style('kanji_main_styles', get_stylesheet_uri());
}
add_action( 'wp_enqueue_scripts', 'kanji_files' );



function remove_admin_bar() {
if (!current_user_can('administrator')) {
  show_admin_bar(false);
}
}
add_action('after_setup_theme', 'remove_admin_bar');


add_action( 'init', 'blockusers_init' );
function blockusers_init() {
  //is_admin() is referring to weather the user is on the admin page or not
  // i have no idea what the doing_ajax is
if ( is_admin() && !current_user_can( 'administrator' ) &&
!( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
wp_redirect('/statistics');
exit;
}
}

//this is adding a field to the rest API so that it is visible in postman
add_action( 'rest_api_init', 'userStats_add_receive_notifications' );
function userStats_add_receive_notifications() {
    register_rest_field( 'userstats',
        'receive_notifications',
        array(
            'get_callback'    => 'userStats_get_receive_notifications',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function userStats_get_receive_notifications( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], 'receive_notifications', true );
}



 add_filter( 'wp_mail_from', 'custom_wp_mail_from' );
 function custom_wp_mail_from( $original_email_address ) {
 	//Make sure the email is from the same domain
 	//as your website to avoid being marked as spam.
 	return 'admin@kanjiclimb.com';
 }

 add_filter( 'wp_mail_from_name', 'custom_wp_mail_from_name' );
 function custom_wp_mail_from_name( $original_email_from ) {
 	return 'Kanji Climb';
 }

 require get_theme_file_path('/includes/course-purchases.php');

 ?>
