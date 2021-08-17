<?php

require get_theme_file_path('/includes/kanji-list.php');
require get_theme_file_path('/includes/user-subscription.php');
get_header();



$learn_url = $_SERVER['REQUEST_URI'];
$lesson_to_play = substr($learn_url, strpos($learn_url, '?num=') + 5);
$kanji_for_game = $heisig_kanji[$lesson_to_play];

if(get_current_user_id()===0){
	echo '<div class="access-denied__logged-out">You need to be logged in to see this page!</div>';
	die();
}

if(!userHasLessonAccess($lesson_to_play)){
	echo '<div class="access-denied__lesson-limit">You need a subscription to go beond lesson 4</div>';
	die();
}

if(!isValidLesson($lesson_to_play,$heisig_kanji)){
	echo '<div class="access-denied__lesson-limit">This is not a valid lesson!</div>';
	die();
}


if(get_current_user_id()>0){
	$allLessons = new WP_Query(array(
		'post_type'=>'lessonstats',
		'author'=>get_current_user_id(),
		'posts_per_page'=>-1
	)
	);

	$userUnlockedLessons = array();
	while($allLessons->have_posts()){
		$allLessons->the_post();
		array_push($userUnlockedLessons,get_field('lesson_number'));
	}

 $userCurrentProgress = $allLessons->found_posts;
 $totalLessons = sizeof($heisig_kanji,0);
 if($userCurrentProgress < $totalLessons) $userNextLesson = array_keys($heisig_kanji)[$userCurrentProgress];
 if($userNextLesson != $lesson_to_play AND !in_array($lesson_to_play, $userUnlockedLessons)){
	 echo '<div class="access-denied__lesson-limit">You cannot complete this lesson yet</div>';
 	die();
 }

wp_reset_postdata();
}

wp_localize_script('game-js','gamedata',
	array(
		'kanji'=>$kanji_for_game,
		'lesson_number'=>$lesson_to_play,
	));

?>
<div class="game-character-area" id="game-character-area">
	<div class="game-character-area__lesson-box" >
		<span >L</span>
		<span id="kanji-game-lesson-number" ><?php echo $lesson_to_play; ?></span>
	</div>
	<div class="game-character-area__lesson-stats-box">
		<span id="kanji-game-mistake-number" class="game-text">0</span>
		<i class="fas fa-times"></i>
		<span id="kanji-game-correct-number" class="game-text">0</span>
		<i class="fas fa-check"></i>
		<span id="kanji-game-remaining-number" class="game-text"></span>
		<span id="kanji-game-remaining-title" class="game-text">Remaining</span>
	</div>
	<div class="game-character-area__lesson-review-box" id="lesson-review-box">
		<div id="lesson-review-box__title">Practise Mode</div>
		<div id="lesson-review-box__number">23</div>
		<div id="lesson-review-box__to-review">To Review</div>
	</div>
	<div class="game-character-area__game-character-display" id="kanji-game-character"></div>
</div>

<div class="answers" id="game-answer-area">
	<div class="answers__button-wrapper" id="kanji-game-answer-wrapper">
		<div class="answers__button answers__button--dont-know" id="kanji-game-dont-know"><span> Don't Know</span></div>
	</div>
	<div class="answers__curtain" id="kanji-game-answer-curtain"></div>
</div>

		<div id='kanji-game-over-wrapper' class="game-over">
			 <!--<div class="game-over__title">RESULTS</div>-->
			 <div id="game-over-percent" class="game-over__percent"></div>
			 <div class="game-over__stats">
				 <div id="game-over-correct" class="game-over__stats__correct-number game-over__stats__text"></div>
				 <i class="fas fa-check game-over__stats__text"></i>
				 <div id="game-over-incorrect" class="game-over__stats__wrong-number game-over__stats__text"></div>
				 <i class="fas fa-times game-over__stats__text"></i>
			 </div>
			<div class="btn btn--large btn--center btn--bottom" onclick="location.href='<?php echo get_site_url() . "/statistics"; ?>'">OK</div>
		</div>

<?php


get_footer();
 ?>
