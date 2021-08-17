<?php

add_action('rest_api_init', 'userPassData');

function userPassData(){
  register_rest_route('user/v1/', 'changePassword',array(
    'methods' =>'POST',
    'callback' => 'updatePass'
  ));
}

function updatePass($data){
$current_user = wp_get_current_user();//tthis is returning 0
echo "inside upddatePass";

  if($data['user_id']>0){
    $new_pass = $data['new_pass'];
    $rep_pass= $data['rep_pass'];
    $pass = $data['password'];
    $user = get_user_by( 'id', $data['user_id']);
    if (empty( $user ) OR !wp_check_password( $pass, $user->data->user_pass, $user->ID)){
      echo "current password not correct ";
      return;
    }

    if($new_pass==null OR $new_pass=='' OR $rep_pass==null OR $rep_pass=='' OR $new_pass != $rep_pass){
      echo "new passwords failed";
      return false;
    }

      $user_id_id = wp_set_password($new_pass, $data['user_id']);

      if ( is_wp_error( $user_id_id ) ) {
          echo "error in update user pass";
      } else {
      	// Success!
        //this is sending the new email for the js to update
        // i can also use this as a succcess indicaotr in the js
        echo "@@passwordSuccess@@";
      }
  }else{
    die("user not logged in");
  }

}


?>
