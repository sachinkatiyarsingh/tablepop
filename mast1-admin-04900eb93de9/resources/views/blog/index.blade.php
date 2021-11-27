@extends('layouts.app',['class'=>'themes','activePage'=>'themes'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box">
                @if(Session::get('type') == 1)
                <a href="{{ url('blog-add')}}" class="btn btn-info">Add</a>
             @else
             @if(in_array('add',$blog ??  array()))
             <a href="{{ url('blog-add')}}" class="btn btn-info">Add</a>
             @endif 
            @endif
              
           
             
         
              
             </div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
                <input type="hidden" class="url" name="" value="blog-delete">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <table>
                <thead style="text-align: left;">
                    <tr>
                        <td>#</td>
                        <td>Title</td>
                        <td>Description</td>
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
                         <td>{{ $row->title ?? '' }}</td>
                         <td>{{ $row->description ?? '' }}</td>
                         @if(Session::get('type') == 1)
                         <td> <a href="{{ url('blog-edit/'.$row->id.'') }}" class="btn btn-theme">Edit </a>  <a href="javascript:void(0);" data-id="{{ $row->id }}" type="button" class="delete">Delete</a></td>
                         @endif
                          <td>  @if(in_array('edit',$blog ??  array())) 
                            <a href="{{ url('blog-edit/'.$row->id.'') }}" class="btn btn-theme">Edit </a>  
                            @endif
                            @if(in_array('delete',$blog ??  array())) 
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