<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Table-Pop</title>
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/resources/vendors/select2/css/select2.min.css">
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/resources/vendors/sweetalert2/sweetalert2.min.css">
        <link href="{{ asset('resources/assets') }}/resources/vendors/select2/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/resources/vendors/toastr/toastr.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('resources/assets') }}/resources/vendors/datatables/datatables.min.css"/>
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/demo/css/fullcalendar.css">
        <link href="{{ asset('resources/assets') }}/resources/vendors/owl/owl.carousel.min.css" type="text/css" rel="stylesheet" />
        <link href="{{ asset('resources/assets') }}/resources/vendors/owl/owl.theme.min.css" type="text/css" rel="stylesheet" />
        <link href="{{ asset('resources/assets') }}/resources/vendors/owl/owl.transitions.min.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/demo/css/style.css">
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/demo/css/message.css">
        <link rel="stylesheet" href="{{ asset('resources/assets') }}/demo/css/event.css">
<style>
.intl-tel-input{
    width: 100%;
}
</style>
</head>
<body>
    @include('common.header.header')
   {{--   @include('common.sidebar.sidebar')  --}}
   
  
      <!--**********************************
            Content body start
        ***********************************-->
    @yield('content')
      <!--**********************************
            Content body end
        ***********************************-->
    @include('common.footer.footer')
     
    
</body>

</html>