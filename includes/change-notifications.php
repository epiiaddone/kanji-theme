<?php

add_action('rest_api_init', 'userNotificationsData');

function userNotificationsData(){
  register_rest_route('user/v1/', 'changeNotifications',array(
    'methods' =>'POST',
    'callback' => 'updateNotifications'
  ));
}

function updateNotifications($data){
  echo "inside updateNotifications";
$current_user = wp_get_current_user();//tthis is returning 0

  if($data['user_id']>0){
    $receive_notifications = $data['receive_notifications'];
    $user = get_user_by( 'id', $data['user_id']);
    if (empty( $user )){
      echo " empty user ";
      return;
    }

    if($receive_notifications !="true" AND $receive_notifications!="false"){
      echo "notification data invalid";
      return false;
    }
    echo "the notification choice is " . $receive_notifications;
    $query = new WP_Query(array(
        'author'=>$data['user_id'],
        'post_type'=>'userStats',
        'posts_per_page'=>-1,
      ));
      $userStatsID = 0;
      while($query->have_posts()){
        $query->the_post();
        $userStatsID = get_the_id();
      }
      wp_reset_postdata();
      if($receive_notifications=="true") $receive_notificaitons='1';
      if($receive_notifications=="false") $receive_notifications='0';
      echo "user stats id is : " . $userStatsID . "<>";
    $update = update_post_meta($userStatsID, 'receive_notifications', $receive_notifications);
      if ( is_wp_error( $update ) ) {
          echo "error in update user";
      } else {
      	// Success!
        //this is sending the new email for the js to update
        // i can also use this as a succcess indicaotr in the js
        echo "@@Notification changed@@";
      }
  }else{
    die("user not logged in");
  }

}


?>
