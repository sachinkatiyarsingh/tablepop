
    <header>
        <div class="container">
            <div class="header">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('resources/assets') }}/demo/images/logo.png" alt="" />
                    </a>
                </div>

                <div class="menus">
                    <ul>
    {{--                     <li class="{{ $activePage == "notification" ? 'active' : '' }}"><a href="{{ url('notification') }}">Notification</a></li> --}}
                    @if(Session::get('type') == 1)
                        <li class="{{ $activePage == "vendors" ? 'active' : '' }}"><a href="{{ url('vendors') }}">Vendor list</a></li>
                        <li class="{{ $activePage == "planners" ? 'active' : '' }}"><a href="{{ url('planners') }}">Planners list</a></li>
                        <li class="{{ $activePage == "customers" ? 'active' : '' }}"><a href="{{ url('customers') }}">Customer list</a></li>
                        <li class="{{ $activePage == "staff" ? 'active' : '' }}"><a href="{{ url('staff') }}">Staff</a></li>
                        <li class="{{ $activePage == "event-types" ? 'active' : '' }}"><a href="{{ url('event-types') }}">Event-Types</a></li>
                        <li class="{{ $activePage == "themes" ? 'active' : '' }}"><a href="{{ url('themes') }}">Themes</a></li>
                        <li class="{{ $activePage == "event-list" ? 'active' : '' }}"><a href="{{ url('event-list') }}">Event list</a></li>      
                        <li class="{{ $activePage == "blogs" ? 'active' : '' }}"><a href="{{ url('blogs') }}">Blogs</a></li>      
                        <li class="{{ $activePage == "faq" ? 'active' : '' }}"><a href="{{ url('faq') }}">Faq</a></li>      
                        <li><a href="{{ url('/calendar') }}">Calendar</a></li>
                        
                    @endif 
                    @php
                        $access = Helper::permission();
                        $Customer = !empty($access['customer']) ? $access['customer'] :  array();
                        $Vendor = !empty($access['vendor']) ? $access['vendor'] :  array();
                        $eventType = !empty($access['eventtypes']) ? $access['eventtypes'] : array() ;
                        $themes = !empty($access['themes']) ? $access['themes'] : array() ;
                        $eventList = !empty($access['eventList']) ? $access['eventList'] : array() ;
                        $calendar = !empty($access['calendar']) ? $access['calendar'] : array() ;
                        $planner = !empty($access['planner']) ? $access['planner'] : array() ;
                        $blog = !empty($access['blog']) ? $access['blog'] : array() ;
                        $faq = !empty($access['faq']) ? $access['faq'] : array() ;
                    @endphp  
                    @if (!empty($Vendor))
                        @if(in_array('index',$Vendor))
                        <li class="{{ $activePage == "vendors" ? 'active' : '' }}"><a href="{{ url('vendors') }}">Vendor list</a></li>
                        @endif
                   @endif
                    @if (!empty($planner))
                        @if(in_array('index',$planner))
                        <li class="{{ $activePage == "planners" ? 'active' : '' }}"><a href="{{ url('planners') }}">Planners list</a></li>
                        @endif
                   @endif
                   @if(!empty($Customer))
                        @if(in_array('index',$Customer))
                            <li class="{{ $activePage == "customers" ? 'active' : '' }}"><a href="{{ url('customers') }}">Customer list</a></li>
                        @endif
                    @endif
                   @if(!empty($eventType))
                        @if(in_array('index',$eventType))
                        <li class="{{ $activePage == "event-types" ? 'active' : '' }}"><a href="{{ url('event-types') }}">Event-Types</a></li>
                         @endif
                    @endif
                   @if(!empty($themes))
                        @if(in_array('index',$themes))
                        <li class="{{ $activePage == "themes" ? 'active' : '' }}"><a href="{{ url('themes') }}">Themes</a></li>
                         @endif
                    @endif
                    @if(!empty($eventList))
                     @if(in_array('index',$eventList))
                        <li class="{{ $activePage == "event-list" ? 'active' : '' }}"><a href="{{ url('event-list') }}">Event list</a></li>      
                    @endif
                    @endif
                    @if(!empty($calendar))
                     @if(in_array('index',$calendar))
                     <li><a href="{{ url('/calendar') }}">Calendar</a></li>
                    @endif
                    @endif
                    @if (!empty($blog))
                    @if(in_array('index',$blog))
                    <li class="{{ $activePage == "blog" ? 'active' : '' }}"><a href="{{ url('blogs') }}">Blog</a></li>
                    @endif
               @endif
               @if (!empty($faq))
                    @if(in_array('index',$faq))
                    <li class="{{ $activePage == "faq" ? 'active' : '' }}"><a href="{{ url('faq') }}">Faq</a></li>
                    @endif
               @endif
                {{--     <li><a href="{{ url('/message') }}">Message</a></li> --}}
                 <li class="{{ $activePage == "history" ? 'active' : '' }}"><a href="{{  url('history') }}">View history</a></li>
                 <li class="{{ $activePage == "contactus" ? 'active' : '' }}"><a href="{{  url('contactus') }}">Contactus</a></li>
                        <!--li><a href="#">Ongoing events</a></li--->
  
                        <li><a href="{{ url('/message') }}">
                                <div class="icon message"><span></span></div>
                            </a></li>
                        <li><a href="{{ url('notification') }}">
                                <div class="icon notification"><span></span></div>
                            </a></li>
                    
                    <li> 
                        <label class="dropdown">

                            <div class="dd-button">
                                <img src="{{ asset('resources/assets') }}/demo/images/baricon.png" alt="">
                            </div>
                          
                            <input type="checkbox" class="dd-input" id="test">
                          
                            <ul class="dd-menu">
                                <li><a href="{{ url('profile') }}">Profile</a></li>
                              <li><a href="{{ url('admin-setting') }}">Admin-Setting</a></li>
                              <li><a href="{{ url('change-password') }}">Change-Password</a></li>
                              <li><a href="{{ url('logout') }}">Logout</a></li>
                             
                             
                            </ul>
                            
                          </label>
                        
                      
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>