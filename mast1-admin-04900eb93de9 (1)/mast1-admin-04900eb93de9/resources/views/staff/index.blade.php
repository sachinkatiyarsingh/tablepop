@extends('layouts.app',['class'=>'staff','activePage'=>'staff'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box">
                <a href="#">View by recent</a>
                <a href="#">View A - Z</a>
                <a href="{{ url('staff-add')}}" class="btn btn-info">Add</a>
             </div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
                <input type="hidden" class="url" name="" value="staff-delete">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <table>
                <thead style="text-align: left;">
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Email</td>

                        <td>Mobile No.</td>
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
                        <td> <img src="{{ asset('resources/assets') }}/demo/images/message.png" alt=""> </td>
                         <td>{{ $row->name ?? '' }}</td>
                         <td>{{ $row->email ?? '' }}</td>
                         <td>{{ $row->mobile ?? '' }}</td>
                         <td> <a href="{{ url('staff-edit/'.$row->id.'') }}" class="btn btn-theme ">Edit </a>  <a href="javascript:void(0);" data-id="{{ $row->id }}" type="button" class="delete">Delete</a></td>
                         
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