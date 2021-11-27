@extends('layouts.app',['class' => 'vendors', 'activePage' => 'vendors'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content">
            <div class="left_sidebar">
                <div class="vendor_box">
                    <div class="vendor_thumb">
                        <div class="thumbnail" style="background: url({{ !empty($data['profileImage']) ? $data['profileImage'] :  asset('resources/assets/demo/images/thumbnail.png') }});"></div>
      
                    </div>
                    <div class="vendor-details ">
                        <h3>{{ $data['firstName'] ?? '' }} {{ $data['lastName'] ?? '' }}</h3>
                     
                       <span class="expertise">{{ $experience ?? '' }}</span>
                        
                        
                        <span class="location">{{ $data['location'] ?? '' }}</span>
                    </div>
                    <!--div class="vendor_bottom ">
                        <a href="#" class="btn">Select <span></span></a>
                        <div class="controls">
                            <ul>
                                <li><img src="{{ asset('resources/assets') }}/demo/images/msg.png" alt="" /></li>
                                <li class="active"><img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt="" /></li>
                            </ul>
                        </div>
                    </div-->
                </div>
                <div class="back_to_vendor">
                    <a href="{{ url('vendors') }}" class="back_vendors">Back to vendor list</a>
                </div>
            </div>
            <div class="main_content">
                <div class="banner_inner">
                    <div id="mdh-carousel" class="container-fluid">
                        <div class="owl-carousel owl-theme">
                        @if(!empty($data['projectImage']))
                            @foreach ($data['projectImage'] as $projectImage)
                            <div class="item mdh-item-overlay">
                                <div class="pic a">  <img src="{{ $projectImage['image']  }}" alt="" class="banner_right"></div>
                                <div class="info">
                                    <h3>{{ $projectImage['event'] ?? '' }}</h3>
                                    <span>{{ $projectImage['numberAttendees']  ?? ''}}</span>
                                    <span>{{ $projectImage['locationEvent'] ?? '' }}
                                    </span>
                                </div>
                          
                            </div>
                           @endforeach
                        @else
                            <div class="item">
                                <img src="{{ url('/') }}/resources/assets/demo/images/NoImage.png" alt="" class="banner_right">
                            </div>
                        @endif
                     </div>
                    </div>
                   
                            


                </div>
                <div class="bottom_text_box">
                <div class="bottom_text">
                   
                </div>
            </div>
            <div style="clear: both"></div>
            </div>
            <div style="clear: both"></div>
           
        </div>
        <div>
            <h2>Reviews </h2>
            <div>
               @if (!empty($review))
                  @foreach ($review as $view)
                  <div class="review">
                    <div class="customerImage"><img  src="{{ $view['profileImage'] ?? asset('resources/assets/demo/images/thumbnail.png') }}">
                     <div>
                         <div>
                            <h4 class="reviewheading">{{  $view['name'] ?? ''  }}</h4>
                            <div class="rating">
                                <ul>
                                    @for($i=1;$i<=5;$i++)
                                        
                                    @if(!empty($view["rating"]) && $i<=$view["rating"]) 
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
                         </div>
                        
                        <div class="comment">
                            {{  $view['comment']  ?? ''}} 
                        </div>
                       
                     </div>
                     <div style="clear: both"></div>  
                    </div>
                    <div class="review-date">{{ date('D,d M Y',strtotime($view['created_at']))  }}</div>
                 </div>
                  @endforeach   
               @endif  
      
            </div>
        </div>

      <div>

          <h2>Products</h2>
         
          @if (!empty($product))
              @foreach ($product as $pro)
               <div class="product">
                <div class="image">
                  <img src="{{ $pro['image'] ??  asset('resources/assets/demo/images/NoImage.png') }}"  style="width:100%;height: 150px;">
                </div>
             
              <h1>{{ $pro['name'] ?? '' }}</h1>
              <span class="price">$ <del>{{ $pro['regularPrice'] ?? '' }}</del></span><br>
              <span class="price">${{ $pro['salePrice'] ?? '' }}</span><br>
              {{ $pro['description'] ?? '' }}
             
            </div>
              @endforeach
          @endif
         
      </div>
    </div>

</section>

@endsection