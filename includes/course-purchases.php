<?php
/////////////////////////product purchased logic/////////////////////
// set up a custom post type of purchase that shows everything you want from the orders
// user_id
// couse type
// epire_date
// if a user logs in first check to see if they have a purchase custom post type
// then check to see if the purchase is still in date
// then get the permissions

function updateUserCourse($current_post_id, $input){
if(get_current_user_id() ==0) return FALSE;

//get post id
if($input['debug']){
echo "<br>////////// start update user Course()///////<br>";
echo "<br> current _post Id: " . $current_post_id;
}
try{

global $wpdb;
$prefix = $wpdb->prefix;

$test_data = $wpdb->get_results("
select
'test' as test
from
".$prefix."posts p
limit 1
");
if($input['debug']){
echo "<br> test:";
print_r($test_data);
echo "<br> end test <br>";
}

//tefl_house.post_author =1 for all shop orders
//so have to search the tefl_house_postsmeta for the user id
$product_user_id_data = $wpdb->get_results("
select
p.ID as post_id
from
".$prefix."postmeta pm,
".$prefix."posts p
where
p.post_type='shop_order'
and p.post_status='wc-completed'
and p.ID = pm.post_id
and pm.meta_value=" . get_current_user_id() . "
and pm.meta_key = '_customer_user'
");
if($input['debug']){
echo "<br> product user id data <br>";
print_r($product_user_id_data);
}
$product_post_id = $product_user_id_data[0]->post_id;
wp_reset_postdata();
if($current_post_id >0 && $current_post_id==$product_post_id) return FALSE;

//check post id is 120 tefl
$product_name_data = $wpdb->get_results("
select
order_item_name
from
".$prefix."woocommerce_order_items oi
where
order_id =" . $product_post_id
);
//print_r($product_name_data);
$product_name = $product_name_data[0]->order_item_name;
if($input['debug']){
echo "<br> producnt name: " . $product_name;
}
if(strval($product_name) != "Life Time Subscription") {
  if($input['debug']){
  echo "<br> in if of name<br>";
}
  return FALSE;
}
// get the time of the order
$product_120_hour_time_purchased_data = $wpdb->get_results("
select
meta_value as date_paid
from
".$prefix."postmeta pm
where
pm.post_id =" . $product_post_id . "
and pm.meta_key = '_date_paid'"
);
//print_r($product_120_hour_time_purchased);
$product_120_hour_time_purchased = $product_120_hour_time_purchased_data[0]->date_paid;
if($input['debug']){
echo "<br> product details: ";
echo $product_post_id . " " . $product_name . " " . $product_120_hour_time_purchased;
echo "<br>";
}
if(isCourseExpired($product_120_hour_time_purchased)) return FALSE;

$data = array(
  'post_title' => "course=" . $product_name . " time=" . $product_120_hour_time_purchased . ' expired=FALSE',
  'post_author' => get_current_user_id(),
  'post_type' => 'userCourses',
  'post_status' => 'publish'
);

$newPostId =   wp_insert_post($data, false);
if($input['debug']){
echo "<br> new post id: " . $newPostId;
}
update_post_meta($newPostId, 'author_id', get_current_user_id());
update_post_meta($newPostId, 'course', $product_name);
update_post_meta($newPostId, 'purchase_time', $product_120_hour_time_purchased);
update_post_meta($newPostId, 'expired', 'FALSE');
wp_reset_postdata();
return TRUE;
}catch(Error $e){
  print_r($e);
  wp_reset_postdata();
  return FALSE;
}
}



function getUserCourseData($input){
  if(get_current_user_id() ==0) return null;
if($input['debug']){
echo "<br> ///start get user course data() /////<br>";
}
$query_userCourses = new WP_Query(array(
  'author'=>get_current_user_id(),
  'post_type' => 'usercourses',
  'post_status'=> 'publish'
));

$userCourse = array();

while($query_userCourses->have_posts()){
  $query_userCourses->the_post();
  $userCourse['course'] = get_field('course');
  $userCourse['purchase_time'] = get_field('purchase_time');
  $userCourse['expired'] = get_field('expired');
  $userCourse['post_id'] = get_the_id();

      }
      if($input['debug']){
      echo "userCourse <br>";
      print_r($userCourse);
      echo "<br>";
      echo "////end user course data()//////////// <br><br>";
    }
      wp_reset_postdata();
      return $userCourse;

}


function isCourseExpired($purchase_time){
  return false;
}


function GetUserCourseCode($input){
  if($input['debug']){
    echo "the current user id:" . get_current_user_id();
  }

  $userCourse = getUserCourseData($input);
  if($userCourse['course'] == "Life Time Subscription" && $userCourse['expired'] = "FALSE"){
    if($input['debug']){
    echo ",br>in first if";
    echo "<br>";
  }
    return "ACCESS";
  }elseif(updateUserCourse($userCourse['post_id'], $input)){
    if($input['debug']){
    echo "<br>in second if";
  }
    return "ACCESS";
  }else return "NO ACCESS";
}
