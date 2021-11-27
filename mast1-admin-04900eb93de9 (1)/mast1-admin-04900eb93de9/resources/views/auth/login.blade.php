@extends('layouts.default')

@section('default')
<div class="login_box">
    <a  href="javascript:void(0);" class="login_logo">
        <img src="{{ asset('resources/assets') }}/demo/images/logo.png" alt=""/>
    </a>
      <div class="login_form">
          <h2>Login</h2>
          <form action=" {{  url('authenticate') }} " method="POST">
            @csrf
          <div class="form-group">
              <label>Username/Email</label>
              <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}"/>
              @if ($errors->has('email'))
              <small class="error"role="alert">
                  <strong>{{ $errors->first('email') }}.</strong>
              </small>
           @endif
          </div>
          <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" name="password" placeholder="type your password"/>
              @if ($errors->has('password'))
              <small class="error"role="alert">
                  <strong>{{ $errors->first('password') }}.</strong>
              </small>
             @endif
              <a href="{{ url('forget') }}" class="forgot_link">Forgot password?</a>
          </div>
          <div class="login_btn">

              <button type="submit"  class="btn btn-primary btn-block">Login</button>
          </div>
       </form>
      </div>
      <div class="footer_link"></div>
</div>

@endsection