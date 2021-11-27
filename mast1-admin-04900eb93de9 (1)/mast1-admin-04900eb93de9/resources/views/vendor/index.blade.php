@extends('layouts.app',['class'=>'customers','activePage'=>'vendors'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content">
            <div class="left_sidebar">
                <div class="filter_heading">
                    <span><img src="{{ asset('resources/assets') }}/demo/images/filter.png" alt=""/>Filter</span>
                </div>
                <ul> 
                    <form  method="post" action="{{ url('vendors') }}">
                        @csrf
                    <li><input type="text"  id="autocomplete" class="form-control" name="location" placeholder="Location" value="{{ Request::input('location') }}"></li>
                   
                    <li> <input type="text"  class="form-control" name="skill" placeholder="Skill" value="{{ Request::input('skill') }}">  </li>
                    <li><select class="form-control"  name="expertise" id="">
                           <option value="">Expertise</option>
                           @if(!empty($event))
                               @foreach($event as $value)
                               <option value="{{ $value['id'] }}" {{ Request::input('expertise') == $value['id'] ? "selected" : '' }}>{{ $value['name'] }}</option>
                               @endforeach
                           @endif
                           <!--option value="other">Other</option-->
                        </select>
                    </li>
                   
                    <li><select  class="form-control" name="experience" id="">
                        <option value="">Experience</option>
                        <option value="zero-three" {{ Request::input('experience') == "zero-three" ? "selected" : '' }}>0-3 years </option>
                        <option value="four-seven" {{ Request::input('experience') == "four-seven" ? "selected" : '' }}>4-7 years</option>
                        <option value="eight-plus" {{ Request::input('experience') == "eight-plus" ? "selected" : '' }}>8+ years</option>
                        
                        <!--option value="other">Other</option-->
                     </select></li>
                     <button type="submit" class="btn btn-primary">Search</button>
                  </form>
                </ul>
                @if(Session::get('type') == 1)
                <a href="{{ url('vendors-add')}}" class="btn btn-primary">Add Vendor</a>
             @else
             @if(in_array('add',$vendor ??  array()))
               <a href="{{ url('vendors-add')}}" class="btn btn-primary">Add Vendor</a> 
             @endif 
            @endif
              
            </div>
            <input type="hidden" class="url" name="" value="status">
            <div class="main_content">
                <div class="vendor_list">
                    @if (!empty($data))
                        @foreach ($data as $row)
                    <div class="vendor_box move">
                        <div class="vendor_thumb">
                            <div class="thumbnail" style="background: url({{ !empty($row['profileImage']) ? Helper::getUrl()."vendor/vendor".$row['id']."/".$row['profileImage'] :  asset('resources/assets/demo/images/thumbnail.png') }});"></div>
                            @if(Session::get('type') == 1)
                            <a href="{{ url('vendors-profile') }}/{{ $row['id'] ?? '' }}" class="btn-link">View Vendor</a>
                            @endif
                            @if(in_array('profile',$vendor ??  array()))
                            <a href="{{ url('vendors-edit') }}/{{ $row['id'] ?? '' }}" class="btn-link">View Vendor</a>
                            @endif
                        </div>
                        <div class="rating">
                            <ul>
                                @for($i=1;$i<=5;$i++)
                                    
                                @if(!empty($row["rating"]) && $i<=$row["rating"]) 
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
                        @if(!empty($row['contract']))
                        <div class="one"><a  download href="{{ Helper::getUrl()."vendor/vendor".$row['id']."/".$row['contract'] }}"><button class="round-icon">Contract <img src="{{ asset('resources/assets') }}/demo/images/download.png" alt=""/></button></a></div>
                        @endif
                        <div class="vendor-details">
                            <h3>{{ $row['firstName'] ?? '' }} {{ $row['lastName'] ?? '' }}</h3>
                            <span class="expertise">{{ Helper::expertise($row['experiencePlanning'])  }}</span>  
                            <span class="location">{{ $row['location']  ?? ''}}</span>
                        </div>
                        <div class="vendor_bottom">
                            @if($row['status'] == 0 )
                               <a href="javascript:void(0);" data-id="{{ $row['id'] }}" data-taxt="Active"  data-status="approved" class="btn approved approv" >Active  <span> </span> </a>
                               <a href="javascript:void(0);"  data-id="{{ $row['id'] }}" data-taxt="In-Active"  data-status="decline" class="btn approved decline">In-Active <span> </span> </a>
                           
                            @else
                            <a href="javascript:void(0);" @if($row['status'] == 2)   @else  style="display: none" @endif data-id="{{ $row['id'] }}" data-taxt="Active"  data-status="approved" class="btn approved approv">Active <span> </span> </a>
                            <a href="javascript:void(0);" @if($row['status'] == 1)   @else  style="display: none" @endif data-id="{{ $row['id'] }}" data-taxt="In-Active"  data-status="decline" class="btn approved decline">In-Active <span> </span></a>
                        
                            @endif
                           
                            <div class="controls">
                                <ul>
                                   
                                    @if(in_array('edit',$vendor ??  array()))
                                    <li class="active"><a href="{{ url('vendors-edit') }}/{{ $row['id'] ?? '' }}"><img src="{{ asset('resources/assets') }}/demo/images/edit.png" alt=""/></a></li>
                                    @endif
                                    @if(Session::get('type') == 1)
                                    <li class="active"><a href="{{ url('vendors-edit') }}/{{ $row['id'] ?? '' }}"><img src="{{ asset('resources/assets') }}/demo/images/edit.png" alt=""/></a></li>
                                    
                                    <!--li data-id="{{ $row['id'] ?? '' }}" class="active delete"><img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/></li-->
                                    
                                    @endif
                                    @if(in_array('delete',$vendor ??  array()))
                                    <li data-id="{{ $row['id'] ?? '' }}" class="active delete"><img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    {{ $data->links() }}
                    
                </div>
            </div>
        </div>
    </div>
</section>

@endsection