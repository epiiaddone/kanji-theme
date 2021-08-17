<?php
get_header();



?>


<div class="text-only">
  <div class="text-only--title">Privacy Policy</div>
  <div class="text-only--content">
  <?php
wp_reset_postdata();
the_content();
?>
</div>
</div>
<div class="text-only--gap"></div>

<?php
 get_footer();
