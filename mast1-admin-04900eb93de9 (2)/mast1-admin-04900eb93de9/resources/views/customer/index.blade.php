@extends('layouts.app',['class'=>'customers','activePage'=>'customers'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box">
                <a href="#">View by recent</a>
                <a href="#">View A - Z</a>
                   @if(Session::get('type') == 1)
                        <a href="{{ url('customers-add')}}" class="btn btn-info">Add</a>
                     @else
                     @if(in_array('add',$customer ??  array()))
                       <a href="{{ url('customers-add')}}" class="btn btn-info">Add</a> 
                     @endif 
                    @endif
            </div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
                <input type="hidden" class="url" name="" value="customers-verify">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <table>
                @empty(!$data)
                @php
                    $i =1;
                @endphp
                    @foreach($data as $key => $row)
                <tr class="move">
                    <td> <img src="{{ asset('resources/assets') }}/demo/images/message.png" alt=""> </td>
                    <td>{{ $row->name ?? '' }} {{ $row->surname ?? '' }}</td>
                    <td>{{ $row->email ?? '' }}</td>
                    <td>{{ $row->mobile ?? '' }}</td>
                    <td> <a href="#">View Project</a> </td>
                    <td> <a href="#">View invoices</a> </td>
                    @if(Session::get('type') == 1)
                    <td><a href="{{ url('calendar/'.$row->id.'') }}" class="btn btn-theme ">calendar </a>
                           <a href="{{ url('customers-edit/'.$row->id.'') }}" class="btn btn-theme ">Edit </a> 
                           @if($row->status == 0) 
                           <a href="javascript:void(0);" data-id="{{ $row->id }}" data-taxt="Active"  data-status="approved" class="approved approv">Active</a>    
                           <a href="javascript:void(0);"  data-id="{{ $row->id }}" data-taxt="In-Active"  data-status="decline" class="approved decline">In-Active</a>
                      
                           @else  
                           <a href="javascript:void(0);" @if($row->status == 2)   @else  style="display: none" @endif data-id="{{ $row->id }}" data-taxt="Active"  data-status="approved" class="approved approv">Active</a>
                           <a href="javascript:void(0);" @if($row->status == 1)   @else  style="display: none" @endif data-id="{{ $row->id }}" data-taxt="In-Active"  data-status="decline" class="approved decline">In-Active</a>
                      
                           @endif

                        </td>

                @else
                <td> <a href="{{ url('calendar/'.$row->id.'') }}"  class="btn btn-theme ">calendar </a>   @if(in_array('edit',$customer ??  array())) <a href="{{ url('customers-edit/'.$row->id.'') }}" class="btn btn-theme ">Edit </a> @endif  @if(in_array('delete',$customer ??  array()))  
                    @if($row->status == 0) 
                    <a href="javascript:void(0);" data-id="{{ $row->id }}" data-taxt="Active"  data-status="approved" class="approved approv">Active</a>     
                    <a href="javascript:void(0);"  data-id="{{ $row->id }}" data-taxt="In-Active"  data-status="decline" class="approved decline">In-Active</a>
               
                    @else  
                    <a href="javascript:void(0);" @if($row->status == 2)   @else  style="display: none" @endif data-id="{{ $row->id }}" data-taxt="Active"  data-status="approved" class="approved approv">Active</a>
                    <a href="javascript:void(0);" @if($row->status == 1 || $row->status == 0 )   @else  style="display: none" @endif data-id="{{ $row->id }}" data-taxt="In-Active"  data-status="decline" class="approved decline">In-Active</a>
               
                    @endif @endif </td>

                @endif

                
            </tr>
           @endforeach
       @endempty
  
                 
              </table>
              {{ $data->links() }}
        </div>
        </div>
    </div>
</section>

@endsection