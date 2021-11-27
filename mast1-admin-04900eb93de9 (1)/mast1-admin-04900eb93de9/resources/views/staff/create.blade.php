@extends('layouts.app',['class' => 'staff', 'activePage' => 'staff'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('create-staff') }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                    <span class="error"role="alert">
                        {{ $errors->first('name') }}.
                    </span>
                @endif
                </div>
               
                <div class="form-group">
                            <input type="email" name="email"  class="form-control" placeholder="Email Address" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="error"role="alert">
                                    {{ $errors->first('email') }}.
                                </span>
                            @endif
                </div>
               
                
                    <div class="form-group">
                     <input class="form-control" placeholder="Mobile Number" name="mobile" id="phone" type="tel" value="{{ old('mobile') }}">
                     <span id="error-msg" class="error"></span>
                     <input type="hidden" name="phoneNo" class="phoneNo" value="{{ old('mobile') }}">  
                          @if ($errors->has('phoneNo'))
                          <span class="error"role="alert">
                            {{ $errors->first('phoneNo') }}.
                          </span>
                      @endif
                      
                        </div>
                   
                        <div class="form-row">
                            <div class="col-md-12">
                               <h5>Permission</h5>
                               <ul><li><input type="checkbox" name="fullPermission" @if(old('fullPermission')) checked  @endif  class="fullPermission" value="fullPermission">
                                 <span>Full Admin Permission</span></li>
                             </ul>
                            </div>
                         </div>
                         <div class="staffPermission"  @if(old('fullPermission')) style="display:none ;"  @endif>
                         <div class="form-row">
                             <div style="float: left;width:20%">
                                <h5>Customers</h5>
                                <ul><li><input type="checkbox" name="customers[]" value="index" >
                                        <span>Read</span></li>
                                 <li><input type="checkbox" name="customers[]"  value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="customers[]"  value="edit">
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="customers[]"  value="delete">
                                    <span>Delete</span></li>
                                    
                       
                                </ul>
                            </div>
                             <div style="float: left;width:20%">
                                <h5>Vendor</h5>
                                <ul><li><input type="checkbox" name="vendor[]" value="index" >
                                        <span >Read</span></li>
                                 <li><input type="checkbox" name="vendor[]" value="profile" >
                                    <span>Profile</span></li>
                                 <li><input type="checkbox" name="vendor[]" value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="vendor[]"  value="edit" >
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="vendor[]"  value="delete" >
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                                <h5>Event-Type</h5>
                                <ul><li><input type="checkbox" name="eventtypes[]" value="index" >
                                        <span >Read</span></li>
                                 <li><input type="checkbox" name="eventtypes[]" value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="eventtypes[]"  value="edit" >
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="eventtypes[]"  value="delete" >
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                                <h5>Themes</h5>
                                <ul><li><input type="checkbox" name="themes[]" value="index" >
                                        <span >Read</span></li>
                                 <li><input type="checkbox" name="themes[]" value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="themes[]"  value="edit" >
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="themes[]"  value="delete" >
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                              <h5>New Event List</h5>
                              <ul><li><input type="checkbox" name="eventList[]" value="index" >
                                      <span >Read</span></li>
                               <li><input type="checkbox" name="eventList[]" value="details" >
                                  <span>Details</span></li>
                               </ul>
                           </div>
                             
                            <div style="clear:both"></div>
                        </div>
                        <div class="form-row">
                           
                           <div style="float: left;width:20%">
                              <h5>Full-Calendar</h5>
                              <ul><li><input type="checkbox" name="calendar[]" value="index"  >
                                      <span >Read</span></li>
                             
                           </div>
                           <div style="float: left;width:20%">
                              <h5>Planner</h5>
                              <ul><li><input type="checkbox" name="planner[]" value="index" >
                                      <span >Read</span></li>
                               <li><input type="checkbox" name="planner[]" value="profile" >
                                  <span>Profile</span></li>
                               <li><input type="checkbox" name="planner[]" value="add" >
                                  <span>Add</span></li>
                               <li>     <input type="checkbox" name="planner[]"  value="edit" >
                                  <span>Edit</span></li>
                               <li>             <input type="checkbox" name="planner[]"  value="delete" >
                                  <span>Delete</span></li>
                              </ul>
                           </div>
                           <div style="float: left;width:20%">
                              <h5>Blog</h5>
                              <ul>
                                 <li><input type="checkbox" name="blog[]" value="index" >
                                      <span >Read</span></li>
                               
                               <li><input type="checkbox" name="blog[]" value="add" >
                                  <span>Add</span></li>
                               <li>     <input type="checkbox" name="blog[]"  value="edit" >
                                  <span>Edit</span></li>
                               <li>             <input type="checkbox" name="blog[]"  value="delete" >
                                  <span>Delete</span></li>
                              </ul>
                           </div>
                           <div style="float: left;width:20%">
                              <h5>Faq</h5>
                              <ul>
                                 <li><input type="checkbox" name="faq[]" value="index" >
                                      <span >Read</span></li>
                               
                               <li><input type="checkbox" name="faq[]" value="add" >
                                  <span>Add</span></li>
                               <li>     <input type="checkbox" name="faq[]"  value="edit" >
                                  <span>Edit</span></li>
                               <li>             <input type="checkbox" name="faq[]"  value="delete" >
                                  <span>Delete</span></li>
                              </ul>
                           </div>
                            <div style="clear:both"></div>
                        </div>
                     </div>
                   
                   
                  
                <div class="form-group">
                    <div class="custom-control custom-checkbox mb-2">
                       
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
					</div>
			</div>
</section>
@endsection