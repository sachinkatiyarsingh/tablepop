@extends('layouts.app',['class' => 'dashboard', 'activePage' => 'dashboard'])

@section('content') 

<section class="dashboard">
  
    <div class="container">
           
           
        <div class="inner-content week">
            <h2 data-tab="week" class="dashboardTab dashboardweek"  style="display: inline-block; ">Current Week</h2>
            <h2 data-tab="month" class="dashboardTab dashboardmonth" style="display: inline-block">Current Month</h2>
        </div>
        <div class="tabs" id="week">
            <div class="week">
                <div class="current_week">
					<div class="boxes_parent">
                   		<div class="boxes">
                        <h4>Total revenue</h4> 
                               <span class="totalRevenueThisWeeek"></span>
                           </div>
                           <div class="boxes">
                               <h4>Virtual Planner</h4> 
                               <span class="plannerInPersonWeekCount"></span>
                           </div>
                           <div class="boxes">
                               <h4>In-person Planner</h4> 
                               <span class="plannerInnVirtualWeekCount"></span>
                           </div>
                           <div class="boxes">
                               <h4>Events In-Person Planning</h4> 
                               <span class="eventWeekIninPerson"></span>
                           </div>
                           <div class="boxes">
                               <h4>Events Online Planning</h4> 
                               <span class="eventWeekonline"></span>
                           </div>
				</div>
                        <div class="top_sellers">
                             
                       <div class="top_planners" style="float: left;width:45% ;    margin-right: 10px;" >  
                               <h2>Top Planner</h2>
                              
                                  @if(!empty($topPlannerThisWeek))
                                    
                                                                   
                                   @foreach ($topPlannerThisWeek as $topplannerWeek)
                                   @if (!empty($topplannerWeek['id']))
                                       
                                   
                                  <div class="planner">
                                       <div class="planner_box">
                                            
                                          <div>
                                            <div class="thumbnail" style="background: url({{ $topplannerWeek['profileImage'] ?? asset('resources/assets/demo/images/thumbnail.png')  }});">
                                            </div>
                                         
                                          </div>
                                         
                                           <div class="planner_details">
                                            <div class="rating">
                                                <ul>
                                                    @for($i=1;$i<=5;$i++)
                                                        
                                                    @if(!empty($topplannerWeek["rating"]) && $i<=$topplannerWeek["rating"]) 
                                                      @php
                                                          $fill = "fill";
                                                      @endphp
                                                    @else
                                                    @php
                                                    $fill = "blank";
                                                   @endphp
                                                    @endif
                                                    <li class="{{ $fill ?? ''  }}"></li>
                                                    @endfor		
                                                </ul>       
                                            </div>
                                            <a  href="{{ url('planner-profile') }}/{{ $topplannerWeek['id'] ?? '' }}" class="btn-link"> <h3>{{ $topplannerWeek['firstName'] ?? '' }} {{ $topplannerWeek['lastName'] ?? '' }}</h3></a>
                                               <h3>{{ $topplannerWeek['email'] ?? '' }}</h3>
                                               <h3>{{ $topplannerWeek['mobile'] ?? '' }}</h3> 
                                           </div>
                                           
                                       </div>
                                  </div>
                                  
                                  @endif 
                                  @endforeach 
                                  @endif 
 
                           </div>
                           <div class="top_planners"  style="float: left;width:45%;    margin-left: 10px;">
                            <h2>Top Vendor</h2>
                           
                               @if(!empty($topVendorThisWeek))
                                 
                                                                
                                @foreach ($topVendorThisWeek as $topvendorWeek)
                                @if (!empty($topvendorWeek['id']))
                                    
                               
                               <div class="planner">
                                    <div class="planner_box">
                                        <div>
                                        <div class="thumbnail" style="background: url({{ $topvendorWeek['profileImage'] ?? asset('resources/assets/demo/images/thumbnail.png')  }});">
                                          
                                        </div>
                                    </div>
                                        <div class="planner_details">
                                            <div class="rating">
                                                <ul>
                                                    @for($i=1;$i<=5;$i++)
                                                        
                                                    @if(!empty($topvendorWeek["rating"]) && $i<=$topvendorWeek["rating"]) 
                                                      @php
                                                          $fill = "fill";
                                                      @endphp
                                                    @else
                                                    @php
                                                    $fill = "blank";
                                                   @endphp
                                                    @endif
                                                    <li class="{{ $fill ?? ''  }}"></li>
                                                    @endfor		
                                                </ul>       
                                            </div>
                                            <a  href="{{ url('vendors-profile') }}/{{ $topvendorWeek['id'] ?? '' }}" class="btn-link">  <h3>{{ $topvendorWeek['firstName'] ?? '' }} {{ $topvendorWeek['lastName'] ?? '' }}</h3></a>
                                            <h3>{{ $topvendorWeek['email'] ?? '' }}</h3>
                                            <h3>{{ $topvendorWeek['mobile'] ?? '' }}</h3> 
                                        </div>
                                        
                                    </div>
                               </div>
                               
                               @endif 
                               @endforeach 
                               @endif 
                               <div style="clear: both"> </div>
                        </div>
                        <div style="clear: both"> </div>
                        </div> 
                           <div class="weekChart">
                           <div class="eventChart">
                            <div class="wrapper"> <canvas id="chartContainerweek" width="500" height="250"></canvas></div>
                            
                           </div>
                           <div class="eventChart">
                            <div class="wrapper"> <canvas id="linechartContainerweekcustomer" width="500" height="250"></canvas></div>
                            
                           </div>
                           <div class="eventChart">
                            <div class="wrapper"> <canvas id="linechartContainerweekplanner" width="500" height="250"></canvas></div>
                             
                           </div>
                           <div class="eventChart">
                            <div class="wrapper"> <canvas id="linechartContainerweekvendor" width="500" height="250"></canvas></div>
                            
                           </div>
                           <div style="clear: both"> </div>
                        </div>
                           <div class="table">
                            <table class="dataTable">
                                <thead>
                                    <tr style="text-align: left">
                                        <th>Event Type</th>
                                    <th>Total</th>
                                  
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                     @if(!empty($eventWeektypeEvent))
                                         @foreach($eventWeektypeEvent as $key => $WeektypeEvent)
                                         <tr>
                                            <td>{{ Helper::eventType($WeektypeEvent['typeEvent'] ?? '') }}</td>
                                            <td>{{ $WeektypeEvent['typeEventtotal'] ?? '' }}</td>
                                        </tr>
                                         @endforeach
                                     @endif
                                     
                                </tbody>
                            </table>
                   </div>
                    </div>
               </div>
            </div>
            
            <div class="tabs" style="display: none;width:100% "  id="month">
                <div class="month">
                    <div class="current_month">
                      <div class="boxes_parent">
                            <div class="boxes">
                                <h4> Total revenue</h4> 
                                <span class="totalRevenueThisMonth"></span>
                            </div>
                            <div  class="boxes">
                                <h4>Virtual Planner</h4> 
                                <span class="plannerInVirtualMonthCount"></span>
                            </div>
                            <div  class="boxes">
                                <h4>In-person Planner</h4> 
                                <span class="plannerInPersonMonthCount"></span>
                            </div>
                            
                            <div  class="boxes">
                                <h4>Events In-Person Planning</h4> 
                                <span class="eventmonthIninPerson"></span>
                            </div>
                            <div  class="boxes">
                                <h4>Events Online Planning</h4> 
                                <span class="eventmonthonline"></span>
                            </div>
						</div>
                        <div class="top_sellers">  
                        <div class="top_planners"  style="float: left;width:45%;    margin-right: 10px;">
                                <h2>Top Planner</h2>
                              
                                    @if(!empty($topPlannerThisMonth))
                                        
                                                                     
                                     @foreach ($topPlannerThisMonth as $topMonth)
                                     @if (!empty($topMonth['id']))   
                                   <div class="planner">
                                       <div class="planner_box">
                                        <div>
                                            <div class="thumbnail" style="background: url({{ $topMonth['profileImage'] ?? asset('resources/assets/demo/images/thumbnail.png')  }});">
                                             
                                            </div>
                                        </div>
                                           <div class="planner_details">
                                            <div class="rating">
                                                <ul>
                                                    @for($i=1;$i<=5;$i++)
                                                        
                                                    @if(!empty($topMonth["rating"]) && $i<=$topMonth["rating"]) 
                                                      @php
                                                          $fill = "fill";
                                                      @endphp
                                                    @else
                                                    @php
                                                    $fill = "blank";
                                                   @endphp
                                                    @endif
                                                    <li class="{{ $fill ?? ''  }}"></li>
                                                    @endfor		
                                                </ul>       
                                            </div>
                                            <a  href="{{ url('planner-profile') }}/{{ $topMonth['id'] ?? '' }}" class="btn-link"> <h3>{{ $topMonth['firstName'] ?? '' }} {{ $topMonth['lastName'] ?? '' }}</h3></a>
                                               <h3>{{ $topMonth['email'] ?? '' }}</h3>
                                               <h3>{{ $topMonth['mobile'] ?? '' }}</h3> 
                                           </div>
                                       </div>
                                  </div>
                                  @endif 
                                  @endforeach 
                                  @endif 
                             </div>
                             <div class="top_planners"  style="float: left;width:45%;    margin-left: 10px;">
                                <h2>Top Vendor</h2>
                              
                                    @if(!empty($topVendorThisMonth))

                                                                     
                                     @foreach ($topVendorThisMonth as $topVendor)
                                     @if (!empty($topVendor['id']))   
                                   <div class="planner">
                                       <div class="planner_box">
                                        <div>
                                            <div class="thumbnail" style="background: url({{ !empty($topVendor['profileImage']) ? $topVendor['profileImage']:  asset('resources/assets/demo/images/thumbnail.png')  }});">
                                              
                                            </div>
                                        </div>
                                           <div class="planner_details">
                                            <div class="rating">
                                                <ul>
                                                    @for($i=1;$i<=5;$i++)
                                                        
                                                    @if(!empty($topVendor["rating"]) && $i<=$topVendor["rating"]) 
                                                      @php
                                                          $fill = "fill";
                                                      @endphp
                                                    @else
                                                    @php
                                                    $fill = "blank";
                                                   @endphp
                                                    @endif
                                                    <li class="{{ $fill ?? ''  }}"></li>
                                                    @endfor		
                                                </ul>       
                                            </div>
                                            <a href="{{ url('vendors-profile') }}/{{ $topVendor['id'] ?? '' }}" class="btn-link">    <h3>{{ $topVendor['firstName'] ?? '' }} {{ $topVendor['lastName'] ?? '' }}</h3></a>
                                              
                                               <h3>{{ $topVendor['email'] ?? '' }}</h3>
                                               <h3>{{ $topVendor['mobile'] ?? '' }}</h3> 
                                           </div>
                                       </div>
                                  </div>
                                  @endif 
                                  @endforeach 
                                  @endif 
                             </div>
                             </div>
                             <div class="weekChart">
                            <div class="eventChart">
                                <div class="wrapper"> <canvas id="chartContainermonth" width="500" height="250"></canvas></div>
                               
                             </div>
                             <div class="eventChart">
                                <div class="wrapper"> <canvas id="linechartContainermonthcustomer" width="500" height="250"></canvas></div>
                              
                             </div>
                             <div class="eventChart">
                                <div class="wrapper"> <canvas id="linechartContainermonthplanner" width="500" height="250"></canvas></div>
                            
                             </div>
                             <div class="eventChart">
                                <div class="wrapper"> <canvas id="linechartContainermonthvendor" width="500" height="250"></canvas></div>
                             
                             </div>
                             <div style="clear: both"> </div>
                            </div>
                             <div class="table">
                                <table class="dataTable">
                                    <thead>
                                        <tr style="text-align: left">
                                            <th>Event Type</th>
                                        <th>Total</th>
                                      
                                        </tr>
                                        
                                    </thead>
                                    <tbody>
                                         @if(!empty($eventmonthtypeEvent))
                                             @foreach($eventmonthtypeEvent as $key => $monthtypeEvent)
                                             <tr>
                                                <td>{{ Helper::eventType($monthtypeEvent['typeEvent'] ?? '') }}</td>
                                                <td>{{ $monthtypeEvent['typeEventtotal'] ?? '' }}</td>
                                            </tr>
                                             @endforeach
                                         @endif
                                         
                                    </tbody>
                                </table>
                       </div>
                        </div>
                    </div>
                </div>
            </div>

   
    
   
    
 
       

</section>

@endsection
