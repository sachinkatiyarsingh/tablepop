@extends('layouts.app',['class' => 'profile', 'activePage' => 'profile'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Profile</h4>
            <form method="post" enctype="multipart/form-data" action="{{ url('profile/insert') }}">
                @csrf
                <div class="form-group">
                   <img style="    width: 130px;height: 130px; border-radius: 50%;" src="{{ !empty($data['image']) ? Helper::getUrl().'admin/admin'.$data['id'].'/'.$data['image'] : asset('resources/assets/demo/images/thumbnail.png')  }}" alt="">
                  
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name',$data['name'] ?? '') }}">
                    @if ($errors->has('name'))
                    <span class="error"role="alert">
                      {{ $errors->first('name') }}.
                    </span>
                @endif
                </div>
                
                <div class="form-group">
                            <input type="email" name="email" readonly  class="form-control" placeholder="Email Address" value="{{ old('email',$data['email'] ?? '') }}">
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
                   
                        <div class="form-group">
                            <input type="file" class="form-control" name="image" placeholder="Image">
                            @if ($errors->has('image'))
                            <span class="error"role="alert">
                               {{ $errors->first('image') }}.
                            </span>
                        @endif
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