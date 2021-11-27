@extends('layouts.app',['class'=>'questionnaire','activePage'=>'questionnaire'])
@section('content')

<section class="project_view">
    <div class="container">
        <div class="project_view_section">
            <div class="project_view_inner">
                <div class="project_view_box">
                    <div class="box_left">
                        <div class="thumbnail" style="background: url( {{  !empty($eventData['image']) ? $eventData['image'] : asset('resources/assets/demo/images/thumbnail.png')  }} );">
                        </div>
                    </div>
                    <div class="box_right">
                        <h3>{{ $eventData['eventName'] }} </h3>
                        <ul>
                            <li>
                                <label>Customer Name</label>
                                <p> {{ $eventData['name'] }} </p>
                            </li>
                            <li>
                                <label>Number of guests</label>
                                <p>{{ $eventData['guest'] }}</p>
                            </li>
                            <li>
                                <label>Event address</label>
                                <p> {{ $eventData['address'] }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="project_view_control">
                    <div class="send_control">
                        <a href="{{ url('message') }}" class="btn btn-primary" >Send Message</a>
                    </div>
                    <!-- <div class="upload_control">
                        <a href="javascript:void()" class="btn btn-primary">Upload contract</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="invoice_section">
    <div class="container" >
        <div class="invoice_inner">
            <div class="invoice_box">
                <div class="top_btn">
                    <!--a href="javascript:void()" class="btn btn-primary">Create invoice</a-->
                </div>
                <div class="invoice_detail">
                    <ul>
                        <li>
                            <label>Date of invoice:</label>
                            <span></span>
                        </li>
                        <li>
                            <label>Project due date: </label>
                            <span></span>
                        </li>
                        <li>
                            <label>Project start date:</label>
                            <span>{{ $eventData['projectStartDate'] ?? ''}}</span>
                        </li>
                      
                    </ul>
                    <div class="form-group">
                        <label>Project Description</label>
                        <p class="description"> Level of Service : {{ Helper::eventType($eventData['levelOfService']) }}/
                                                                  {{ Helper::eventType($eventData['levelOfServicePlanningType']) }}
                          <br>
                          Budget :  {{ $eventData['budgetRangeStart'] }} - {{ $eventData['budgetRangeEnd'] }}
                          <br>
                          Theme Event :  {{ $eventData['themeEvent'] }}
                          <br>
                          Party Planner :  {{ Helper::eventType($eventData['partyPlanner']) }}
                          <br>
                         Event Planning :  {{ Helper::eventType($eventData['eventPlanning']) }}
                          <br>
                          Party Planing Service :  {{ Helper::eventType($eventData['partyPlaningService']) }}
                          <br>
                         Venue :  {{ Helper::eventType($eventData['vennu']) }}
                                                                 </p>
                    </div>
                    <ul>
                        <li class="border">
                            <label>Price:</label>
                            <span>{{ $eventData['amount']}}</span>
                        </li>
                        <li class="border">
                            <label>VAT 10%:</label>
                            <span>{{ $eventData['vat']}}</span>
                        </li>
                        <li class="border">
                            <label>Grand Total:</label>
                            <span>{{ $eventData['totalAmount']}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="milestone_payment">
    <div class="container">
        <div class="milestone_payment_inner">
            <div class="milestone_payment_box">
                <h3>Milestone payment </h3>

                <div class="milestone_steps">
                    <ul>
                       @if(!empty( $eventData['milestones']))
                           
                      @foreach($eventData['milestones'] as $key => $item)
                         @if ($item['isCompleted']=='1')
                         @php
                         $class = 'active';
                    @endphp
                         @elseif($item['status'] == 1)
                             @php
                                  $class = 'active';
                             @endphp
                        @else
                        @php
                        $class = '';
                   @endphp
                         @endif
                     
                        <li class='milestomeapproved {{ $class }}'>
                            <span class="status_check {{ $class}}"></span>
                            <h4>{{  $item['name'] }}</h4>
                            <p>{{ $item['description']}}</p>
                            <p>{{ $item['amount']}}</p>
                            @if ($item['isCompleted']=='1')
                                <p>Completed</p>
                            @else
                                
                           
                            <p class="status">{{  ($item['status'] == 1) ? 'Completed' : 
                                (($item['status']== 2) ? 'Approve Milestone ' : (($item['status'] == 3) ? 'Paid':'Ongoing') )
                                }}</p>
                            <!--span>{{ $item['isCompleted']=='1' ? 'Completed':'Ongoing'}}</span-->
                            @if ($item['status']== 2)
                            <button type="button" data-id="{{ $item['id'] }}" class="btn btn-primary milestomePayment">PayMent</button> 
                            @endif
                           
                            @endif
                          </li>
                          @endforeach
                          @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content_block">
    <div class="container">
        <h3>Transactions</h3>
        <div class="customer_table">
            <div class="customer_table_list">
              
           
          
                <table class="dataTable">
                <thead style="text-align: left;">
                    <tr>
                        <td class="active"><h4>Sr No.</h4></td>
                        <td> <h4>Customer Name</h4></td>
                        <td><h4>Event Name</h4></td>
                        <td><h4>Price</h4></td>
                        <td><h4>Vat</h4></td>
                        <td><h4>Total Amount</h4></td>
                      
                        <td><h4>TransactionId</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Action</h4></td>
                      
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($TransactionData))
                        @php
                            $i = 1;
                        @endphp
                            @foreach($TransactionData as $key => $value)
                        <tr>
                                  <td>{{ $i++ }}</td>
                                  <td>{{  $value['name'] ?? ''  }} {{  $value['surname'] ?? ''  }}</td>
                                  <td>{{ $eventData['eventName'] }}</td>
                                  <td>{{ $value['amount'] }}</td>
                                  <td>{{ $value['vat'].'%' ?? '' }}</td>
                                  <td>{{ $value['totalAmount'] }}</td>
                                  <td>{{ $value['transactionId'] }}</td>
                                  <td>{{ date("D,L,M Y", strtotime($value['created_at']) ) ?? '' }}</td>
                                  <td>{{ ($value['status'] == 1) ? 'success' : (($value['status'] == 2 ? 'Fail' : ($value['status'] == 3 ? 'Custom Plan Payment' : ''))) }}</td>
                                </tr>
                            @endforeach 
                        @endif
                      
                   
                    </tbody>
              </table>
           
        
            
        </div>
        </div>
    </div>
</section>
@endsection