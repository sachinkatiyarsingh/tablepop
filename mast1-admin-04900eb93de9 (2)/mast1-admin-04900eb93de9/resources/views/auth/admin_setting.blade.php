@extends('layouts.app',['class' => 'customers', 'activePage' => 'admin_seetion'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Admin Setting</h4>

            <form method="post" action="{{ url('admin-setting-insert') }}">
                @csrf
                <div class="form-group">
                             <label for="">Tax in %</label>
                            <input type="text" class="form-control" name="tax" placeholder="Tax in %" value="{{ old('tax',$data['tax'] ?? '') }}">
                            @if ($errors->has('tax'))
                            <span class="error"role="alert">
                             {{ $errors->first('tax') }}
                            </span>
                        @endif
                        </div>
                 
                <div class="form-group">
                    <label for="">Event Day</label>
                            <input type="text" class="form-control" name="eventday" placeholder="Event Day" value="{{ old('eventday',$data['eventDay'] ?? '') }}">
                            @if ($errors->has('eventday'))
                            <span class="error"role="alert">
                               {{ $errors->first('eventday') }}
                            </span>
                        @endif
                        </div>
                <div class="form-group">
                    <label for="">Fee In %</label>
                            <input type="text" class="form-control" name="fee" placeholder="Fee" value="{{ old('fee',$data['fee'] ?? '') }}">
                            @if ($errors->has('fee'))
                            <span class="error"role="alert">
                               {{ $errors->first('fee') }}
                            </span>
                        @endif
                        </div>
                <div class="form-group">
                    <label for="">Refund Fee In %</label>
                        <input type="text" class="form-control" name="refund" placeholder="Refund Fee In %" value="{{ old('refund',$data['refund'] ?? '') }}">
                        @if ($errors->has('refund'))
                        <span class="error"role="alert">
                            {{ $errors->first('refund') }}
                            </span>
                     @endif
                </div>
                <h4>Company Details</h4>
                <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email',$data['email'] ?? '') }}">
                            @if ($errors->has('email'))
                            <span class="error"role="alert">
                               {{ $errors->first('email') }}
                            </span>
                        @endif
                        </div>
                <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="Phone No." value="{{ old('phone',$data['phoneNo'] ?? '') }}">
                            @if ($errors->has('phone'))
                            <span class="error"role="alert">
                               {{ $errors->first('phone') }}
                            </span>
                        @endif
                        </div>

                <div class="form-group">
                          <textarea class="form-control" placeholder="Address" name="address" id="" cols="100" rows="100" style="margin: 0px; height: 73px;">{{ old('address',$data['address'] ?? '') }}</textarea>
                            @if ($errors->has('address'))
                            <span class="error"role="alert">
                               {{ $errors->first('address') }}
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