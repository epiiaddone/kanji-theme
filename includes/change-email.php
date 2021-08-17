<?php

add_action('rest_api_init', 'userEmailData');

function userEmailData(){
  register_rest_route('user/v1/', 'changeEmail',array(
    'methods' =>'POST',
    'callback' => 'updateEmail'
  ));
}

function updateEmail($data){
$current_user = wp_get_current_user();//tthis is returning 0
echo "inside upddateEmail";

  if($data['user_id']>0){
    $email = $data['email'];
    $pass = $data['password'];
    $user = get_user_by( 'id', $data['user_id']);
    if (empty( $user ) OR !wp_check_password( $pass, $user->data->user_pass, $user->ID)){
      echo " invalid password ";
      return;
    }
    $email = formatEmail($email);
    if($email==null){
      echo "email failed varification";
      return false;
    }
    echo "the email is: " . $email;


      $user_id_id = wp_update_user( array(
        'ID' => $data['user_id'],
        'user_email'=> $email,
      ) );

      if ( is_wp_error( $user_id_id ) ) {
          echo "error in update user";
      } else {
      	// Success!
        //this is sending the new email for the js to update
        // i can also use this as a succcess indicaotr in the js
        echo "@@email=" . $email . "@@end";
      }
  }else{
    die("user not logged in");
  }

}

function formatEmail($email){
  if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) return null;
  return $email;
}

?>
