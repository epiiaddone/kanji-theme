<?php
get_header();
require get_theme_file_path('/includes/kanji-list.php');
require get_theme_file_path('/includes/kanji-list-stats.php');
require get_theme_file_path('/includes/html-chunks.php');
require get_theme_file_path('/includes/user-subscription.php');
?>


  <?php
  if(get_current_user_id()===0){
    echo '<div class="access-denied__logged-out">You need to be logged in to see this page!</div>';
    die();
  }

  //get the user states
  $userStats = new WP_Query(array(
    'post_type'=>'userStats',
    'author'=>get_current_user_id(),
		'posts_per_page'=>-1
  )
  );

  $todaysDate= date('Y-m-d') . '';
  $noUserStats = true;
  $recommendedCompleteToday = null;
  $recommendedOutOfDate = null;
  $todaysRecommendedList = '';
  $userStatsID = null;
  while($userStats->have_posts()){
    $userStats->the_post();
    $userStatsID = get_the_ID();
    $noUserStats= false;
    if($todaysDate== get_field('recommended_lessons_set_date')){
      $recommendedCompleteToday = get_field('recommended_lessons_done_today');
      $recommendedOutOfDate = false;
      $todaysRecommendedList= get_field('recommended_list');
    }else{
      $recommendedOutOfDate = true;
      $recommendedCompleteToday = 0;
      //need to update the user stats
      wp_update_post(array(
        'ID'=>$userStatsID,
        'post_title'=>'user:' . $user_id . ' date: ' . $todaysDate . ' done today: ' . '0' .  'RL: ' . $todaysRecommendedList,
        )
    );
      update_post_meta($userStatsID, 'recommended_lessons_set_date', $todaysDate);
      update_post_meta($userStatsID, 'recommended_lessons_done_today', 0);
    }
  }
  if($noUserStats){
    $recommendedCompleteToday = 0;
    $recommendedOutOfDate = true;
    //need to create user stats
    $data = array(
      'post_title'=>'user:' . $user_id . ' date: ' . $todaysDate . ' done today: ' . '0' .  'RL: ' . $todaysRecommendedList,
      'post_author'=>$user_id,
      'post_type'=>'userstats',
      'post_status'=>'publish'
    );
  $newPostId =   wp_insert_post($data, false);
  update_post_meta($newPostId, 'recommended_lessons_set_date', $todaysDate);
  update_post_meta($newPostId, 'recommended_lessons_done_today', 0);
  $userStatsID = $newPostId;
  }

  wp_reset_postdata();





  $allLessons = new WP_Query(array(
    'post_type'=>'lessonstats',
    'author'=>get_current_user_id(),
		'posts_per_page'=>-1
  )
  );


  $userCurrentProgress = $allLessons->found_posts;
  $totalLessons = sizeof($heisig_kanji,0);
  $progressPercentage = (int)($userCurrentProgress/$totalLessons * 100);
  $userNextLesson = 0;
  $userCurrentLesson = array_keys($heisig_kanji)[$userCurrentProgress-1];
  $lastLesson = array_keys($heisig_kanji)[$totalLessons- 1];

  if($userCurrentProgress < $totalLessons) $userNextLesson = array_keys($heisig_kanji)[$userCurrentProgress];


?>
<div class="page__statistics__section">
<div class="current-progress">
    <div class="current-progress__section current-progress__section--top">
      <div class="current-progress__title">Current Progress</div>
      <div class="percentage-chart">
        <div class="percentage-chart__bar">
          <div class="percentage-chart__fill"style="width:<?php echo $progressPercentage?>%">
            <div class="percentage-chart__fill--color" ></div>
          </div>
        </div>
        <div class="percentage-chart__percentage"><?php echo $progressPercentage ?>%</div>
        <div class="percentage-chart__subtitle"><?php echo $userCurrentProgress . " out of " . $totalLessons . " sections completed"?></div>
        <!--<div class="percentage-chart__extra-title"><?php //echo $kanjiCount[$userCurrentLesson] . " out of " . $kanjiCount[$lastLesson] . " kanji covered";?></div>-->

      </div>
    </div>
    <div class="current-progress__bottom-row">
      <div class="current-progress__section current-progress__section--middle">
        <div class="current-progress__title">Kanji Learnt</div>
        <div class="kanji-done">
          <span class="kanji-done__learned"><?php echo $kanjiCount[$userCurrentLesson] ?></span>
          <span class="">/<span>
          <span class="kanji-done__total"><?php echo $kanjiCount[$lastLesson] ?></span>
        </div>
      </div>
    <div class="current-progress__section current-progress__section--bottom">
      <div class="current-progress__title">Next Section</div>


        <?php
        if($userNextLesson>0){
          ?>
          <div class="current-progress__next-lesson">
            <?php if(!userHasLessonAccess($userNextLesson)){?>
              <span class="access-denied__subscription-needed"><span>Subscription needed</span></span>
            <?php }else{?>
            <span class="text--main text--heavy"><?php echo $userNextLesson; ?></span>
            <div class="btn btn--next-lesson text--heavy text--main" onclick="location.href='<?php echo get_site_url() . '/learn/?num=' . $userNextLesson; ?>'">Start</div>
          </div>
      <?php }}else{
        ?>
        <div class="current-progress__next-lesson--complete">
          <div>All Lessons Studied!</div>
        </div>
      <?php
      }
      ?>
      </div>
    </div>
  </div>
</div>


<?php

  $lessonData = [];
  $lessonDateReviewed = [];
  $lessonAttempts = [];
  $lessonCorrectAverage =[];
  $lessonSRS = [];

