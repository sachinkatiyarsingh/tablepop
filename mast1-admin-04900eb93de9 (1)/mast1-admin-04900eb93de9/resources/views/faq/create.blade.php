@extends('layouts.app',['class' => 'faq', 'activePage' => 'faq'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="inner-content form-box">
            <h4>Add</h4>
            <form method="post" action="{{ url('faq-add') }}">
                @csrf
                
                <div class="form-group">
                    <label for="">Question</label>
                    <input type="text" class="form-control" name="question" placeholder="Question" value="{{ old('question') }}">
                    @if ($errors->has('question'))
                    <span class="error"role="alert">
                        {{ $errors->first('question') }}.
                    </span>
                @endif
                </div>
                <div class="form-group">
                    <label for="">Answer</label>
                     <textarea class="form-control" name="answer" id="editor1" placeholder="Answer" cols="30" rows="10">{{ old('answer') }}</textarea>
                 
                    @if ($errors->has('answer'))
                    <span class="error"role="alert">
                        {{ $errors->first('answer') }}.
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