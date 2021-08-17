<?php
get_header();
 ?>




<?php
if(get_current_user_id()===0){
  echo '<div class="access-denied__logged-out">You need to be logged in to see this page!</div>';
  die();
}

$current_user = wp_get_current_user();
if ( ! $current_user->exists() ) {
    die("user not logged in");
 }
?>
<div class="profile-section">
  <div class="profile-section__title">Username</div>
  <div class="profile-section__text">Your username is <span class="profile-section__text--bold"><?php echo $current_user->user_login ?></span></div>
  <div class="profile-section__text">You are not allowed to change your Username</div>
</div>

<div class="profile-section">
  <div class="profile-section__title">Email</div>
  <div class="profile-section__email"><span>Current Email: </span><span id="current-email-address" class="profile-section__email--highlighted"><?php echo $current_user->user_email ?></span></div>
  <div class="profile-section__text">
    You can change the email address registered to this account.</br>
     We will send you a confirmation email to the new address.</br>
    You are required to click the link in the email to complete the change.
  </div>
  <form method="POST" action="" id="email-form">
    <div class="profile-section__form-container">
    <label for="email">New email address</label></br> <input type="email" id="email" name="user_email" min="4" max="50" >
  </div>
  <div class="profile-section__form-container">
    <label for="email_pass">Password</label></br><input type="password" id="email_pass" min="3" max="50">
  </div>
        <button type="submit">Change Email</button>
   </form>
   <div class="profile-section__update-alert profile-section__update-alert--success" id="email-update-alert-sucess">Email Sucessfully Changed</div>
   <div class="profile-section__update-alert profile-section__update-alert--failure" id="email-update-alert-failure">Email Change Failed!</div>
 </div>

 <div class="profile-section">
   <div class="profile-section__title">Password</div>
   <div class="profile-section__text">You can change you password</div>
   <form method="POST" action="" id="password-form">
     <div class="profile-section__form-container">
     <label for="cur_pass">Current Password</label></br> <input type="password" id="cur_pass" name="cur_pass" min="4" max="50" >
   </div>
     <div class="profile-section__form-container">
     <label for="new_pass">New Password</label></br><input type="password" id="new_pass" name ="new_pass" min="3" max="50">
   </div>
     <div class="profile-section__form-container">
     <label for="rep_pass">Repeat New Password</label></br><input type="password" id="rep_pass" name ="rep_pass" min="3" max="50">
   </div>
         <button type="submit">Change Password</button>
    </form>
    <div class="profile-section__update-alert profile-section__update-alert--failure" id="pass-not-match">Passwords do not match!</div>
    <div class="profile-section__update-alert profile-section__update-alert--success" id="password-update-alert-sucess">Password Sucessfully Changed</div>
    <div class="profile-section__update-alert profile-section__update-alert--failure" id="password-update-alert-failure">Password Change Failed!</div>
 </div>

 <?php
   $query = new WP_Query(array(
       'post_type'=>'userStats',
       'author'=>get_current_user_id(),
   		'posts_per_page'=>-1
   ));
   $select_yes = false;;
   $select_no = false;
   while($query->have_posts()){
     $query->the_post();
     //echo get_field('receive_notifications');
     //echo get_field('recommended_lessons_done_today');
     if(get_field('receive_notifications')==1) $select_yes=true;
     else $select_no=true;
   }
   wp_reset_postdata();
 ?>
 <div class="profile-section">
   <div class="profile-section__title">Recieve Emails</div>
   <div class="profile-section__text">Would you like to recieve emails about updates and offers?</div>
   <form method='POST' action="" id="notifications-form">
     <select class="forms__select" name="notify" id="notify">
       <option value="true" <?php if($select_yes) echo 'selected="selected"'; ?>>Yes</option>
       <option value="false" <?php if($select_no) echo 'selected="selected"'; ?>>No</option>
     </select>
   </form>
   <div class="profile-section__update-alert profile-section__update-alert--success" id="notification-update-alert-sucess">Notification Preference Changed</div>
   <div class="profile-section__update-alert profile-section__update-alert--failure" id="notification-update-alert-failure">Notificaton Change Failed!</div>
 </div>



 <?php
 get_footer();
 ?>
