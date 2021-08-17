import $ from 'jquery';

class NotificationsFormAjax{

    constructor(){
      this.form = document.getElementById("notifications-form");
      if(!this.form) return;
      this.successAlert = document.getElementById("notification-update-alert-sucess");
      this.failureAlert = document.getElementById("notification-update-alert-sucess");
      this.select = document.getElementById("notify");
      this.events();
    }

    events(){
      let _this = this;
      this.select.addEventListener("change", function (event) {
        event.preventDefault();
        _this.successAlert.style.display="none";
        _this.failureAlert.style.display="none";
        _this.sendNotificationData(_this);
       });
    }

    sendNotificationData(_this){
      $.ajax({
        /*
        beforeSend:(xhr)=>{
          xhr.setRequestHeader('X-WP-Nonce', kanjiAppData.nonce)
        },
        */
        url:kanjiAppData.root_url + '/wp-json/user/v1/changeNotifications',
        type:'POST',
        data:{
          'user_id': kanjiAppData.user_id,
          'receive_notifications' : _this.select.value,
        },
        success:(response)=>{//this is never reached regardless of succes or failure
          console.log(response);
          if(response.responseText.includes('@@Notification changed@@')){
            console.log("sucess from sendnotificationData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendnotificationData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        },
        error:(response)=>{
          console.log(response);
          if(response.responseText.includes('@@Notification changed@@')){
            console.log("sucess from sendnotificationData");
            _this.failureAlert.style.display="none";
            _this.successAlert.style.display="block";
          }else{
          console.log("error from sendnotificationData");
          _this.successAlert.style.display="none";
          _this.failureAlert.style.display="block";
          }
        }
      });
    }

}

export default NotificationsFormAjax;
