@extends('layouts.app')

@section('pageTitle','Dashboard')

@section('content')
        
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">{{$sales_monthyear}}</span>
                        <h5>Sales</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{number_format($sales_total)}}</h1>
                        <div class="stat-percent font-bold text-success">{{number_format($sales_percent)}}% <i class="fa fa-bolt"></i></div>
                        <small>Total Sales</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">{{$orders_monthyear}}</span>
                        <h5>Orders</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{number_format($order_total)}}</h1>
                        <div class="stat-percent font-bold text-info">{{number_format($order_percent)}}% <i class="fa fa-level-up"></i></div>
                        <small>New orders</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">{{$monthtoday}}</span>
                        <h5>Sales & Orders</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="no-margins">{{number_format($current_sales)}}</h1>
                                <div class="font-bold text-navy">0% <i class="fa fa-level-up"></i> <small>Sales</small></div>
                            </div>
                            <div class="col-md-6">
                                <h1 class="no-margins">{{number_format($current_orders)}}</h1>
                                <div class="font-bold text-navy">0% <i class="fa fa-level-up"></i> <small>Orders</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Daily Sales</h5>
                        <div class="ibox-tools">
                            <span class="label label-primary">Updated  {{$datetoday}}</span>
                        </div>
                    </div>
                    <div class="ibox-content no-padding">
                        <div class="flot-chart m-t-lg" style="height: 55px;">
                            <div class="flot-chart-content" id="flot-chart1"></div>
                        </div>
                    </div>  

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div>
                                        <span class="pull-right text-right">
                                        <small>Average value of sales in the past month in: <strong>PH</strong></small>
                                            <br/>
                                            All sales: 162,862
                                        </span>
                            <h3 class="font-bold no-margins">
                                Half-year revenue margin
                            </h3>
                            <small>Sales marketing.</small>
                        </div>

                        <div class="m-t-sm">

                            <div class="row">
                                <div class="col-md-8">
                                    <div>
                                        <canvas id="lineChart" height="114"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <ul class="stat-list m-t-lg">
                                        <li>
                                            <h2 class="no-margins">2,346</h2>
                                            <small>Total orders in period</small>
                                            <div class="progress progress-mini">
                                                <div class="progress-bar" style="width: 48%;"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins ">4,422</h2>
                                            <small>Orders in last month</small>
                                            <div class="progress progress-mini">
                                                <div class="progress-bar" style="width: 60%;"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <div class="m-t-md">
                            <small class="pull-right">
                                <i class="fa fa-clock-o"> </i>
                                Update on 16.07.2015
                            </small>
                            <small>
                                <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over 50,000.
                            </small>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-warning pull-right">As of {{$datetoday}}</span>
                        <h5>TOP 3 Sales Team This Week</h5>
                    </div>
                    @foreach($gettopsales as $gettopsale)
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-xs-4">
                                <small class="stats-label">Team Leader</small>
                                <h4>{{$gettopsale->emp_name}}</h4>
                            </div>

                            <div class="col-xs-4">
                                <small class="stats-label">Sales</small>
                                <h4>{{number_format($gettopsale->sales)}}</h4>
                            </div>
                            <div class="col-xs-4">
                                <small class="stats-label">Last week</small>
                                <h4>{{number_format($gettopsale->last_sales)}}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="row">

        <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title" >
            <div id="inven-title"></div>
            <div class="ibox-tools">
                  <div class="form-group">
                    <div>
                        <label> <input type="radio" checked="" value="In Stock" id="optionsRadios1" name="optionsRadios"> In Stock </label>
                        &nbsp;&nbsp;
                        <label> <input type="radio" checked="" value="Reorder" id="optionsRadios2" name="optionsRadios"> Reorder</label>
                         &nbsp;&nbsp;
                        <label> <input type="radio" checked="" value="Critical" id="optionsRadios3" name="optionsRadios"> Critical</label>
                
                    </div>
                </div>                           
            </div>
        </div>
        
        <div class="ibox-content">
    
            <div class="table-responsive">
                <table class="table table-striped data-table-inventory" id="data-table-inventory">
                    <thead>

                    </thead>
                    <tbody>
                               
                    </tbody>
                </table>
            </div>

     
        </div>

        </div>

        <!-- Inactive Customer-->

        <div class="row">

        <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Inactive Customers <small>(In 2 Weeks, Follow up)-(In 1 Month, No Transactions)-(More than a Month, Lost)</small></h5>
        </div>
        <div class="ibox-content">

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>

                        <th>Customer Name </th>
                        <th>SO Date </th>
                        <th>No Transactions</th>
                        <th>Status </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($getcustomerlist as $getcustomer)
                    <tr>
                        <td>{{$getcustomer->cs_name}}</td>
                        <td>{{$getcustomer->so_date}}</td>
                        <td>{{$getcustomer->Last_trans}}</td>
                        <td>@IF($getcustomer->trans_stat == 'No Transaction')
                            <span class="label label-success pull-left">{{$getcustomer->trans_stat}}</span>
                            @ELSEIF ($getcustomer->trans_stat == 'Follow Up')
                            <span class="label label-info pull-left">{{$getcustomer->trans_stat}}</span>
                            @ELSE
                            <span class="label label-warning pull-left">{{$getcustomer->trans_stat}}</span>
                            @ENDIF
                        </td>
                    </tr>
                    @endforeach
                                
                    </tbody>
                </table>
            </div>

        </div>
        </div>
        </div>
        </div>

        </div>


        </div>
 </div>


@endsection


