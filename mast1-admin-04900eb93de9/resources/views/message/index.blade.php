@extends('layouts.app',['class' => 'dashboard', 'activePage' => 'message'])

@section('content') 
<section class="message_section">
        <div class="container">
            <div class="chat_wrap">
                <div class="chat_sidebar">
					<input type="hidden" name="" class="userId" value="{{ Session::get('id') }}">
                    @if(!empty($data))
                         
                    @foreach($data as $key => $value)
                        @php
                            $currentDate = date('Y-m-d');
                            $messageDate = date('Y-m-d',strtotime($value['messageDate'] ?? ''));
                        @endphp
                     
                    <div class="user_chat_box userContact" data-id="{{ $value['groupId'] ?? '' }}" data-value="{{ $value['userId'] ?? '' }}"  >
                        <span class="date">{{ !empty($value['messageDate'] ?? '') ?  date('M ,d Y', strtotime($value['messageDate'])) : '' }}</span>
                        <div class="user_chat_box_inner ">
                            <div class="thumbnail" style="background: url({{  asset('resources/assets/demo/images/thumbnail.png') }});">
                            </div>
                            <div class="chat_sidebox">
                                <h4 class="contactName">{{ $value['eventName'] ?? '' }} {{ !empty($value['vendorName']) ? 'vs '. $value['vendorName'] : '' }}<span class="currentTime"> {{ date('h:i a',strtotime($value['messageDate'])) }}    </span></h4>
                                <p class="lastMessage">{{ $value['message'] ?? '' }}</p>
                                <p class=""> 
                                    @if($value['type'] == 1)
                                        {{ !empty($value['customerId']) ? 'Customer' : 'Seller' }}
                                    @endif
                                </p>
                                    <div class="chat_control">
                                        <div class="chat_control_inner">
                                            <label  class="count"></label>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    @endif


                    @if(!empty($customerData))
                         
                    @foreach($customerData as  $customer)
                        @php
                            $currentDate = date('Y-m-d');
                            $messageDate = date('Y-m-d',strtotime($customer['messageDate'] ?? ''));
                        @endphp
                     
                    <div class="user_chat_box userContact" data-id="{{ $customer['groupId'] ?? '' }}"  data-value="{{ $customer['id'] ?? '' }}"  data-type="2">
                        <span class="date">{{ !empty($customer['messageDate'] ?? '') ?  date('M ,d Y', strtotime($customer['messageDate'] ?? '')) : '' }}</span>
                        <div class="user_chat_box_inner">
                            <div class="thumbnail" style="background: url({{  asset('resources/assets/demo/images/thumbnail.png') }});">
                            </div>
                            <div class="chat_sidebox">
                                <h4>{{ $customer['name'] ?? '' }}<span class="currentTime"> {{ date('h:i a',strtotime($customer['messageDate'] ?? '')) }}    </span></h4>
                                <p class="lastMessage">{{ $customer['message'] ?? '' }}</p>
                                <p class="">Customer</p>
                                    <div class="chat_control">
                                        <div class="chat_control_inner">
                                            <label  class="count"></label>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    @endif


                    @if(!empty($sellerData))
                         
                    @foreach($sellerData as  $seller)
                        @php
                            $currentDate = date('Y-m-d');
                            $messageDate = date('Y-m-d',strtotime($seller['messageDate'] ?? ''));
                        @endphp
                     
                    <div class="user_chat_box userContact" data-id="{{ $seller['groupId'] ?? '' }}"  data-value="{{ $seller['id'] ?? '' }}"  data-type="3>
                        <span class="date">{{ !empty($seller['messageDate'] ?? '') ?  date('M ,d Y', strtotime($seller['messageDate'] ?? '')) : '' }}</span>
                        <div class="user_chat_box_inner ">
                            <div class="thumbnail" style="background: url({{  asset('resources/assets/demo/images/thumbnail.png') }});">
                            </div>
                            <div class="chat_sidebox">
                                <h4>{{ $seller['firstName'] ?? '' }} {{ $seller['lastName'] ?? '' }}<span class="currentTime"> {{ date('h:i a',strtotime($seller['messageDate'])) }}    </span></h4>
                                <p class="lastMessage">{{ $seller['message'] ?? '' }}</p>
                                <p class="">{{ $seller['type'] == 1 ? 'Vendor' : 'Planner' }}</p>
                               
                                    <div class="chat_control">
                                        <div class="chat_control_inner">
                                            <label  class="count"></label>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    @endif
                </div>
                <input type="hidden" name="" class="fileName" value="">
                <div   class="chat_messages">
                    <div style=" width: 100%;" class="messageChat">
                        <h3 class="chatName"></h3>
                    <div class="message_list messageLists">
                      
                    </div>
                    <div class="message">
                        <input type="hidden" class="sendid" >
                        <textarea class="messageBox" placeholder="999 characters left"></textarea>
                        
                        <div class="send_btn">
						<form method="POST" enctype="multipart/form-data" class="attach_form">
                        <div class="image-upload" class="sendMessage">
                            <label for="file-input">
                                <img src="{{ asset('resources/assets/demo/images/attach.png') }}"/>
                            </label>
																								 Attach File
                            <input id="file-input" multiple  name="image[]" type="file"/>
                        </div>
                       </form>
                            <a href="javascript:void(0)"  class="sendMessage">
                                Send
                            </a>
                        </div>
                        <div class="messageImageStatus"></div>
                    </div>
                    <div id="LoadingImage" style="display: none">
                        <img src="{{ asset('resources/assets') }}/demo/images/loader.gif" />
                      </div>  
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection