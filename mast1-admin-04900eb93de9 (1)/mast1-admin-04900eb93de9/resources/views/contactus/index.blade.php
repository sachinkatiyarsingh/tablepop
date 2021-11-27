@extends('layouts.app',['class'=>'contactus','activePage'=>'contactus'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box">
              
            </div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
               
            <table>
                <thead style="text-align: left;">
                    <tr>
                        <td>#</td>
                        <td> Name</td>
                        <td>Email</td>
                        <td>Message</td>
                      
                    </tr>
                    </thead>
                    <tbody>
                @empty(!$data)
                @php
                    $i =1;
                @endphp
                    @foreach($data as $key => $row)
                <tr class="move">
                  
                    <td>{{ $i++ }}</td>
                    <td>{{ $row->name ?? '' }}</td>
                    <td>{{ $row->email ?? '' }}</td>
                    <td>{{ $row->message ?? '' }}</td>
                    

               

                
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