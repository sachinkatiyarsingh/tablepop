@extends('layouts.app',['class' => 'themes', 'activePage' => 'themes'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Edit</h4>
            <form method="post" action="{{ url('themes-edit') }}/{{ $data['id'] ?? '' }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" name="theme" placeholder="Theme" value="{{ old('theme',$data['name'] ?? '') }}">
                    @if ($errors->has('theme'))
                    <span class="error"role="alert">
                        {{ $errors->first('theme') }}.
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