<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('resources/assets') }}/demo/css/style.css">
<style>
   .error{
    color:red;
    text-transform: capitalize;
} 
</style>

</head>

    <body>

      <!--**********************************
            Content body start
        ***********************************-->
    @yield('default')
      <!--**********************************
            Content body end
        ***********************************-->
    @include('common.footer.guest')
     
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
</body>

<!-- Mirrored from byrushan.com/projects/super-admin/app/2.2/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 20 Sep 2019 12:30:24 GMT -->
</html>