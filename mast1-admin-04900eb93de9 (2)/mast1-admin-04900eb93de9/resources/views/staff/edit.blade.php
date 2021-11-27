@extends('layouts.app',['class' => 'staff', 'activePage' => 'staff'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Edit</h4>
            <form method="post" action="{{ url('staff-update') }}/{{ $data['id'] ?? '' }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name',$data['name'] ?? '') }}">
                    @if ($errors->has('name'))
                    <span class="error"role="alert">
                        {{ $errors->first('name') }}.
                    </span>
                @endif
                </div>
               
                <div class="form-group">
                            <input type="email" name="email"  class="form-control" placeholder="Email Address" value="{{ old('email',$data['email'] ?? '') }}">
                            @if ($errors->has('email'))
                                <span class="error"role="alert">
                                    {{ $errors->first('email') }}.
                                </span>
                            @endif
                </div>
               
                
                    <div class="form-group">
                     <input class="form-control" placeholder="Mobile Number" name="mobile" id="phone" type="tel" value="{{ old('mobile',$data['mobile'] ?? '') }}">
                     <span id="error-msg" class="error"></span>
                     <input type="hidden" name="phoneNo" class="phoneNo" value="{{ old('mobile',$data['mobile'] ?? '') }}">  
                          @if ($errors->has('phoneNo'))
                          <span class="error"role="alert">
                            {{ $errors->first('phoneNo') }}.
                          </span>
                      @endif
                        </div>
                   
                        <div class="form-row">
                            <div class="col-md-12">
                               <h5>Permission</h5>
                               <ul><li><input type="checkbox" name="fullPermission" @if($data['adminType'] == 2 && $data['type'] == 1) checked  @endif  class="fullPermission" value="fullPermission">
                                 <span>Full Admin Permission</span></li>
                             </ul>
                               @php
                               $permission =  !empty($data['permission']) ? json_decode($data['permission'],true) : array() ;
                               $customerArray = !empty($permission['customer']) ? $permission['customer'] : array() ;
                               $VendorArray = !empty($permission['vendor']) ? $permission['vendor'] : array() ;
                               $eventType = !empty($permission['eventtypes']) ? $permission['eventtypes'] : array() ;
                               $themes = !empty($permission['themes']) ? $permission['themes'] : array() ;
                               $eventList = !empty($permission['eventList']) ? $permission['eventList'] : array() ;
                               $calendar = !empty($permission['calendar']) ? $permission['calendar'] : array() ;
                               $planner = !empty($permission['planner']) ? $permission['planner'] : array() ;
                               $blog = !empty($permission['blog']) ? $permission['blog'] : array() ;
                               $faq = !empty($permission['faq']) ? $permission['faq'] : array() ;
                            @endphp 
                            </div>
                         </div>
                         <div class="staffPermission"  @if($data['adminType'] == 2 && $data['type'] == 1) style="display:none ;"  @endif>
                         <div class="form-row">
                             <div style="float: left;width:20%">
                                <h5>Customers</h5>
                                <ul><li><input type="checkbox"  @if(in_array('index',$customerArray)) checked  @endif name="customers[]" value="index" >
                                        <span>Read</span></li>
                                 <li><input type="checkbox"  @if(in_array('add',$customerArray)) checked  @endif name="customers[]"  value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox"  @if(in_array('edit',$customerArray)) checked  @endif name="customers[]"  value="edit">
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox"  @if(in_array('delete',$customerArray)) checked  @endif name="customers[]"  value="delete">
                                    <span>Delete</span></li>
                                </ul>
                            </div>
                             <div style="float: left;width:20%">
                                <h5>Vendor</h5>
                                <ul><li><input type="checkbox"  @if(in_array('index',$VendorArray)) checked  @endif name="vendor[]" value="index" >
                                        <span >Read</span></li>
                                 <li><input type="checkbox"  @if(in_array('profile',$VendorArray)) checked  @endif name="vendor[]" value="profile" >
                                    <span>Profile</span></li>
                                 <li><input type="checkbox"  @if(in_array('add',$VendorArray)) checked  @endif name="vendor[]" value="add" >
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox"  @if(in_array('edit',$VendorArray)) checked  @endif name="vendor[]"  value="edit" >
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox"  @if(in_array('delete',$VendorArray)) checked  @endif name="vendor[]"  value="delete" >
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                                <h5>Event-Type</h5>
                                <ul><li><input type="checkbox"  name="eventtypes[]" value="index"  @if(in_array('index',$eventType)) checked  @endif>
                                        <span >Read</span></li>
                                 <li><input type="checkbox" name="eventtypes[]" value="add"  @if(in_array('add',$eventType)) checked  @endif>
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="eventtypes[]"  value="edit"  @if(in_array('edit',$eventType)) checked  @endif>
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="eventtypes[]"  value="delete"  @if(in_array('delete',$eventType)) checked  @endif>
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                                <h5>Themes</h5>
                                <ul><li><input type="checkbox" name="themes[]" value="index"  @if(in_array('index',$themes)) checked  @endif>
                                        <span >Read</span></li>
                                 <li><input type="checkbox" name="themes[]" value="add"  @if(in_array('add',$themes)) checked  @endif>
                                    <span>Add</span></li>
                                 <li>     <input type="checkbox" name="themes[]"  value="edit"  @if(in_array('edit',$themes)) checked  @endif>
                                    <span>Edit</span></li>
                                 <li>             <input type="checkbox" name="themes[]"  value="delete"  @if(in_array('delete',$themes)) checked  @endif>
                                    <span>Delete</span></li>
                                </ul>
                             </div>
                             <div style="float: left;width:20%">
                              <h5>New Event List</h5>
                              <ul><li><input type="checkbox" name="eventList[]" value="index"  @if(in_array('index',$eventList)) checked  @endif>
                                      <span >Read</span></li>
                               <li><input type="checkbox" name="eventList[]" value="details"  @if(in_array('details',$eventList)) checked  @endif>
                                  <span>Details</span></li>
                               </ul>
                           </div>
                           
                          
                            <div style="clear:both"></div>
                        </div>
                        <div class="form-row">
                           
                         <div style="float: left;width:20%">
                            <h5>Full-Calendar</h5>
                            <ul><li><input type="checkbox" name="calendar[]" value="index"  @if(in_array('index',$calendar)) checked  @endif>
                                    <span >Read</span></li>
                           
                         </div>
                         <div style="float: left;width:20%">
                           <h5>Planner</h5>
                           <ul><li><input type="checkbox" name="planner[]" value="index"  @if(in_array('index',$planner)) checked  @endif>
                                   <span >Read</span></li>
                            <li><input type="checkbox" name="planner[]" value="profile"  @if(in_array('profile',$planner)) checked  @endif>
                               <span>Profile</span></li>
                            <li><input type="checkbox" name="planner[]" value="add"  @if(in_array('add',$planner)) checked  @endif>
                               <span>Add</span></li>
                            <li>     <input type="checkbox" name="planner[]"  value="edit"  @if(in_array('edit',$planner)) checked  @endif>
                               <span>Edit</span></li>
                            <li>             <input type="checkbox" name="planner[]"  value="delete"  @if(in_array('delete',$planner)) checked  @endif>
                               <span>Delete</span></li>
                           </ul>
                        </div>
                        <div style="float: left;width:20%">
                           <h5>Blog</h5>
                           <ul><li><input type="checkbox" name="blog[]" value="index"  @if(in_array('index',$blog)) checked  @endif>
                                   <span >Read</span></li>
                            <li><input type="checkbox" name="blog[]" value="add"  @if(in_array('add',$blog)) checked  @endif>
                               <span>Add</span></li>
                            <li>     <input type="checkbox" name="blog[]"  value="edit"  @if(in_array('edit',$blog)) checked  @endif>
                               <span>Edit</span></li>
                            <li>             <input type="checkbox" name="blog[]"  value="delete"  @if(in_array('delete',$blog)) checked  @endif>
                               <span>Delete</span></li>
                           </ul>
                        </div>
                        <div style="float: left;width:20%">
                           <h5>Faq</h5>
                           <ul><li><input type="checkbox" name="faq[]" value="index"  @if(in_array('index',$faq)) checked  @endif>
                                   <span >Read</span></li>
                            <li><input type="checkbox" name="faq[]" value="add"  @if(in_array('add',$faq)) checked  @endif>
                               <span>Add</span></li>
                            <li>     <input type="checkbox" name="faq[]"  value="edit"  @if(in_array('edit',$faq)) checked  @endif>
                               <span>Edit</span></li>
                            <li>             <input type="checkbox" name="faq[]"  value="delete"  @if(in_array('delete',$faq)) checked  @endif>
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