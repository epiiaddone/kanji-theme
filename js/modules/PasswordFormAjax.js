import $ from 'jquery';

class PasswordFormAjax{

    constructor(){
      this.form = document.getElementById("password-form");
      if(!this.form) return;
      this.successAlert = document.getElementById("password-update-alert-sucess");
      this.failureAlert = document.getElementById("password-update-alert-failure");
      this.currentPass = document.getElementById("cur_pass");
      this.newPass = document.getElementById("new_pass");
      this.repPass = document.getElementById("rep_pass");
      this.passNotMatch = document.getElementById("pass-not-match");
      this.events();
    }

    events(){
      let _this = this;
      this.form.addEventListener("submit", function (event) {
        event.preventDefault();
        _this.successAlert.style.display="none";
        _this.failureAlert.style.display="none";
        _this.passNotMatch.style.display="none";
        if(!_this.verifyInput(_this)){
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          return false;
        }
        _this.sendPassData(_this);
       });
    }

    verifyInput(_this){
        if(!_this.newPass.value || !_this.currentPass.value || !_this.repPass.value) return false;
        if(_this.newPass.value!=_this.repPass.value){
          _this.passNotMatch.style.display="block";
          return false;
        }
        return true;
    }

    sendPassData(_this){
      $.ajax({
        /*
        beforeSend:(xhr)=>{
          xhr.setRequestHeader('X-WP-Nonce', kanjiAppData.nonce)
        },
        */
        url:kanjiAppData.root_url + '/wp-json/user/v1/changePassword',
        type:'POST',
        data:{
          'user_id': kanjiAppData.user_id,
          'new_pass' : _this.newPass.value,
          'rep_pass': _this.repPass.value,
          'password': _this.currentPass.value
        },
        success:(response)=>{//this is never reached regardless of succes or failure
          console.log(response);
          if(response.responseText.includes('@@passwordSuccess@@')){
            console.log("sucess from sendPassData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendPassData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        },
        error:(response)=>{
          console.log(response);
          if(response.responseText.includes('@@passwordSuccess@@')){
            console.log("sucess from sendPassData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendPassData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        }

      });
    }

}

export default PasswordFormAjax;
