@extends('layouts.app',['class' => 'themes', 'activePage' => 'blogs'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('blog-add') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="blogs_files" value="">
                <div class="form-group">
                    <label for="">Planner</label>
                     <select class="form-control select2" multiple name="seller[]" id="">
                         <option value="">Seller</option>
                         @if(!empty($data))
                             @foreach($data as $key => $value)
                             <option value="{{ $value['id'] ?? '' }}"  {{ old('seller') == $value['id'] ? 'selected'  : ' ' }}>{{ $value['firstName']  ?? ' ' }} {{ $value['lastName'] ?? '' }}</option>
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
                    <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title') }}">
                    @if ($errors->has('title'))
                    <span class="error"role="alert">
                        {{ $errors->first('title') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                   <textarea name="description" class="form-control" id="editor1" placeholder="Description" style="height: 100px;" id="" cols="300" rows="100">{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                    <span class="error"role="alert">
                        {{ $errors->first('description') }}.
                    </span>
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