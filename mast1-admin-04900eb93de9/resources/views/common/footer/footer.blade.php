<script>
   var base_url  = "{{ url('/') }}";
   var socketUrl  = "{{ env('SOCKET_URL') }}";
</script>

<script src="{{ asset('resources/assets') }}/resources/vendors/jquery/jquery.min.js"></script>
<script src="{{ asset('resources/assets') }}/resources/vendors/popper.js/popper.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
  $('.select2').select2();
});
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/css/intlTelInput.css" rel="stylesheet" />
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/intlTelInput.js"></script>
<script>

$("#phone").intlTelInput({
  initialCountry: "us",
  separateDialCode: true,
  preferredCountries: ["fr", "us", "gb"],
  geoIpLookup: function(callback) {
    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });
  },
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js"
});
dialCode = '';
var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
$(document).on('change','#phone',function(){
  $('.phoneNo').val('');
  
  var dialCode =  $("#phone").intlTelInput("getSelectedCountryData").dialCode;
 var phoneNo = '+' + dialCode + $("#phone").val();      
   
  
    if($("#phone").intlTelInput("isValidNumber")==true){  
      $('#error-msg').html('');
      $('.phoneNo').val(phoneNo);
    }
  else{
    var errorCode =  $("#phone").intlTelInput("getValidationError");
    errorMsg = errorMap[errorCode];
    
    $('#error-msg').html(errorMsg);
  }


})
$(document).on('click','.country-list',function(){
  var dialCode = $('.selected-dial-code').html();
  var phoneNo =   dialCode + $("#phone").val();
  $('.phoneNo').val('');
  if($("#phone").intlTelInput("isValidNumber")==true){    
    $('#error-msg').html('');
  $('.phoneNo').val(phoneNo);
  }else{
    var errorCode =  $("#phone").intlTelInput("getValidationError");
    errorMsg = errorMap[errorCode];
    
    $('#error-msg').html(errorMsg);
  }
})
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('resources/assets') }}/resources/vendors/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{{ asset('resources/js') }}/owl.carousel.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
<script src="{{ asset('resources/js') }}/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
@if($activePage  == 'dashboard')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"> </script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script src="{{ asset('resources/js') }}/chart.js"></script>
@endif 
<script src="{{ asset('resources/js') }}/moment.min.js"></script>
<script src="{{ asset('resources/js') }}/fullcalendar.js"></script>
@if($activePage == "message")
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
<script src="{{ asset('resources/js') }}/message.js"></script> 
@endif
<script src="{{ asset('resources/js') }}/common.js"></script>
@if($activePage == "blogs" || $activePage == "faq")
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>


<script>
    CKEDITOR.replace( 'editor1' );
</script>
@endif
<script>
    @if(Session::has('message'))
      var type = "{{ Session::get('alert-type', 'info') }}";
      switch(type){
          case 'info':
              toastr.info("{{ Session::get('message') }}");
              break;
          
          case 'warning':
              toastr.warning("{{ Session::get('message') }}");
              break;
  
          case 'success':
              toastr.success("{{ Session::get('message') }}");
              break;
  
          case 'error':
              toastr.error("{{ Session::get('message') }}");
              break;
      }
    @endif
  </script>
 @if($activePage == "planner" || $activePage == "questionnaire" || $activePage == "vendors")
<script>
    function initMap() {
        var input = document.getElementById('autocomplete');
      
        var autocomplete = new google.maps.places.Autocomplete(input);
       
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
          //  document.getElementById('location-snap').innerHTML = place.formatted_address;
           // document.getElementById('lat-span').innerHTML = place.geometry.location.lat();
           // document.getElementById('lon-span').innerHTML = place.geometry.location.lng();
        });
    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
    @endif    
@if ($activePage == 'history')
    

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
      $(function() {
      
          var start = moment().subtract(29, 'days');
          var end = moment();
      
          function cb(start, end) {
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
              $('#reportrange .start').val(start.format('MMMM D, YYYY'));
              $('#reportrange .end').val(end.format('MMMM D, YYYY'));
          }
      
          $('#reportrange').daterangepicker({
              startDate: start,
              endDate: end,
              ranges: {
                 'Today': [moment(), moment()],
                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                 'This Month': [moment().startOf('month'), moment().endOf('month')],
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              }
          }, cb);
      
          cb(start, end);
      
      });
      </script>
@endif