while($allLessons->have_posts()){
  $allLessons->the_post();
  $lesson_number = get_field('lesson_number');
  $date_expire = get_the_modified_date('c');
  $date = new DateTime($date_expire);
  $now = new DateTime('c');
  $interval = date_diff($date, $now);
  $date_for_numeric = (int)$interval->format('%a');
  //echo $date_for_numeric . '<br>';
  $date_since_last_review = $date_for_numeric;
  $attempts = (int)get_field('attempts');
  $first_incorrect = (int)get_field('incorrect_answers_first');
  $second_incorrect = (int)get_field('incorrect_answers_second');
  $third_incorrect = (int)get_field('incorrect_answers_third');
  $incorrect_average = 0;
  if($attempts ==1) $incorrect_average = $first_incorrect;
  if($attempts ==2) $incorrect_average = ($first_incorrect + $second_incorrect)/2;
  if($attempts >2) $incorrect_average = ($first_incorrect + $second_incorrect + $third_incorrect)/3;
  $newLessonBonus =0;
  if($attempts >0 && $attempts <5) $newLessonBonus = 10 - $attempts;
  //logic for next reviews
  $comparison = (int)$date_for_numeric + $newLessonBonus + $incorrect_average * 2;
  if($date_for_numeric==0) $comparison = $incorrect_average;
  $incorrectAveragePercent = (int)(100 - (100 * $incorrect_average/count($heisig_kanji[$lesson_number]))) . "%";

  $lessonDateReviewed[$lesson_number] = $date_for_numeric;
  $lessonAttempts[$lesson_number] = $attempts;
  $lessonCorrectAverage[$lesson_number] = $incorrect_average;
  $lessonSRS[$lesson_number] = $comparison;
  $lessonData[$lesson_number] = [
    'date-since-last-review'=>$date_since_last_review,
    'attempts'=>$attempts,
    'incorrect-average-percent'=>$incorrectAveragePercent,
    'comparison'=>$comparison,
    'incorrect-average-integer'=>(int)$incorrect_average
  ];

}

arsort($lessonDateReviewed);
asort($lessonAttempts);
arsort($lessonCorrectAverage);
arsort($lessonSRS);
?>
<div class="page__statistics__section">
<div class="recommended-reviews">
  <div class="recommended-reviews__title">Today's Recommended Reviews</div>
  <div id="lesson-srs" class="lesson-box--visible">
  <?php
  if($recommendedOutOfDate==true){
  $count = 0;
  $RECOMMENDEDREVIEWSCONST = 5;
foreach($lessonSRS as $key => $value){
  $count++;
  if($lessonData[$key]['date-since-last-review']==0) continue;
  if($count > $RECOMMENDEDREVIEWSCONST - $recommendedCompleteToday) break;
  if($recommendedOutOfDate==true){
    $todaysRecommendedList = $todaysRecommendedList . '{' . $key . '}';
  }
  lessonItem($lessonData, $key);

}


wp_update_post(array(
  'ID'=>$userStatsID,
  'post_title'=>'user:' . $user_id . ' date: ' . $todaysDate . ' done today: ' . '0' .  'RL: ' . $todaysRecommendedList,
  )
);
update_post_meta($userStatsID, 'recommended_lessons_set_date', $todaysDate);
update_post_meta($userStatsID, 'recommended_lessons_done_today', 0);
update_post_meta($userStatsID, 'recommended_list', $todaysRecommendedList);
}else{
  foreach($lessonSRS as $key => $value){
    if($lessonData[$key]['date-since-last-review']==0) continue;
    $search_key = '{' . $key . '}';
    if(strpos($todaysRecommendedList, $search_key) > -1 ){
      lessonItem($lessonData,$key);
    }
}
}
?>
</div>
</div>
</div>
<div class="page__statistics__section">
<div class="completed-sections">
      <div class="completed-sections__title">Completed Sections</div>
      <div class="completed-sections__orderby">Order By
      <select id="completed-sections-select">
        <option value="srs-option" selected="true">SRS Order</option>
        <option value="review-date-option">Last Review Date</option>
        <option value="least-correct-option">Most Incorrect</option>
        <option value="least-attempted-option">Least Attempted</option>
      </select>
      </div>

<div id="least-attempted-lesson-box" class="lesson-box">
  <?php
foreach($lessonAttempts as $key => $value){
  lessonItem($lessonData,$key);
}
?>
</div>

<div id="review-date-lesson-box" class="lesson-box">
  <?php
foreach($lessonDateReviewed as $key => $value){
  lessonItem($lessonData,$key);
}
?>
</div>

<div id="lesson-correct-lesson-box" class="lesson-box">
  <?php
foreach($lessonCorrectAverage as $key => $value){
  lessonItem($lessonData,$key);
}
?>
</div>

<div id="srs-lesson-box" class="lesson-box">
  <?php
foreach($lessonSRS as $key => $value){
  lessonItem($lessonData,$key);
}
?>
</div>

</div>
</div>
<?php
wp_reset_postdata();

/*
echo "lessonDateReviwed: ";
print_r($lessonDateReviewed);
echo "<br>" .  "lesson data reviewed sorted :";
arsort($lessonDateReviewed);
print_r($lessonDateReviewed);

echo "<br>" . "lesson attempts:";
print_r($lessonAttempts);
echo "<br>" . " lesson attempts sorted";
asort($lessonAttempts);
print_r($lessonAttempts);

echo "<br>" . "lesson correct average";
print_r($lessonCorrectAverage);
arsort($lessonCorrectAverage);
echo "<br>" . "lesson correct av sorted";
print_r($lessonCorrectAverage);

echo "<br>" . "lesson srs";
print_r($lessonSRS);
arsort($lessonSRS);
echo "<br>". "lesson srs  sorted";
print_r($lessonSRS);

echo "<br>" . "lesson data";
print_r($lessonData);
*/
 ?>


<?php

get_footer();
