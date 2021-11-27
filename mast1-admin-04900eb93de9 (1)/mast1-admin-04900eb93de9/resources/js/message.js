function removeValue(arr) {
	var what, a = arguments, L = a.length, ax;
	while (L > 1 && arr.length) {
		what = a[--L];
		while ((ax= arr.indexOf(what)) !== -1) {
			arr.splice(ax, 1);
		}
	}
	return arr;
}

function currentTime() {
  var date = new Date;
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; 
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return strTime;
}
var socket = io.connect(socketUrl);
var chathtml = $(".messageLists");
$(document).ready(function(){
  	
})



$(document).on('click','.userContact',function(){
    var id = $(this).data('id');
    var user = $(this).data('value');
    var typeData = $(this).data('type');
     var contactName =	 $(this).children().find('.contactName').html();
     console.log(contactName);
      $('.chatName').html(contactName);
    
	 $(this).children().find('.count').html('');

    $('.messageChat').css('display','inline-block');
    $('.user_chat_box_inner').removeClass('active');
    $(this).children('.user_chat_box_inner').addClass('active');

    $('.sendid').val(id);
    
    $.ajax({
      url: base_url + '/message-list',
      data :{ id: id,user:user,typeData:typeData },
      type: 'POST',
      dataType: 'json',
        success: function (respons) {
       //   console.log(respons.html);
          $('.messageLists').html('');
          if(respons.html != ''){
            $('.messageLists').append(respons.html);
            var scr = $('.messageLists')[0].scrollHeight;
            $('.messageLists').animate({scrollTop: scr},2000)
          }else{
            $('.messageLists').html('');
          }
        
          
        }
      });  	
})


socket.on("new_group", (data) => {  
        if(data != ''){
             if(data.type == 1) {
               if(data.customerId != '') { 
                  var usertype =  'Customer';
              }else{ 
                var usertype ='Seller';
              } 
            }else{
              var usertype ='';
            }
         //   console.log(usertype);
              var groupList = '<div class="user_chat_box userContact" data-id="'+ data.groupId +'" data-value="'+ data.id +'" > <span class="date"></span> <div class="user_chat_box_inner"><div class="thumbnail" style="background: url('+ data.profileImage  +');"></div><div class="chat_sidebox"><h4>'+ data.name +'<span></span></h4><p class="lastMessage"></p><p class="">'+ usertype +'</p><p></p><div class="chat_control"> <div class="chat_control_inner"> <label  class="count"></label> <span></span><span></span><span></span></div> </div></div></div></div>';
              $('.chat_sidebar').append(groupList);
            }
});
$(document).ready(function(){
  var userId = $('.userId').val(); 
  $('.user_chat_box').eq(0).children('.user_chat_box_inner').addClass('active');
  var user = $('.active').parents('.userContact').data('value');
  var typeData = $('.active').parents('.userContact').data('type');
  var id = $('.active').parent('.user_chat_box ').data('id');
  $(this).children().find('.count').html('');

  $('.sendid').val(id);

  var contactName =	$('.active').children().find('.contactName').html();
  $('.chatName').html(contactName);
  $.ajax({
    url: base_url + '/message-list',
    data :{ id: id,user:user,typeData:typeData },
    type: 'POST',
    dataType: 'json',
      success: function (respons) {
      
        $('.messageLists').html('');
          $('.messageLists').append(respons.html);
          var scr = $('.messageLists')[0].scrollHeight;
          $('.messageLists').animate({scrollTop: scr},2000)
      }
    }); 
    var obj = {  uid: userId, type: 'admin'};
    socket.emit("join", obj);
	  socket.on("new_message", (message) => {  
   // console.log(message);
		if(message.groupId == $('.active').parents('.user_chat_box').data('id')){
			var html = '<div class="message-box you"><div class="thumbnail" style="background:url('+ message.customerImage +')"> </div><span class="messageSendName" style="font-size:10px"> '+ message.name +' </sapn> <div class="message_content"><div class="message-text"><p> '+ message.message +' </p> </div><span class="date">'+ message.date +'</span></div></div>';
			$('.messageLists').append(html);
		}else{
			var count = $('.user_chat_box[data-id="'+ message.groupId +'"]').children().find('.count').html();
			count = count != '' ? parseInt(count) : 0;
			$('.user_chat_box[data-id="'+ message.groupId +'"]').children().find('.count').html((count+1));
			$('.user_chat_box[data-id="'+ message.groupId +'"]').children().find('.lastMessage').html(message.message);
		}
	})
  })

$(document).on('click','.sendMessage',function(){
  var id = $('.sendid').val();
  var fileName = $('.fileName').val();
  var messageBox = $('.messageBox').val();
  var userId = $('.userId').val();
  var user = $('.active').parents('.userContact').data('value');
  var typeData = $('.active').parents('.userContact').data('type');

    if(messageBox != '' || fileName != ''){
      $.ajax({
        url: base_url + '/message-send',
        data :{ id: id,text:messageBox,msgFile:fileName ,user:user,userType:typeData},
        type: 'POST',
        dataType: 'json',
          success: function (respons) {
          
            $('.active').children().find('.lastMessage').html('')
            $('.active').children().find('.lastMessage').append(messageBox)
            $('.active').children().find('.currentTime').html('')
            $('.active').children().find('.currentTime').append(currentTime())
            $('.messageLists').append(respons.data);
            $('.messageBox').val('');
            $('.fileName').val('');
            $('.messageImageStatus').html('');
            socket.emit('new_message', { messageId : respons.messageId });
         //   $('.active').parents('.userContact').prepend($('.user_chat_box').eq(0).before());  
         
          }
        });  	
      }

 
})


var files = [];
var i = 0;
$(document).on('change','#file-input',function(){
           $(".ajaxloader").css('display','flex');
           var filesUploaded = $('#file-input').prop('files');
           $.each(filesUploaded, function( key, value ) {
               var file_data = value;   
               var form_data = new FormData();                  
               form_data.append('file', file_data);
               $("#LoadingImage").show();
               $.ajax({
                   url: base_url + '/message-image-upload',
                   type: "POST",
                   data:  form_data,
                   contentType: false,
                   cache: false,
                   processData:false,
                   dataType:'json',
                   success:function(response){
                       $('.image_name_reset').html('')
                       $("#LoadingImage").hide();
                       var obj = JSON.parse(JSON.stringify(response));
                       if(obj.status){
                           
                            $('.fileName').val(obj.fileName);
                            $('.messageImageStatus').append(obj.html)
                           if(response.filename.length>0){
                           $.each(response.filename,function(index,value){
                           files.push(value);
                           });
                           $(".fileName").val(JSON.stringify(files));
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
        var filesUploaded = $(".fileName").val();
         var remove_val = $(this).data('value');
          removeValue(files,remove_val)
            $.ajax({
              url:base_url + '/remove-image' ,
              type: "POST",
              data: { name : remove_val},
              success:function(response){
                $(".fileName").val(JSON.stringify(files));
                $(this).parents('.move').remove()
              }
          });   
       })



