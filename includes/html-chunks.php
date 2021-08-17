<?php

function lessonItem($lessonData, $key){
  ?>
  <div class="lesson-box__list-item">
    <div class="text--heavy text--main lesson-box__list-item--wide"> <?php echo $key;?></div>
    <div class="lesson-box__list-item--incorrect lesson-box__list-item--wide">
      <i class="fa fa-times"></i>
      <?php echo $lessonData[$key]['incorrect-average-integer'];?>
  </div>
    <div class="lesson-box__list-item--time lesson-box__list-item--wide">
      <i class="far fa-calendar-alt"></i>
      <?php echo $lessonData[$key]['date-since-last-review']; ?>
  </div>
    <div class="lesson-box__list-item--attempts lesson-box__list-item--wide">
    <i class="fas fa-graduation-cap"></i>
     <?php echo $lessonData[$key]['attempts'];?>
   </div>
    <div class="btn btn--lesson text--main text--heavy" onclick="location.href='<?php echo get_site_url() . '/learn/?num=' . $key; ?>'">Review</div>
  </div>
  <?php
}
