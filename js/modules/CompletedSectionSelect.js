import $ from 'jquery';

class CompletedSectionSelect{
  constructor(){
      this.select = $('#completed-sections-select');
      if(!this.select[0]) return;
      this.leastAttemptedDiv = $('#least-attempted-lesson-box');
      this.reviewDateDiv = $('#review-date-lesson-box');
      this.correctAnswersDiv = $('#lesson-correct-lesson-box');
      this.srsDiv = $('#srs-lesson-box');
      this.events();
      this.updateLessons();
  }

  events(){
    this.select.on('change',this.updateLessons.bind(this));
  }

  updateLessons(){
    console.log(this);
    let value = this.select[0].value;
    console.log(value);
    this.leastAttemptedDiv[0].style.display='none';
    this.reviewDateDiv[0].style.display='none';
    this.correctAnswersDiv[0].style.display='none';
    this.srsDiv[0].style.display='none';
    let divToShow = null;
    switch(value){
      case 'srs-option': divToShow = this.srsDiv; break;
      case 'review-date-option': divToShow = this.reviewDateDiv; break;
      case 'least-correct-option': divToShow = this.correctAnswersDiv; break;
      case 'least-attempted-option': divToShow = this.leastAttemptedDiv; break;
    }
    divToShow[0].style.display='block';
  }



}

export default CompletedSectionSelect;
