<?php
get_header();
require get_theme_file_path('/includes/user-subscription.php');

if(get_current_user_id()===0){
  echo '<div class="access-denied__logged-out">You need to be logged in to see this page!</div>';
  die();
}
if(userHasSubscription()){
  ?>
  <div class="subscription">
  <div class="subscription__content">
    <div class="subscription__content--title">Lifetime Subscription</div>
    <div class="subscription__content--text">You Have Already Purchased a Lifetime Subscription.</div>
  </div>
</div>
<?php
}else{


?>
<div class="subscription">

  <div class="subscription__content">
    <div class="subscription__content--title">Lifetime Subscription</div>
    <div class="subscription__content--text">
      <p>Purchase a lifetime subscription and you'll learn the Kanji in no time.</p>
      <ul>
        <li><span>Master</span> all 2200 Kanji</li>
        <li><span>Unlimited</span> reviews</li>
        <li><span>Personalized</span> learning with SRS system</li>
        <li><span>Ideal</span> companion to the Heisig book</li>
      </ul>
</div>
<div class="product-page">
  <p>Test</p>
  <?php
if (have_posts()){
  while (have_posts()) : the_post();
    the_content();
  endwhile;
}

?>
</div>
</div>
</div>


<?php
}
get_footer();
