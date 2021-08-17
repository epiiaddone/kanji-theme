<?php

require get_theme_file_path('/includes/kanji-list.php');

$kanjiAmount = 0;
$kanjiCount = [];
$lessonSizes = [];
$longestPhraseLesson = null;
$longestPhraseSize = 0;
$longestWordSize = 0;
$longestWordLesson = null;

foreach ($heisig_kanji as $key => $value){
    $kanjiAmount+= sizeof($value);
    $kanjiCount[$key] = $kanjiAmount;
    $lessonSizes[$key] = sizeof($value);
    for($i=0; $i<sizeof($value); $i++){
      $tempPhraseLength = strlen($value[$i][2]);
      if($tempPhraseLength > $longestPhraseSize){
        $longestPhraseSize = $tempPhraseLength;
        $longestPhraseLesson = $key;
      }
      $tempWordArray = explode(" ",$value[$i][2]);
      foreach ($tempWordArray as $arrayKey => $word){
        if(strpos($word, "-") > 0) continue;
        if(strlen($word) > $longestWordSize){
          $longestWordSize = strlen($word);
          $longestWordLesson = $key;
        }
      }

    }
  }
