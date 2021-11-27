@extends('layouts.app',['class'=>'questionnaire','activePage'=>'questionnaire'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content">
            <div class="left_sidebar">
                <div class="filter_heading">
                    <span><img src="{{ asset('resources/assets') }}/demo/images/filter.png" alt=""/>Filter</span>
                </div>
                <ul>
                    <form action="" method="get">
                        <li><input type="text"  id="autocomplete" class="form-control" name="location" placeholder="Location"> </li>
                        <li> <input type="text" class="form-control" name="skill" placeholder="Skill">  </li>
                        <li><select class="form-control" name="expertise" id="">
                               <option value="">Expertise</option>
                               @if(!empty($event))
                                   @foreach($event as $value)
                                   <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                   @endforeach
                               @endif
                               <!--option value="other">Other</option-->
                            </select>
                        </li>
                        <li><select class="form-control" name="experience" id="">
                            <option value="">Experience</option>
                            <option value="zero-three">0-3 years </option>
                            <option value="four-seven">4-7 years</option>
                            <option value="eight-plus">8+ years</option>
                            
                            <!--option value="other">Other</option-->
                         </select></li>
                        <button class="btn btn-primary" name="submit">Submit</button>
                      </form>
                </ul>
                @if(Session::get('type') == 1)
                <a href="{{ url('planners-add')}}" class="btn btn-primary">Add Planner</a>
             @else
             @if(in_array('add',$planner ??  array()))
               <a href="{{ url('planners-add')}}" class="btn btn-primary">Add Planner</a> 
             @endif 
            @endif
              
            </div>
            <input type="hidden" class="url" name="" value="planners-delete">
            <div class="main_content">
                 <input type="hidden" name="" class="plannerToken" value="">
                 <input type="hidden" name="" class="questionnaireId" value="{{ $id ?? '' }}">
                <div class="vendor_list">
                    @if (!empty($data))
                        @foreach ($data as $row)
                    <div class="vendor_box move">
                        <div class="vendor_thumb">
                            <div class="thumbnail" style="background: url({{ !empty($row['profileImage']) ? Helper::getUrl()."vendor/vendor".$row['id']."/".$row['profileImage'] :  asset('resources/assets/demo/images/thumbnail.png') }});"></div>
                            @if(Session::get('type') == 1)
                            <a href="{{ url('planners-edit') }}/{{ $row['id'] ?? '' }}" class="btn-link">View Planner</a>
                            @endif
                            @if(in_array('edit',$planner ??  array()))
                            <a href="{{ url('planners-edit') }}/{{ $row['id'] ?? '' }}" class="btn-link">View Planner</a>
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
                        <div class="vendor-details">
                            <h3>{{ $row['firstName'] ?? '' }} {{ $row['lastName'] ?? '' }}</h3>
                            <span class="expertise">Expertise</span>
                            <span class="location">Location</span>
                        </div>
                        <div class="vendor_bottom">
                            <a href="javascript:void(0)" class="btn sellerToken" data-id="{{ $row['id'] ?? '' }}">Select<span ><input  class="selectSeller" name="selectSeller" type="checkbox" value="{{ $row['id'] ?? '' }}"> </span></a>
                            <div class="controls">
                                <ul>
                                    <li><img src="{{ asset('resources/assets') }}/demo/images/msg.png" alt=""/></li>
                                    @if(Session::get('type') == 1)
                                    <li data-id="{{ $row['id'] ?? '' }}" class="active delete"><img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/></li>
                                    @endif
                                    @if(in_array('edit',$planner ??  array()))
                                    <li data-id="{{ $row['id'] ?? '' }}" class="active delete"><img src="{{ asset('resources/assets') }}/demo/images/delete.png" alt=""/></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    
                    
                </div>
                <button type="button" style="display: none" class="btn btn-primary sendMail">
                   Send    
                </button>
            </div>
        </div>
    </div>
</section>

@endsection