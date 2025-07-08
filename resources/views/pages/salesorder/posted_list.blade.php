  

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
  
           @foreach ($posted_salesorder as $posted)

                <tr>
                    <td>{{$posted->id}}</td>
                    <td>{{$posted->so_number}}</td>
                    <td>{{ date('d-M-y', strtotime($posted->so_date))}}</td>
                    <td>{{$posted->customer}}</td>
                    <td>{{$posted->sales_agent}}</td>
                    <td class="text-right">{{number_format($posted->total_sales,2)}}</td>
                    <td class="text-center">
                        <label class="label label-warning" >{{$posted->status}}</label>
                        @if ($posted->inventory_deducted == 1)
                        <label class="label label-danger" >DEDUCTED</label>
                        @endif
                    </td>
          
                    <td class="text-center">

                        <div class="btn-group">
                            <a href="{{ route ('salesorder.edit', $posted->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



