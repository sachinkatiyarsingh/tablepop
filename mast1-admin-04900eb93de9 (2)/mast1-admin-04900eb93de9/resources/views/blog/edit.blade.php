@extends('layouts.app',['class' => 'themes', 'activePage' => 'blogs'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('blog-edit') }}/{{ $blog['id'] ?? '' }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="blogs_files" value="">
                <input type="hidden" class="url" name="" value="blog-file-delete">
                <div class="form-group">
                    <label for="">Planner</label>
                    @php
                         $sellerIds = !empty($blog['sellerId']) ? $blog['sellerId'] : '' ;
                         $sellerIds = explode(',',$sellerIds);
                         
                    @endphp
                     <select class="form-control select2" name="seller[]" multiple id="">
                         <option value="">Seller</option>
                         @if(!empty($data))
                             @foreach($data as $key => $value)
                              @if (in_array($value['id'],$sellerIds))
                              @php
                                  $selected =  'selected' ;
                              @endphp 
                              @else
                              @php
                              $selected =  '' ;
                          @endphp 
                              @endif
                             <option value="{{ $value['id'] ?? '' }}"  {{ !empty($selected) ? $selected : '' }}>{{ $value['firstName']  ?? ' ' }} {{ $value['lastName'] ?? '' }}</option>
                             @endforeach
                         @endif
                       
                     </select>
                    @if ($errors->has('seller'))
                    <span class="error"role="alert">
                        {{ $errors->first('seller') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title',$blog['title'] ?? '') }}">
                    @if ($errors->has('title'))
                    <span class="error"role="alert">
                        {{ $errors->first('title') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                   <textarea name="description" class="form-control" id="editor1" placeholder="Description" style="height: 100px;" id="" cols="300" rows="100">{{ old('description',$blog['description'] ?? '') }}</textarea>
                    @if ($errors->has('description'))
                    <span class="error"role="alert">
                        {{ $errors->first('description') }}.
                    </span>
                @endif
                </div>

                <div>
                    @if(!empty($blogImage))
                        @foreach($blogImage as $key => $file)
                                @if($file['type']  == 'image')
                                   <div class="move">
                                    <img width="100px"  height="100px" src="{{ Helper::getUrl().'admin/blogs/'.$file['file'] }}" alt="" srcset="">
                                    <button type="button"  data-id="{{ $file['id'] }}" class="btn btn-primary delete" style="padding: 1px 6px;">×</button>
                             
                                   </div>
                                @elseif($file['type']  == 'video')
                                <div class="move">
                                    <video width="320" height="240" controls autoplay>
                                        <source src="{{ Helper::getUrl().'admin/blogs/'.$file['file'] }}" type="video/mp4">
                                        <source src="{{ Helper::getUrl().'admin/blogs/'.$file['file'] }}" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <button type="button"  data-id="{{ $file['id'] }}" class="btn btn-primary delete" style="padding: 1px 6px;">×</button>
                               
                                </div>
                                  @endif
                        @endforeach
                    @endif
                </div>
                <div class="form-group">
                    <label for="">File</label>
                    <input type="file" class="form-control" id="blogFile" multiple name="file[]" placeholder="File" value="{{ old('file') }}">
                    @if ($errors->has('file'))
                    <span class="error"role="alert">
                        {{ $errors->first('file') }}.
                    </span>
                @endif
                </div>
                
              
                   
                <div>
                    <ul class="preview_box">

                    </ul>
                </div>
                <div id="LoadingImage" style="display: none">
                    <img src="{{ asset('resources/assets') }}/demo/images/loader.gif" />
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