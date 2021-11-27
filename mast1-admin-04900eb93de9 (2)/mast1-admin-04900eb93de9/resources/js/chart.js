week = true;
var month = false;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(){
    $('.dashboardweek').click(function(){
        week = true;
        month = false;
    })
})
 $(document).on('click','.dashboardmonth',function(){
    month = true;
    week = false;   
})


if(week){
    $.ajax({ 
        type: 'POST',
        url:  base_url+"/dashboardweek",
       dataType:'json',
       success: function(output) {
         $('.totalRevenueThisWeeek').html(output.totalRevenueThisWeeek);  
         $('.plannerInPersonWeekCount').html(output.plannerInPersonWeekCount);  
         $('.plannerInnVirtualWeekCount').html(output.plannerInnVirtualWeekCount);  
         $('.eventWeekIninPerson').html(output.eventWeekIninPerson);  
         $('.eventWeekonline').html(output.eventWeekonline);  
       // console.log(output.eventChartWeek);
  
var barCahrt = new Chart(chartContainerweek, {
        type: 'bar',
        data: {
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            datasets: [
                {
                    label: "Events",
                    data: output.eventChartWeek,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                      ],
                    borderWidth: "1",
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                      ],
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var lineCahrt = new Chart(linechartContainerweekcustomer, {
        type:"line",
        data:{
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        datasets:[{
                        "label":"Customer",
                        "data":output.CustomerChartWeek,
                        "fill":false,
                        "borderColor":"rgb(108, 2, 2)",
                        "lineTension":0.1
                    }]
    },options:{}});

    var lineCahrt = new Chart(linechartContainerweekplanner, {
        type:"line",
        data:{
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        datasets:[{
                        "label":"Planner",
                        "data":output.plannerChartWeek,
                        "fill":false,
                        "borderColor":"rgb(108, 2, 2)",
                        "lineTension":0.1
                    }]
    },options:{}});


    var lineCahrt = new Chart(linechartContainerweekvendor, {
        type:"line",
        data:{
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        datasets:[{
                        "label":"Vendor",
                        "data":output.vendorChartWeek,
                        "fill":false,
                        "borderColor":"rgb(108, 2, 2)",
                        "lineTension":0.1
                    }]
    },options:{}});

}
}); 
}
 $(document).on('click','.dashboardmonth',function(){
    month = true;
    week = false;   
if(month){
    
   
    $.ajax({ 
        type: 'POST',
        url:  base_url+"/dashboardmonth",
       dataType:'json',
       success: function(response) {
        $('.totalRevenueThisMonth').html(response.totalRevenueThisMonth);  
        $('.plannerInPersonMonthCount').html(response.plannerInPersonMonthCount);  
        $('.plannerInVirtualMonthCount').html(response.plannerInVirtualMonthCount);  
        $('.eventmonthIninPerson').html(response.eventmonthIninPerson);  
        $('.eventmonthonline').html(response.eventmonthonline); 




    var barCahrt = new Chart(chartContainermonth, {
            type: 'bar',
            data: {
                labels:  ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: "Events",
                        data: response.eventChartMonth,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                          ],
                        borderWidth: "1",
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                          ],
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    
        var lineCahrt = new Chart(linechartContainermonthcustomer, {
            type:"line",
            data:{
                labels:  ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets:[{
                            "label":"Customer",
                            "data":response.CustomerChartMonth,
                            "fill":false,
                            "borderColor":"rgb(108, 2, 2)",
                            "lineTension":0.1
                        }]
        },options:{}});
    
        var lineCahrt = new Chart(linechartContainermonthplanner, {
            type:"line",
            data:{
                labels:  ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets:[{
                            "label":"Planner",
                            "data":response.plannerChartMonth,
                            "fill":false,
                            "borderColor":"rgb(108, 2, 2)",
                            "lineTension":0.1
                        }]
        },options:{}});
    
    
        var lineCahrt = new Chart(linechartContainermonthvendor, {
            type:"line",
            data:{
                labels:  ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets:[{
                            "label":"Vendor",
                            "data":response.vendorChartWeek,
                            "fill":false,
                            "borderColor":"rgb(108, 2, 2)",
                            "lineTension":0.1
                        }]
        },options:{}});
    
  
}
});
}
}) 