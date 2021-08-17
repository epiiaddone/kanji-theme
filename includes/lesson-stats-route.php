<?php

add_action('rest_api_init', 'lessonStatsData');

function lessonStatsData(){
  register_rest_route('kanji/v1', 'manageLessonStats',array(
    'methods' =>'POST',
    'callback' => 'inputStats'
  ));
}

function inputStats($data){

  if($data['user_id']>0){
    //need lesson_number and incorrect number from the js
      $lessonNumber = sanitize_text_field($data['lessonNumber']);
      $incorrect = $data['incorrect'];
      $user_id = $data['user_id'];
      $lessonID = 0;
      $attempts = null;
      $incorrect_first = null;
      $incorrect_second = null;
      $incorrect_third = null;


      $userQuery = new WP_Query(array(
        'post_type'=>'userStats',
        'author'=>$user_id,
    		'posts_per_page'=>-1
      )
      );

      $todaysDate= date('Y-m-d') . '';
      $todaysRL = '';
      $todaysRLDone = 0;
      $userQueryDate = '';
      $userStatsID = null;
      while($userQuery->have_posts()){
        $userQuery->the_post();
        $todaysRL = get_field('recommended_list');
        $todaysRLDone = get_field('recommended_lessons_done_today');
        $userQueryDate = get_field('recommended_lessons_set_date');
        $userStatsID = get_the_ID();
        $listOfDone = get_field('recommended_done_today');
      }


      $args = array(
          'author'=>$user_id,
          'post_type'=>'lessonstats',
          'posts_per_page'=>-1,
          'meta_query'=>array(
            array(
            'key' => 'lesson_number',
            'compare'=> '=',
            'value'=>$lessonNumber
          )
          )
          );

      // Custom query.
      $query = new WP_Query( $args );

      // Check that we have query results. s
      if ( $query->have_posts() ) {

          // Start looping over the query results.
          while ( $query->have_posts() ) {

              $query->the_post();

              // Contents of the queried post results go here.
              echo get_the_title();
              $lessonID = get_the_ID();
              $incorrect_first =  get_field('incorrect_answers_first');
              $incorrect_second =  get_field('incorrect_answers_second');
              $incorrect_third =  get_field('incorrect_answers_third');
              $attempts =  get_field('attempts') + 1;
          }
          wp_reset_postdata();

          echo "from lesson stats lesson id is " . $lessonID . "||";
                if(get_post_type($lessonID) == 'lessonstats'){
                  //update the lesson data now
                  if($incorrect_second>0) update_post_meta($lessonID, 'incorrect_answers_third', $incorrect_second );
                  if($incorrect_first>0) update_post_meta($lessonID, 'incorrect_answers_second', $incorrect_first );
                  update_post_meta($lessonID, 'incorrect_answers_first', $incorrect);
                  update_post_meta($lessonID, 'attempts', $attempts);
                    wp_update_post(array(
                      'ID'=>$lessonID,
                      'post_title'=>'user:' . $user_id . '||lesson number: ' . $lessonNumber . '||incorrect_first: ' . $incorrect . '||attempts: ' . $attempts,
                      )
                  );

                  echo " //todaysRL: " . $todaysRL;
                  echo "//lessonNumber: " . $lessonNumber;
                  echo "//listOfDone: " . $listOfDone;
                  echo "//todaysRLDone: " . $todaysRLDone;
                  echo "//userstatsID: " . $userStatsID;
                  echo "//userid: " . $user_id;
                  echo "//get_current_user_id(): " . get_current_user_id();//this is returning 0 as the current user id
                  if(stripos($todaysRL,'{' . $lessonNumber . '}')!==false && stripos($listOfDone, '{' . $lessonNumber . '}')===false){
                    echo "inside the if {} stuff";
                    $todaysRLDone++;
                    wp_update_post(array(
                      'ID'=>$userStatsID,
                      'post_title'=>'user:' . $user_id . ' date: ' . $todaysDate . ' done today: ' . $todaysRLDone .  'RL: ' . $todaysRL,
                      )
                  );
                    update_post_meta($userStatsID, 'recommended_lessons_done_today', $todaysRLDone);
                    update_post_meta($userStatsID, 'recommended_done_today', $listOfDone . '{' . $lessonNumber . '}' );
                  }

                    return;
                }else{
                  die("cannot edit data for this lesson");
                }

      }else{
        //no lesson data- this is a new lesson to be saved
        $data = array(
          'post_title'=>'user:' . $user_id . '||lesson number: ' . $lessonNumber . '||incorrect_first: ' . $incorrect . '||attempts: 1',
          'post_author'=>$user_id,
          'post_type'=>'lessonstats',
          'post_status'=>'publish'
        );
      $newPostId =   wp_insert_post($data, false);
      update_post_meta($newPostId, 'incorrect_answers_first', $incorrect);
      update_post_meta($newPostId, 'attempts', 1);
      update_post_meta($newPostId, 'lesson_number', $lessonNumber);
      }
  }else{
    die("only logged in users can edit lesson data");
  }
}
