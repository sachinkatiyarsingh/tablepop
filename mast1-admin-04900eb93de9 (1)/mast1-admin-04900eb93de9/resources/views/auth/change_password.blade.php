@extends('layouts.app',['class' => 'profile', 'activePage' => 'chaneg-password'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Change-Password</h4>
            <form method="post" action="{{ url('change-password/insert') }}">
                @csrf
                <div class="form-group">
                    <input type="password" class="form-control" name="current_password" placeholder="Current Password" value="{{ old('current_password') }}">
                    @if ($errors->has('current_password'))
                    <span class="error"role="alert">
                        {{ $errors->first('current_password') }}
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="New password" value="{{ old('password') }}">
                    @if ($errors->has('password'))
                    <span class="error"role="alert">
                       {{ $errors->first('password') }}
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password"  class="form-control" placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                    @if ($errors->has('confirm_password'))
                        <span class="error"role="alert">
                           {{ $errors->first('confirm_password') }}
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