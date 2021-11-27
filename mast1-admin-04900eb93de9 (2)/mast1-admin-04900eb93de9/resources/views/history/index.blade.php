@extends('layouts.app',['class'=>'history','activePage'=>'history'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            
            <div class="view_by_box">
                <form  method="post" action="{{ url('history') }}">
                    @csrf
                <div style="float: left">
                <label for="">Date</label>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span>
                    <input type="hidden" class="start" name="start" value=""> 
                    <input type="hidden" class="end" name="end" value=""> 
                </div>
                </div>
                <div style="float: left;     margin-left: 10px; ">
                <label> Events </label>
                    <div>
                    <input type="text" style="height: auto;" class="form-control" id="txt_search" autocomplete="off" name="eventName" value="{{ Request::get('eventName')}}">
                    <input type="hidden" name="event" id="eventId" value="{{ Request::get('event')}}">
                    </div>
                    <ul id="searchResult"></ul>

                    <div class="clear"></div>
                    <div id="userDetail"></div>
                <div style="clear: both"></div>
            </div>
            <div style="float: left;     margin-left: 10px; ">
                <label for=""> &nbsp</label>
                <button style="border: none;outline:none;cursor: pointer;" class="btn btn-primary">Submit</button>
            </div>
            <div style="float: left;     margin-left: 10px; ">
                <label for=""> &nbsp</label>
                <button type="reset"  style="border: none;outline:none;cursor: pointer;" class=" reset btn btn-primary">Reset</button>
            </div>
            <div style="clear: both"></div>
           
            </form>
            </div>
              
        </div>
        <div class="customer_table">
            <div class="customer_table_list">  
            <table>
                <thead style="text-align: left;">
                    <tr>
                        <td>#</td>
                        <td>Event Name</td>
                        <td>Cutomer Name</td>
                        <td>Transaction Id</td>
                        <td>Stripe Transaction Id</td>
                        <td>Price</td>
                        <td>vat In %</td>
                        <td>Total Amount</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                @if(!empty($data))
                @php
                    $i =1;
                @endphp
                    @foreach($data as $key => $row)
                    <tr class="move">
                        <td> {{ $i++ }} </td>
                         <td>{{ $row->eventName ?? '' }}</td>
                         <td>{{ $row->name ?? '' }}</td>
                         <td>{{ $row->transactionId ?? '' }}</td>
                         <td>{{ $row->stripeTransactionId ?? '' }}</td>
                         <td>{{ "$"." ". number_format($row->amount,2) ?? '' }}</td>
                         <td>{{ $row->vat ?? '' }}</td>
                         <td>{{ "$"." ". number_format($row->totalAmount,2) ?? '' }}</td>
                         @if ($row->status == 1)
                         <td class="Refunded"> <button type="button"  style="border: none;outline:none;cursor: pointer;" name="" data-id="{{  $row->id ?? ''  }}" class="btn btn-primary refund">Refund</button> </td>    
                         @else
                         <td class="Refunded"> Refunded Success </td>    
                         @endif
                     </tr>
                    @endforeach
                @endif
                </tbody>
                 
              </table>
              {{ $data->links() }}
        </div>
        </div>
    </div>


</section>
@endsection