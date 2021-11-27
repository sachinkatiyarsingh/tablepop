Array.prototype.removeValue = function(name, value){
  var array = $.map(this, function(v,i){
      return v[name] === value ? null : v;
  });
  this.length = 0; //clear original array
  this.push.apply(this, array); //push all elements except the one we want to delete
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){  	
    $(".delete").click(function() {
        var id = $(this).data("id");
        var url = $('.url').val();
        var move = $(this).parents('.move');
   const swalWithBootstrapButtons = Swal.mixin({
     customClass: {
        confirmButton: 'btn btn-primary',
       cancelButton: 'btn btn-danger'
     },
     buttonsStyling: false
   })
   
   swalWithBootstrapButtons.fire({
     title: 'Are you sure?',
     type: 'question',
     background:"#EFEAE6",
     showCancelButton: true,
     confirmButtonText: 'Yes, delete it!',
     cancelButtonText: 'cancel!',
     reverseButtons: true
   }).then((result) => {
     if (result.value) {
           $.ajax({
           url: url+'/' +id,
             
             success: function () {
                 move.remove();	
               swalWithBootstrapButtons.fire({
               title: 'Deleted!',
               type: 'success',
               background:"#EFEAE6",
               confirmButtonText: 'Ok',
               })  
               },
           });  	
    
     } else if (
       /* Read more about handling dismissals below */
       result.dismiss === Swal.DismissReason.cancel
     ) {
               swalWithBootstrapButtons.fire({
     title: 'Cancelled!',
     type: 'error',
     background:"#EFEAE6",
     confirmButtonText: 'Ok',
    
   })
       
     }
   })
   
   });
     
   });  


   $(document).on('change','.country',function(){
    var id = $(this).val();
    $.ajax({
      url: base_url + '/customers/states/' +id,
      dataType:'json',
        success: function (respons) {
          $('.states').html('')
          $('.states').html(respons)
        }
      });  	
})

   $(document).on('click','.notificationDelete',function(){
    var id = $(this).data('id');
    var current = $(this).parents('.notificationMove');
    $.ajax({
      url: base_url + '/notification-status',
      data :{ id: id },
      type: 'POST',
        success: function (respons) {
         
          current.remove()
        }
      });  	
})


/* $(document).ready(function () {
  var token = $('#token').val();
  $('#customer').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax":{
               "url": "customers-details",
               "dataType": "json",
               "type": "POST",
               "data":{ _token: token}
             },
      "columns": [
          { "data": "image" },
          { "data": "name" },
          { "data": "email" },
          { "data": "mobile" },
          { "data": "project" },
          { "data": "invoices" },
          { "data": "action" },
       
      ]	 

  });
}); */
$(document).ready(function() {
  var ids = $('#id').val();
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  $('#calendar').fullCalendar({
      header: {
          left: 'prev, next today',
          center: 'title',
          right: 'month, basicWeek, basicDay'
      },
      
      events: function (start, end, timezone, callback) {
              $.ajax({
                  url: base_url+"/calendar/" + ids,
                  type: "GET",
                  dataType: "JSON",
                  success: function (result) {
                      var eventsList = [];
                      $(result).each(function (k,val) {
                        
                          eventsList.push(
                              {
                                  id: val.id,
                                  title: val.title,
                                  color: val.color,
                            
                                  start: val.start,
                                  url:  base_url + '/event-list/'+ val.id,
                                //  url: 'http://google.com/'
                                 // end: "2020-05-02"
                              });
                      });
                      if (callback)
                          callback(eventsList);
                  }
              });
          }
     
     
  });
});


$(document).ready(function() {
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  $('#calendar2').fullCalendar({
      header: {
          left: 'prev, next today',
          center: 'title',
          right: 'month, basicWeek, basicDay'
      },
      
      events: function (start, end, timezone, callback) {
              $.ajax({
                  url: base_url+"/calendar",
                  type: "GET",
                  dataType: "JSON",
                  success: function (result) {
                      var eventsList = [];
                      $(result).each(function (k,val) {
                        
                          eventsList.push(
                              {
                                  id: val.id,
                                  title: val.title,
                                  color: val.color,
                                  start: val.start,
                                  url:  base_url + '/event-list/'+ val.id,
                                 // end: "2020-05-02"
                              });
                      });
                      if (callback)
                          callback(eventsList);
                  }
              });
          }
     
     
  });
});


