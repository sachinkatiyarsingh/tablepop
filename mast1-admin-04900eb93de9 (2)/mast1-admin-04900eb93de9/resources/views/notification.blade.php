@extends('layouts.app',['class' => 'dashboard', 'activePage' => 'dashboard'])

@section('content') 

<section class="dashboard">
    <div class="container">
        <div class="dashboard_inner">
            <div class="notification_block">
               <div class="notification_head">
                    <h2>New notifications </h2>
                    <a href="#">view all</a>
               </div>
               @if(!empty($notification))
               <div class="notificationScroll" style="overflow: scroll; height:600px ">
                 @foreach($notification as $key => $value)
                       @if($value['urlType'] == 'customer')
                           @php
                               $link = url('customers')
                           @endphp
                        @elseif($value['urlType'] == 'event')
                            @php
                                  $link = url('event-list')
                            @endphp
                        @elseif($value['urlType'] == 'vendor')
                            @php
                                $link = url('vendors')
                            @endphp
                        @elseif($value['urlType'] == 'planner')
                            @php
                               $link = url('planners')
                            @endphp
                         @elseif($value['urlType'] == 'offer')
                         @php
                            $link = url('event-list')
                         @endphp
                        @else
                        @php
                            $link =  'javascript:void(0)';
                         @endphp
                       @endif
               <div class="notification_wrap notificationMove">
                    <div class="notification_box">
                        <div class="thumbnail" style="background: url({{ asset('resources/assets') }}/demo/images/mask.png);">

                        </div>
                        <div class="notification_details">
                            <h3>New request</h3>
                            <p>{{ $value['notification'] ?? '' }}</p>
                        </div>
                        <div class="notification_control">
                            <a href="{{ $link  }}">reply request</a>
                            <div class="trash_icon notificationDelete" data-id="{{ $value['id'] ?? '' }}">
                                <img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/>
                            </div>
                        </div>
                    </div>
               </div>
               @endforeach
            </div>
             @endif
            </div>



            <div class="ongoing_box">
                <div class="notification_head">
                    <h2>Ongoing</h2>
                    <a href="{{ url('event-list') }}">View more project</a>
               </div>
               @if(!empty($ongoing))
               <div class="ongoingScroll" style="overflow: scroll; height:600px ">
               @foreach($ongoing as  $ongoingData)
               <div class=ongoing_section>
                    <div class="ongoing_inner_box">
                        <img style="    width: 90px;
                        height: 90px;
                        border-radius: 50%;" src="{{ $ongoingData['image'] ?? url('/resources/assets/demo/images/NoImage.png') }}" alt=""/>
                        <h2>{{ $ongoingData['eventName'] ?? '' }}</h2>
                        <ul>
                            <li style="display: inline-block; text-align: left;" ><a href="#">View invoice</a></li>
                         
                            <li style="display: inline-block;text-align: right;"><a href="#">Message administrator</a></li>
                        </ul>
                        <!--div class="delte_icon">
                            <img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/>
                        </div--->
                    </div>
               </div>
               @endforeach
              {{--   {{ $ongoing->render()}}  --}}
            </div>
               @endif
               
            </div>
            <div class="message_box">
                <div class="notification_head">
                    <h2>New messages</h2>
                    <a href="{{ url('message') }}">all messages</a>
               </div>
               <div class="messagesScroll" style="overflow: scroll; height:600px ">
               @if(!empty($messages))
               <div class="messages_block " id="post-data">
              
                @foreach($messages as  $messagesData)
                    <div class="d_message_box">
                        <div class="message_head">
                            <h2>{{ $messagesData['name'] ?? '' }}</h2>
                            <a href="{{ url('message') }}">reply message</a>
                        </div>
                        <div class="message_text">
                            <p>{{ $messagesData['message'] ?? ''  }}</p>
                        </div>
                    </div>
                    @endforeach
                   {{--   {{ $messages->render()}}  --}}
                </div>
              
               @endif
            </div>
        </div>
        </div>

        <div class="ajax-load text-center" style="display:none">
            <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
        </div>
        

        
       

        


      

</section>

@endsection
