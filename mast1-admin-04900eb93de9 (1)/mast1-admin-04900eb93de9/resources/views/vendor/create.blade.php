@extends('layouts.app',['class' => 'vendors', 'activePage' => 'vendors'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('vendors-add') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="">FirstName</label>
                    <input type="text" class="form-control" name="firstname" placeholder="FirstName" value="{{ old('firstname') }}">
                    @if ($errors->has('firstname'))
                    <span class="error"role="alert">
                      {{ $errors->first('firstname') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">LastName</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="{{ old('lastname') }}">
                    @if ($errors->has('lastname'))
                    <span class="error"role="alert">
                      {{ $errors->first('lastname') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">UserName</label>
                    <input type="text" class="form-control" name="userName" placeholder="UserName" value="{{ old('userName') }}">
                    @if ($errors->has('userName'))
                    <span class="error"role="alert">
                      {{ $errors->first('userName') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                            <input type="email" name="email"  class="form-control" placeholder="Email Address" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="error"role="alert">
                                    {{ $errors->first('email') }}.
                                </span>
                            @endif
                </div>
                <div class="form-group">
                    <label for="">Mobile</label>
                    <input class="form-control" placeholder="Mobile Number" name="mobileNo" id="phone" type="tel" value="{{ old('mobileNo') }}">
                    <span id="error-msg" class="error"></span>
                    <input type="hidden" name="phoneNo" class="phoneNo" value="{{ old('mobileNo') }}">  
                         @if ($errors->has('phoneNo'))
                         <span class="error"role="alert">
                           {{ $errors->first('phoneNo') }}.
                         </span>
                     @endif
                </div>
                <div class="form-group">
                    <label for="">Profile Image</label>
                    <input type="file" class="form-control" name="image" placeholder="Image" value="{{ old('image') }}">
                    @if ($errors->has('image'))
                    <span class="error"role="alert">
                       {{ $errors->first('image') }}.
                    </span>
                @endif
               </div>
               <!--div class="form-group">
                <label for="">Contract</label>
                <input type="text" class="form-control" name="contract" placeholder="Contract" value="{{ old('contract') }}">
                @if ($errors->has('contract'))
                <span class="error"role="alert">
                   {{ $errors->first('contract') }}.
                </span>
            @endif
           </div-->
                   
                
                        
                   
                   
                  
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