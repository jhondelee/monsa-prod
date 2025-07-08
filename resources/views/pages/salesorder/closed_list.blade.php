  

 <table class="table table-striped table-bordered dataTables-po" data-toggle="dataTable" data-form="deleteForm" >
    <thead>
        <tr>
            <th>ID</th>
            <th>SO #</th>
            <th>SO Date</th>
            <th>Customer</th>
            <th>Sales Agent</th>
            <th>Sales Total</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>                                                   
        </tr>
    </thead>
        <tbody>
  
           @foreach ($closed_salesorder as $closed)

                <tr>
                    <td>{{$closed->id}}</td>
                    <td>{{$closed->so_number}}</td>
                    <td>{{ date('m-d-y', strtotime($closed->so_date))}}</td>
                    <td>{{$closed->customer}}</td>
                    <td>{{$closed->sales_agent}}</td>
                    <<td class="text-right">{{number_format($closed->total_sales,2)}}</td>
                    <td class="text-center">
                        <label class="label label-warning" >{{$closed->status}}</label> </td>
                    <td class="text-center">

                        <div class="btn-group">
                            <a href="{{ route ('salesorder.edit', $closed->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



