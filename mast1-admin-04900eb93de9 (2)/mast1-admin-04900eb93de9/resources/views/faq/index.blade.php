@extends('layouts.app',['class'=>'faq','activePage'=>'faq'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box">
                @if(Session::get('type') == 1)
                <a href="{{ url('faq-add')}}" class="btn btn-info">Add</a>
             @else
             @if(in_array('add',$faq ??  array()))
             <a href="{{ url('faq-add')}}" class="btn btn-info">Add</a>
             @endif 
            @endif
              
             </div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
                <input type="hidden" class="url" name="" value="faq-delete">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <table>
                <thead style="text-align: left;">
                    <tr>
                        <td>#</td>
                        <td>Question</td>
                        <td>Answer</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                @empty(!$data)
                @php
                    $i =1;
                @endphp
                    @foreach($data as $key => $row)
                    <tr class="move">
                        <td> {{ $i++ }} </td>
                         <td>{{ $row->question ?? '' }}</td>
                         <td>{{ $row->answer ?? '' }}</td>
                         @if(Session::get('type') == 1)
                         <td> <a href="{{ url('faq-edit/'.$row->id.'') }}" class="btn btn-theme">Edit </a>  <a href="javascript:void(0);" data-id="{{ $row->id }}" type="button" class="delete">Delete</a></td>
                         @endif
                         <td>   @if(in_array('edit',$faq ??  array())) 
                            <a href="{{ url('faq-edit/'.$row->id.'') }}" class="btn btn-theme">Edit </a>  
                            @endif
                            @if(in_array('delete',$faq ??  array())) 
                            <a href="javascript:void(0);" data-id="{{ $row->id }}" type="button" class="delete">Delete</a>
                            @endif 
                        </td>
                        
                        
                    </tr>
                    @endforeach
                @endempty
                </tbody>
                 
              </table>
              {{ $data->links() }}
        </div>
        </div>
    </div>
</section>
@endsection