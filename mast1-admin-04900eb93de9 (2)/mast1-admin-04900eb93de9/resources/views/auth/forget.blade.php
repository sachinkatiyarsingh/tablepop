@extends('layouts.default')

@section('default')
<div class="login_box">
    <a  href="javascript:void(0);" class="login_logo">
        <img src="{{ asset('resources/assets') }}/demo/images/logo.png" alt=""/>
    </a>
      <div class="login_form">
          <h2>Forgot Password?</h2>
          <small>Enter your user account's verified email address and we will send you a password reset link.</small>
          <form action=" {{  url('forget-insert') }} " method="POST">
            @csrf
          <div class="form-group">
              <label>Email</label>
              <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}"/>
              @if ($errors->has('email'))
              <small class="error"role="alert">
                  <strong>{{ $errors->first('email') }}.</strong>
              </small>
           @endif
          </div>
          <div class="form-group">
            <a href="{{ url('login') }}" class="forgot_link">Login</a>
          </div>
          <div class="login_btn">
              <button type="submit"  class="btn btn-primary btn-block">Submit</button>
          </div>
       </form>
      </div>
      <div class="footer_link"></div>
</div>

@endsection