$(document).ready(function() {
  $(".selectSeller").click(function(){
      var favorite = [];
      $.each($("input[name='selectSeller']:checked"), function(){
          favorite.push($(this).val());
      });
      $('.plannerToken').val(JSON.stringify(favorite));
     
      if(favorite.length > 0){
        $('.sendMail').css('display','block')
      }else{
        $('.sendMail').css('display','none')
      }
   
      
  });
});


$(document).ready(function(){
   $('.sendMail').click(function(){
       var plannerToken =  $('.plannerToken').val();
       var questionnaireId =  $('.questionnaireId').val();
      
        $.ajax({ 
          type: 'POST',
          url:  base_url+"/send-mail",
         data: { plannerToken: plannerToken,questionnaireId:questionnaireId},
         dataType:'json',
         success: function(output) {
                if(output.status){
                  toastr.success('Customer Mail Send Successfully',{ timeOut: 9500 });
                  window.location.replace(base_url+"/event-list");
              }
            }
          });
   })
})


$(document).ready(function () {
  $('.owl-carousel').owlCarousel({
      loop: true,
      margin: 10,
      nav: false,
      items: 1,
      dots:false,
  })
})


 $(document).ready(function () {
  var token = $('#token').val();
  $('.Questionnairetab1').DataTable({

      "processing": true,
      "serverSide": true,
      "ajax":{
               "url": base_url+ "/questionnaire-data",
               "dataType": "json",
               "type": "POST",
                 "data":{ _token: token}
             },
      "columns": [
          { "data": "id" },
          { "data": "eventName" },
          { "data": "location" },
          { "data": "date" },
          { "data": "mobileNo" },
          { "data": "action" },
          { "data": "dots" }
      ]	 

  });
}); 

$(document).on('click','.tab',function(){
   var tab = $(this).data('id');
   var status = $(this).data('value');
   var token = $('#token').val();
   $('.tab').removeClass('tab-active')
   $(this).addClass('tab-active')
   $('.QuestionnaireData').css('display','none');
   $('#'+ tab + '').css('display','block');
   
   $('.Questionnaire'+ tab +'').DataTable({
        destroy: true,
       "processing": true,
       "serverSide": true,
       "ajax":{
                "url": base_url+ "/questionnaire-data",
                "dataType": "json",
                "type": "POST",
                  "data":{ _token: token,status:status}
              },
       "columns": [
           { "data": "id" },
           { "data": "eventName" },
           { "data": "location" },
           { "data": "date" },
           { "data": "mobileNo" },
           { "data": "action" },
           { "data": "dots" }
       ]	 
 
   });

})


$(document).ready(function(){  	
  $(".approved").click(function() {
      var id = $(this).data("id");
      var status = $(this).data("status");
      var taxt = $(this).data("taxt");
      var url = $('.url').val();
      var approved = $(this).parents('.move').find('.approv');
      var decline = $(this).parents('.move').find('.decline');
 const swalWithBootstrapButtons = Swal.mixin({
   customClass: {
      confirmButton: 'btn btn-primary',
     cancelButton: 'btn btn-danger'
   },
   buttonsStyling: false
 })
 
 swalWithBootstrapButtons.fire({
   title: 'Are you sure?',
   type: 'question',
   background:"#EFEAE6",
   showCancelButton: true,
   confirmButtonText: 'Yes, '+ taxt + ' It!',
   cancelButtonText: 'cancel!',
   reverseButtons: true
 }).then((result) => {
   if (result.value) {
         $.ajax({
         url: base_url + '/' + url ,
          type:'post',
          data : { id : id,status :status},
          dataType: 'json',
           success: function (respons) {
             swalWithBootstrapButtons.fire({
             title: taxt,
             type: 'success',
             background:"#EFEAE6",
             confirmButtonText: 'Ok',
             }).then(function() {
               console.log(respons);
                if(respons.status == 'approved'){
                  approved.css('display','none')
                  decline.css('display','inline-block')
                }else if(respons.status == 'decline'){
                  approved.css('display','inline-block')
                  decline.css('display','none')
                }
          });    
             },
         });  	
   } else if (
     /* Read more about handling dismissals below */
     result.dismiss === Swal.DismissReason.cancel
   ) {
             swalWithBootstrapButtons.fire({
   title: 'Cancelled!',
   type: 'error',
   background:"#EFEAE6",
   confirmButtonText: 'Ok',
  
 })
     
   }
 })
 
 });
   
 });  
 $(document).ready(function () {
  $('.dataTable').DataTable();
});


 $(document).ready(function () {
 $('.dashboardTab').click(function(){
     var tab = $(this).data('tab');
     $('.tabs').css('display','none');  
     $("#" + tab).css('display','inline-block');
 })
}); 



