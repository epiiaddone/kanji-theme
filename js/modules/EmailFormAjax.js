import $ from 'jquery';

class EmailFormAjax{

    constructor(){
      this.form = document.getElementById("email-form");
      if(!this.form) return;
      this.successAlert = document.getElementById("email-update-alert-sucess");
      this.failureAlert = document.getElementById("email-update-alert-failure");
      this.currentEmail = document.getElementById("current-email-address");
      this.events();
    }

    events(){
      let _this = this;
      this.form.addEventListener("submit", function (event) {event.preventDefault();_this.sendEmailData(_this); });
    }

    sendEmailData(_this){
      let email = document.getElementById('email').value;
      if(email===null || email==='') return;
      let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if(!re.test(String(email).toLowerCase())) return;
      let pass = document.getElementById('email_pass').value;
      if(pass===null || pass==='') return;
      let email_response_start="@@email=";
      let email_response_end = "@@end";
      $.ajax({
        /*
        beforeSend:(xhr)=>{
          xhr.setRequestHeader('X-WP-Nonce', kanjiAppData.nonce)
        },
        */
        url:kanjiAppData.root_url + '/wp-json/user/v1/changeEmail',
        type:'POST',
        data:{
          'user_id': kanjiAppData.user_id,
          'email' : email,
          'password': pass
        },
        success:(response)=>{//this is never reached regardless of succes or failure
          console.log(response);
          if(response.responseText.includes(email_response_start) && response.responseText.includes(email_response_end)){
            let start_index = response.responseText.indexOf(email_response_start) + email_response_start.length;
            let end_index = response.responseText.indexOf(email_response_end);
            let new_email = response.responseText.slice(start_index,end_index);
            console.log('[' + new_email + ']');
            _this.currentEmail.innerHTML = new_email;
            console.log("sucess from sendEmailData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendEmailData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        },
        error:(response)=>{
          console.log(response);
          if(response.responseText.includes(email_response_start) && response.responseText.includes(email_response_end)){
            let start_index = response.responseText.indexOf(email_response_start) + email_response_start.length;
            let end_index = response.responseText.indexOf(email_response_end);
            let new_email = response.responseText.slice(start_index,end_index);
            console.log('[' + new_email + ']');
            _this.currentEmail.innerHTML = new_email;
            console.log("sucess from sendEmailData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendEmailData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        }

      });
    }

}

export default EmailFormAjax;
