@extends('layouts.default')

@section('default')
<div class="login_box">
    <a  href="javascript:void(0);" class="login_logo">
        <img src="{{ asset('resources/assets') }}/demo/images/logo.png" alt=""/>
    </a>
      <div class="login_form">
          <h2>Reset Password</h2>
          <form action="{{  url('reset_password_insert') }}/{{ $token ?? '' }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="password" name="password" class="form-control text-center" placeholder="Password" value="{{ old('password') }}">
                @if ($errors->has('password'))
                    <span class=""role="alert">
                        <small class="error">{{ $errors->first('password') }}.</small>
                    </span>
                 @endif
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control text-center" placeholder="Confirm Password">
                @if ($errors->has('confirm_password'))
                <span class="invalid "role="alert">
                    <small class="error">{{ $errors->first('confirm_password') }}.</small>
                </span>
             @endif
             <a href="{{ url('login') }}" class="forgot_link">Login</a>
            </div>

         
          <div class="login_btn">

              <button type="submit"  class="btn btn-primary btn-block">Login</button>
          </div>
       </form>
      </div>
      <div class="footer_link"></div>
</div>

@endsection