var page = 1;
$('.notificationScroll').scroll(function() {
  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
        page++;
        loadMoreData(page);
    }
});


function loadMoreData(page){
   
  $.ajax(
        {
           url: base_url + '/notification' +   '?page=' + page,
            type: "get",
            beforeSend: function()
            {
                $('.ajax-load').show();
            }
        })
        .done(function(data)
        {
          
          if(data.html == " "){
                $('.ajax-load').html("No more records found");
                return;
            }
            $('.ajax-load').hide();
            $(".notificationScroll").append(data.html);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              alert('server not responding...');
        });
}



var pages = 1;
$('.ongoingScroll').scroll(function() {
  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
        pages++;
        loadMoreDataOngoing(pages);
    }
});


function loadMoreDataOngoing(pages){
   
  $.ajax(
        {
           url: base_url + '/ongoingData' +   '?page=' + pages,
            type: "get",
            beforeSend: function()
            {
                $('.ajax-load').show();
            }
        })
        .done(function(data)
        {
          
          if(data.ongoingHtml == " "){
                $('.ajax-load').html("No more records found");
                return;
            }
            $('.ajax-load').hide();
            $(".ongoingScroll").append(data.ongoingHtml);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              alert('server not responding...');
        });
}

var messagespages = 1;
$('.messagesScroll').scroll(function() {
  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
    messagespages++;
    loadMoreDataMessages(messagespages);
    }
});


function loadMoreDataMessages(messagespages){
  
  $.ajax(
        {
           url: base_url + '/messagesdata' +   '?page=' + messagespages,
            type: "get",
            beforeSend: function()
            {
                $('.ajax-load').show();
            }
        })
        .done(function(data)
        {
        
          if(data.messagesHtml == " "){
                $('.ajax-load').html("No more records found");
                return;
            }
            $('.ajax-load').hide();
            $(".messages_block").append(data.messagesHtml);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              alert('server not responding...');
        });
}

$(document).on('click','.fullPermission',function(){
  if($(this).is(":checked")){
    $('.staffPermission').css('display','none');
  }else{
    $('.staffPermission').css('display','inline-block');
  }
})


$(document).on('click','.milestomePayment',function(){
    var $id = $(this).data('id');
    var approved = $(this).parents('.milestomeapproved');
    var approved2 = $(this).parents('.milestomeapproved').find('.status_check');
    $.ajax({ 
      type: 'POST',
      url:  base_url+"/milestone-payment",
     data: { id: $id},
     dataType:'json',
     success: function(output) {
            if(output.status){
              approved.addClass('active');
              approved2.addClass('active');
              $('.milestomePayment').hide();
              $('.status').html('');
              $('.status').html('Completed');

              toastr.success(output.message,{ timeOut: 9500 });
               
          }else{
            toastr.error(output.message,{ timeOut: 9500 });
          }
        }
      });

})



