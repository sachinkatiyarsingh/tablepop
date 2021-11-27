@extends('layouts.app',['class' => 'customers', 'activePage' => 'customers'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Edit</h4>

            <form method="post" action="{{ url('customers-edit') }}/{{ $data['id'] ?? '' }}">
                @csrf
                <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name',$data['name'] ?? '') }}">
                            @if ($errors->has('name'))
                            <span class="error"role="alert">
                             {{ $errors->first('name') }}.
                            </span>
                        @endif
                        </div>
                 
                <div class="form-group">
                            <input type="text" class="form-control" name="surname" placeholder="Surname" value="{{ old('surname',$data['surname'] ?? '') }}">
                            @if ($errors->has('surname'))
                            <span class="error"role="alert">
                               {{ $errors->first('surname') }}.
                            </span>
                        @endif
                        </div>
                 
               
         <div class="form-group">
                            <input type="email" name="email"  class="form-control" placeholder="Email Address" value="{{ old('email',$data['email'] ?? '') }}">
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
                            <label>Country</label>
                            <select class="select2 country form-control" name="country" data-placeholder="Select a make">
                                <option></option>
                               @if(!empty($countries))
                                   @foreach($countries as $key => $country)
                                   <option value="{{ $country->id ?? '' }}"{{ $data['country_id'] ==  $country->id ? "selected":"" }}>{{ $country->name ?? '' }}</option>
                                   @endforeach
                                @endif
                            </select>
                            @if ($errors->has('country'))
                            <span class="error"role="alert">
                               {{ $errors->first('country') }}.
                            </span>
                        @endif
                        </div>
                    
                   
                    
                     <div class="form-group">
                            <label>States</label>
                            <select class="select2 states form-control" name="state" data-placeholder="Select a make">
                               @if(!empty($states))
                                    @foreach($states as $key => $state)
                                    <option value="{{ $state->id ?? '' }}"{{ $data['state_id'] ==  $state->id ? "selected":"" }}>{{ $state->name ?? '' }}</option>
                                    @endforeach
                               @endif
                            </select>
                            @if ($errors->has('state'))
                            <span class="error"role="alert">
                               {{ $errors->first('state') }}.
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