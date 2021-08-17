<?php


function userHasLessonAccess($lessonNumber){
    $current_user_temp = wp_get_current_user();
    $user_login_temp = $current_user_temp->user_login;
  if($lessonNumber =='1' || $lessonNumber =='2' || $lessonNumber =='3' || $lessonNumber ==4 || $user_login_temp =="livepractise" ){ return true;
  }else{
    if(userHasSubscription())return true;
  }
  return false;
}

function userHasSubscription(){
  try{
  global $wpdb;
  $prefix = $wpdb->prefix;

  //stripe direct order
  $user_subscribed_data = $wpdb->get_results("
  select
  'subscribed' as subscription
  from
  ".$prefix."posts p
  where
  p.post_type='stripe_order'
  and p.post_author=".get_current_user_id()
);
if($user_subscribed_data[0]->subscription=='subscribed') return true;

//woo commerce order
if(GetUserCourseCode(array('debug'=>false,))== "ACCESS") return true;


wp_reset_postdata();
}catch(Error $e){
  print_r($e);
  wp_reset_postdata();
  return FALSE;
}
}

function isValidLesson($lesson_to_play,$heisig_kanji){
  if($heisig_kanji[$lesson_to_play]!=null) return True;
  return False;
}
