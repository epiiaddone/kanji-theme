<?php

require get_theme_file_path('/includes/kanji-list.php');
get_header();


$learn_url = $_SERVER['REQUEST_URI'];
$lesson_to_play = substr($learn_url, strpos($learn_url, '?num=') + 5, strpos($learn_url, '?cor=') - strpos($learn_url, '?num=') - 5 ) != "" ? strpos($learn_url, '?cor=') - strpos($learn_url, '?num=') - 5 : 1;
$current_correct = substr($learn_url, strpos($learn_url, '?cor=') + 5) !="" ? substr($learn_url, strpos($learn_url, '?cor=') + 5) : 0;
$kanji_for_game = $heisig_kanji[$lesson_to_play];

wp_localize_script('correct-run-game-js','gamedata',
	array(
		'kanji'=>$kanji_for_game,
		'lesson_number'=>$lesson_to_play,
    'current_correct'=>$current_correct,
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
	<div class="answers__button-wrapper" id="kanji-game-answer-wrapper-correct-run">
		<div class="answers__button answers__button--dont-know" id="kanji-game-dont-know"><span> Don't Know</span></div>
	</div>
	<div class="answers__curtain" id="kanji-game-answer-curtain"></div>
</div>

		<div id='kanji-game-over-wrapper' class="game-over">
			 <!--<div class="game-over__title">RESULTS</div>-->
			 <div class="game-over__stats">
				 <div id="game-over-correct" class="game-over__stats__correct-number game-over__stats__text"></div>
				 <i class="fas fa-check game-over__stats__text"></i>
			 </div>
			<div id="game-over-button" class="btn btn--large btn--center btn--bottom" onclick=""></div>
    </div>

<?php


get_footer();
