@extends('layouts.app',['class'=>'questionnaire','activePage'=>'questionnaire'])
@section('content')
<section class="content_block">
    <div class="container">
        <div class="view_by_wrap">
            <div class="view_by_box" style="float: left"  id="tabs">
                <a href="javascript:void(0)" class="tab tab-active" data-id="tab1">All </a>
                <a href="javascript:void(0)" data-value="pendding" class="tab" data-id="tab2">New Events list</a>
                <a href="javascript:void(0)" data-value="ongoing" class="tab" data-id="tab3">Ongoing</a>
                <a href="javascript:void(0)" data-value="finish" class="tab" data-id="tab4">Finished</a>
               
             </div>
             <div class="view_by_box" style="float: right ">
                <ul class="dots">
                    <li> <span class="penddingdot"></span>  Pendding</li>
                    <li> <span class="ongoingdot"></span>  Ongoing</li>
                    <li> <span class="finishdot"></span>  Finished</li>
                </ul>
             </div>
             <div style="clear: both"></div>
        </div>
        <div class="customer_table">
            <div class="customer_table_list">
                <input type="hidden" class="url" name="" value="staff-delete">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="QuestionnaireData" id="tab1">
            <table class="Questionnairetab1"  style="width: 100%;" >
                <thead style="text-align: left;">
                    <tr>
                        <td class="active"><h4>Sr No.</h4></td>
                        <td> <h4> Event Name</h4></td>
                        <td><h4>Location</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Mobile No.</h4></td>
                        <td><h4>Action</h4></td>
                        <td></td>
                        
                    </tr>
                    </thead>
                    <tbody>
                      
                  
                   
                    </tbody>
              </table>
            </div>
            <div style="display: none"  class="QuestionnaireData" id="tab2">
                 <table class="Questionnairetab2"  style="width: 100%;" >
                <thead style="text-align: left;">
                    <tr>
                        <td class="active"><h4>Sr No.</h4></td>
                        <td> <h4> Event Name</h4></td>
                        <td><h4>Location</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Mobile No.</h4></td>
                        <td><h4>Action</h4></td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                      
                  
                   
                    </tbody>
              </table></div>
            <div style="display: none"  class="QuestionnaireData" id="tab3"> <table class="Questionnairetab3"  style="width: 100%;" >
                <thead style="text-align: left;">
                    <tr>
                        <td class="active"><h4>Sr No.</h4></td>
                        <td> <h4> Event Name</h4></td>
                        <td><h4>Location</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Mobile No.</h4></td>
                        <td><h4>Action</h4></td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                      
                  
                   
                    </tbody>
              </table></div>
            <div style="display: none"  class="QuestionnaireData" id="tab4"> <table class="Questionnairetab4"  style="width: 100%;" >
                <thead style="text-align: left;">
                    <tr>
                        <td class="active"><h4>Sr No.</h4></td>
                        <td> <h4> Event Name</h4></td>
                        <td><h4>Location</h4></td>
                        <td><h4>Date</h4></td>
                        <td><h4>Mobile No.</h4></td>
                        <td><h4>Action</h4></td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                      
                  
                   
                    </tbody>
              </table></div>
        
            
        </div>
        </div>
    </div>
</section>

@endsection