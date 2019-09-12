$(function(){
    var currentTime = new Date();
    $('input[name="joining_date"].datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') ),
        locale: {
            format: 'DD-MM-YYYY',
        },
    });

  //page specific script
  if(getPageUrl().toLowerCase().includes("member/add-new-member") ||getPageUrl().toLowerCase().includes("admin/add-new-member") )
  {

    //checking member name availablity on membername change
  $('#chaeckUsername').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    if($('#user_name').val() != "")
    { 
      showFullPageLoader();
      var data = {
        user_name: $('#user_name').val()
       };
      performAjaxCall('/member/ismember','GET',data,function(userId){
        if(userId == false)
        {
          hideFullPageLoader();
          showPopupMessage('Login ID available','Message',false);
        }
        else
        { 
          hideFullPageLoader();
          showPopupMessage('Login ID already in use','Error',true);
        }
  
      })
    }
    else
    {
      showPopupMessage('Please enter a Login ID','Error',true);
    }
  
        
  });


  }
  // set child node availablity based on parent 

    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }



    $('#parentId').keyup(delay(function (e) {
        var data = {
            parent: $(this).val(),
        };
        performAjaxCall('/member/getPositon','GET',data,function(data){
            console.log(data);
           if( data.length === 0){
               // $('input[name="position_id"]').attr('disabled', 'disabled');
               // $('input[name="position_id"]').prop("checked", false);
           }else{
               $.each(data, function (index, value) {
                   $("input[name=position_id][value=" + value + "]").removeAttr('disabled');
               });
           }

        });
    }, 500));







});