@section('scripts')
   
    <!-- Flot -->
    <script src="/js/plugins/flot/jquery.flot.js"></script>
    <script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="/js/plugins/flot/curvedLines.js"></script>

    <!-- Peity -->
    <script src="/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/js/demo/peity-demo.js"></script>

    <!-- Jvectormap -->
    <script src="/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Sparkline -->
    <script src="/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="/js/plugins/chartJs/Chart.min.js"></script>
    <script src="/js/plugins/toastr/toastr.min.js"></script>
    <script>
        
    $(document).ready(function(){
        $('#inven-title').append("<h5>Critical Items</h5>");
            $.ajax({
                url:  '{{ url('/getinventorystatus') }}',
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}",
                id: 'Critical'}, 
                success:function(results){
                     
                $('#data-table-inventory').DataTable({
                                        destroy: true,
                                        pageLength: 100,
                                        responsive: true,
                                        data: results,
                                        autoWidth: true,
                                        dom: '<"html5buttons"B>lTfgitp',
                                        buttons: [],
                                        fixedColumns: true,
                                        columns: [
                                            {data: 'id', title: 'Id'},  
                                            {data: 'name', title: 'Name'},    
                                            {data: 'description', title: 'Description'},
                                            {data: 'units', title: 'Units'},
                                            {data: 'onhand_quantity', title: 'Quantity'},
                                            {data: 'status', title: 'Status',
                                                render: function(data, type, row){
                                                    if(row.status=='In Stock'){
                                                        return '<label class="label label-success" >In Stock</label>  '
                                                    } 
                                                    if(row.status=='Reorder'){
                                                        return '<label class="label label-warning" >Reorder</label>  '
                                                    }  
                                                    if(row.status=='Critical'){
                                                        return '<label class="label label-danger" >Critical</label>  ';
                                                    }    
                                                }
                                            },
                        
                                 
                                        ],
                                    })

                }
            });

    });

     $("input[name='optionsRadios']").click(function () {
          var _rdVaule = $("input[name='optionsRadios']:checked").val();
          $('#inven-title').empty();
           $('#inven-title').append("<h5>"+ _rdVaule +" Items</h5>");

            $.ajax({
                url:  '{{ url('/getinventorystatus') }}',
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}",
                id: _rdVaule}, 
                success:function(results){
                     
                $('#data-table-inventory').DataTable({
                                        destroy: true,
                                        pageLength: 100,
                                        responsive: true,
                                        data: results,
                                        autoWidth: true,
                                        dom: '<"html5buttons"B>lTfgitp',
                                        buttons: [],
                                        fixedColumns: true,
                                        columns: [
                                             {data: 'id', title: 'Id'},  
                                            {data: 'name', title: 'Name'},    
                                            {data: 'description', title: 'Description'},
                                            {data: 'units', title: 'Units'},
                                            {data: 'onhand_quantity', title: 'Quantity'},
                                            {data: 'status', title: 'Status',
                                                render: function(data, type, row){
                                                    if(row.status=='In Stock'){
                                                        return '<label class="label label-success" >In Stock</label>  '
                                                    } 
                                                    if(row.status=='Reorder'){
                                                        return '<label class="label label-warning" >Reorder</label>  '
                                                    }  
                                                    if(row.status=='Critical'){
                                                        return '<label class="label label-danger" >Critical</label>  ';
                                                    }    
                                                }
                                            },
                        
                                 
                                        ],
                                    })

                }
            });

       
     });

    /*
        $(document).ready(function() {
            let  _salesVal = [];
            var _val;
            var d1;
            var d2;

                $.ajax({
                    url:  '{{ url('/getsalesmonthly') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}"}, 
                    success:function(results){

             
                        alert(results[0].Total_Sales);

                        var d1 = [[1262304000000,  results[0].Total_Sales ], [1264982400000,  results[1].Total_Sales], [1267401600000,  results[2].Total_Sales], [1270080000000,  results[3].Total_Sales], [1272672000000,  results[4].Total_Sales], [1275350400000,  results[5].Total_Sales], [1277942400000,  results[6].Total_Sales], [1280620800000,  results[7].Total_Sales], [1283299200000,  results[8].Total_Sales], [1285891200000,  results[9].Total_Sales], [1288569600000,  results[10].Total_Sales], [1291161600000,  results[11].Total_Sales]
                        ];
                  
                        var d2 =  [[1262304000000,  results[0].Total_Sales ], [1264982400000,  results[1].Total_Sales], [1267401600000,  results[2].Total_Sales], [1270080000000,  results[3].Total_Sales], [1272672000000,  results[4].Total_Sales], [1275350400000,  results[5].Total_Sales], [1277942400000,  results[6].Total_Sales], [1280620800000,  results[7].Total_Sales], [1283299200000,  results[8].Total_Sales], [1285891200000,  results[9].Total_Sales], [1288569600000,  results[10].Total_Sales], [1291161600000,  results[11].Total_Sales]
                        ]; 
                        
                     }


            var data1 = [
                { label: "Data 1", data: d1, color: '#749189'},
                { label: "Data 2", data: d2, color: '#749169' }
            ];
            $.plot($("#flot-chart1"), data1, {
                xaxis: {
                    tickDecimals: 0
                },
                
                series: {
                    lines: {
                        show: true,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 1
                            }, {
                                opacity: 1
                            }]
                        },
                    },
                    points: {
                        width: 0.1,
                        show: false
                    },
                },
                grid: {
                    show: false,
                    borderWidth: 0
                },
                legend: {
                    show: false,
                }
            });

            var lineData = {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Septr","Oct","Nov","Dec"],
                datasets: [
                    {
                        label: "Revenue",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [55, 48, 60, 39, 56, 37, 30,0]
                    },
                    {
                        label: "Margin",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 40, 51, 36, 25, 40,0]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});

                         
                });            

        });
        */
    </script>
    
@endsection