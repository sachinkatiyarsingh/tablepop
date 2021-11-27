@extends('layouts.app',['class' => 'event-types', 'activePage' => 'event-types'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('event-types-add') }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" name="event" placeholder="Event Type" value="{{ old('event') }}">
                    @if ($errors->has('event'))
                    <span class="error"role="alert">
                        {{ $errors->first('event') }}.
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