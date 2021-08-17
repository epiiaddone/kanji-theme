
//currently this file is being loaded on every page
//so using this rubbish to get this file to run on page learn only
let id_of_div_only_in_page_learn = document.getElementById('kanji-game-answer-wrapper');
let is_this_front_page = document.getElementById('this-is-front-page');
if(id_of_div_only_in_page_learn && !is_this_front_page ){

  let lesson_number = gamedata.lesson_number;
  let kanji = gamedata.kanji;
  let startingLength = kanji.length;
  let endLength = kanji.length;

  let characterDiv = document.getElementById('kanji-game-character');
  let randomKanjiPairs = {};
  let currentCharacterIndex = 0;
  let currentCharacter = '';
  let currentCorrectAnswer = '';
  let selected = '';
  let mistakesCount = 0;
  let correctCount = 0;
  let remaining = startingLength;
  let toReview = 0;
  let inFreePractise = false;

  let shuffle = function(array) {
      var currentIndex = array.length, temporaryValue, randomIndex;

      // While there remain elements to shuffle...
      while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }
  return array;
  }

  let buildButtons = function(kanjiPairs){
    let dontKnowButton = document.getElementById('kanji-game-dont-know');
  	let buttonContainer = document.getElementById('kanji-game-answer-wrapper');
  	buttonContainer.innerHTML ='';
  	 let randomKanjiPairs = shuffle(kanjiPairs);
  	 for(let i=0; i<randomKanjiPairs.length;i++){
  		let div = document.createElement("div");
  		div.className = 'answers__button'
      let span = document.createElement("span");
  		span.innerHTML = randomKanjiPairs[i][2];
  		div.addEventListener('pointerdown',function(){checkAnswer(div)});
      div.appendChild(span);
  		buttonContainer.appendChild(div);
  	}
    buttonContainer.appendChild(dontKnowButton);
  }

  let gameRun = function(){
  	buildButtons(kanji);
    setupUI();
  	startGame(kanji);
  }

  let dontKnowClicked = function(){
    var answers = $('.answers__button');
    for(var i=0; i<answers.length; i++){
      if(answers[i].children[0].innerHTML == currentCorrectAnswer){
        answerAnimation(answers[i], '#ffa63a');
        break;
      }
    }
    let dummyDiv = document.createElement('div');
    let dummySpan = document.createElement('span');
    dummyDiv.appendChild(dummySpan);
    checkAnswer(dummyDiv);
  }

  let setupUI = function(){

    document.getElementById('kanji-game-dont-know').addEventListener( "pointerdown", dontKnowClicked );
  }

  let startGame = function(kanjiPairs){
  	randomKanjiPairs = shuffle(kanjiPairs);
  	characterDiv.innerHTML = randomKanjiPairs[0][1];
  	currentCharacter = randomKanjiPairs[0][1];
  	currentCorrectAnswer = randomKanjiPairs[0][2];
  	currentCharacterIndex = 0;
  	mistakesCount = 0;
  	document.getElementById('kanji-game-mistake-number').innerHTML=mistakesCount;
    document.getElementById('kanji-game-remaining-number').innerHTML=remaining;
  }

  let checkAnswer = function(div)
  {
    disableAnswerButtons();
	if(!inFreePractise){
      remaining--;
      document.getElementById('kanji-game-remaining-number').innerHTML=remaining;
	  if(div.children[0].innerHTML == currentCorrectAnswer){
  		answerAnimation(div,'#b7ed93');
        correctCount++;
        document.getElementById('kanji-game-correct-number').innerHTML=correctCount;
      }else{
		answerAnimation(div,'red');
		mistakesCount++;
		document.getElementById('kanji-game-mistake-number').innerHTML=mistakesCount;
		endLength++;
		let newIndex = randomKanjiPairs.length;
		randomKanjiPairs[newIndex]=['','',''];
		randomKanjiPairs[newIndex][0]=0;
		randomKanjiPairs[newIndex][1]= currentCharacter;
		randomKanjiPairs[newIndex][2] = currentCorrectAnswer;
	  }
    }else{
		if(div.children[0].innerHTML == currentCorrectAnswer){
			answerAnimation(div,'#b7ed93');
			toReview--;
			document.getElementById('lesson-review-box__number').innerHTML=toReview;
		}else{
			answerAnimation(div,'red');
			endLength++;
			let newIndex = randomKanjiPairs.length;
			randomKanjiPairs[newIndex]=['','',''];
			randomKanjiPairs[newIndex][0]=0;
			randomKanjiPairs[newIndex][1]= currentCharacter;
			randomKanjiPairs[newIndex][2] = currentCorrectAnswer;
		}
	}

	currentCharacterIndex++;
  if(currentCharacterIndex === endLength){
      setTimeout(function(){gameOver();},1000);
      return;
    }
	if(currentCharacterIndex === startingLength) freePractiseMode();
	currentCharacter = randomKanjiPairs[currentCharacterIndex][1];
    setTimeout(function(){characterDiv.innerHTML = currentCharacter;},1000);
    currentCorrectAnswer = randomKanjiPairs[currentCharacterIndex][2];

  }

  let answerAnimation = function(buttonClicked, color){
  	buttonClicked.style.backgroundColor  = color;
  	characterDiv.style.borderColor = color;
  	setTimeout(function(){
  						buttonClicked.style.backgroundColor = '#efefef';
  						characterDiv.style.borderColor = 'black';
  						},1000);
  }

  let gameOver = function(){
  	characterDiv.innerHTML = 'END';
    sendLessonData();
    endGameAnimation();
    }

  let sendLessonData = function(){
    $.ajax({
      /*
      beforeSend:(xhr)=>{
        xhr.setRequestHeader('X-WP-Nonce', kanjiAppData.nonce)
      },
      */
      url:kanjiAppData.root_url + '/wp-json/kanji/v1/manageLessonStats',
      type:'POST',
      data:{
        'user_id': kanjiAppData.user_id,
        'lessonNumber': lesson_number,
        'incorrect': mistakesCount
      },
      success:(response)=>{
        console.log("success from sendLessonData");
      },
      error:(response)=>{
        console.log(response);
      }

    });
  }

  let endGameAnimation= function(){
      let answerWrapperDiv = document.getElementById('kanji-game-answer-wrapper');
      answerWrapperDiv.style.display='none';
      let gameOverWrapperDiv = document.getElementById('kanji-game-over-wrapper');
      gameOverWrapperDiv.style.display='block';
      $('#lesson-review-box')[0].style.display='none';
      $('#game-over-correct')[0].innerHTML=correctCount;
      $('#game-over-incorrect')[0].innerHTML = mistakesCount;
      let percentCorrect = Math.round(correctCount/(correctCount + mistakesCount) *100);
      $('#game-over-percent')[0].innerHTML=percentCorrect + '%';
  }

  let freePractiseMode = function(){
    toReview = mistakesCount;
    inFreePractise = true;
    $('#game-character-area').removeClass('bg-second');
    $('#game-character-area').addClass('bg-third');
    $('#lesson-review-box__number')[0].innerHTML = toReview;
    $('#lesson-review-box')[0].style.display='block';
  }

  let disableAnswerButtons = function(){
    $('#kanji-game-answer-curtain')[0].style.display ='block';
    setTimeout(function(){$('#kanji-game-answer-curtain')[0].style.display = 'none';},1000);
  }

  gameRun();

}