var files = [];
var i = 0;
$("#LoadingImage").hide();
$(document).on('change','#blogFile',function(){
           var filesUploaded = $('#blogFile').prop('files');
           $.each(filesUploaded, function( key, value ) {
               var file_data = value;   
               var form_data = new FormData();                  
               form_data.append('file', file_data);
               $("#LoadingImage").show();
               $.ajax({
                   url:base_url + '/blog-image-upload' ,
                   type: "POST",
                   data:  form_data,
                   contentType: false,
                   cache: false,
                   processData:false,
                   dataType:'json',
                   success:function(response){
                       $('.image_name_reset').html('')
                      /// $('.image_name_reset').html('Choose file')
                       var obj = JSON.parse(JSON.stringify(response));
                       if(obj.status){
                        $("#LoadingImage").hide();
                           toastr.success(obj.msg);
                           $('.preview_box').append(obj.html);
                           if(response.filename.length>0){
                           $.each(response.filename,function(index,value){
                           files.push(value);
                           });
                           $("input[name='blogs_files']").val(JSON.stringify(files));
                       }
                       }else{
                           toastr.error(obj.msg);
                           $("#LoadingImage").hide();
                       }
  
                   }
               });
           });
       });

   $(document.body).on('click','.remove_images',function(){ 
     var filesUploaded = $("input[name='blogs_files']").val();
      var remove_val = $(this).data('value');
      var rem =  $(this).parents('.move');
      var countries = {};
      countries.results = files;
      countries.results.removeValue('name', remove_val);
           $.ajax({
               url:base_url + '/file-delete' ,
               type: "POST",
               data: { file : remove_val},
               success:function(response){
                   $("input[name='blogs_files']").val(JSON.stringify(files));
                   rem.remove()
               }
           });         
    })


  $(document).ready(function(){  	
      $(".refund").click(function() {
          var id = $(this).data("id");
        var move = $(this);
        var Refunded = $(this).parents('.Refunded');
     const swalWithBootstrapButtons = Swal.mixin({
       customClass: {
          confirmButton: 'btn btn-primary',
         cancelButton: 'btn btn-danger'
       },
       buttonsStyling: false
     })
     
     swalWithBootstrapButtons.fire({
       title: 'Are you sure?',
       type: 'question',
       background:"#EFEAE6",
       showCancelButton: true,
       confirmButtonText: 'Yes, Refunded it!',
       cancelButtonText: 'cancel!',
       reverseButtons: true
     }).then((result) => {
       if (result.value) {
             $.ajax({
             url: 'refund',
             type: "POST",
             dataType:"json",
             data: { id : id},
               success: function (response ) {
                var obj = JSON.parse(JSON.stringify(response));
                if(obj.status){
                  move.remove()
                  Refunded.html('Refunded Success');
                  swalWithBootstrapButtons.fire({
                    title: 'Refunded!',
                    type: 'success',
                    background:"#EFEAE6",
                    confirmButtonText: 'Ok',
                    })  
                }else{
                  toastr.error(obj.msg);
                }
                
                 },
             });  	
      
       } else if (
         /* Read more about handling dismissals below */
         result.dismiss === Swal.DismissReason.cancel
       ) {
                 swalWithBootstrapButtons.fire({
       title: 'Cancelled!',
       type: 'error',
       background:"#EFEAE6",
       confirmButtonText: 'Ok',
      
     })
         
       }
     })
     
     });
       
     });  

     $(document).ready(function(){

      $("#txt_search").keyup(function(){
          var search = $(this).val();
  
          if(search != ""){
  
              $.ajax({
                url: 'get-event-list',
                  type: 'post',
                  data: {search:search},
                  dataType: 'json',
                  success:function(response){
                      var len = response.length;
                      $('#searchResult').css('display','inline-block')
                      $("#searchResult").empty();
                      for( var i = 0; i<len; i++){
                          var id = response[i]['tokenId'];
                          var eventName = response[i]['eventName'];
                          $("#searchResult").append("<li data-id='"+id+"' value='"+id+"'>"+eventName+"</li>");
  
                      }
  
                      // binding click event to li
                      $("#searchResult li").bind("click",function(){
                      
                          $('#txt_search').val('');
                          $('#eventId').val('');
                          $('#txt_search').val($(this).html());
                          $('#eventId').val($(this).data('id'));
                          $('#searchResult').hide();
                      });
  
                  }
              });
          }
  
      });
  
  });
  
  $(document).on('click','.reset',function(){
  
    window.location.replace(base_url + "/history");
  })
     
     