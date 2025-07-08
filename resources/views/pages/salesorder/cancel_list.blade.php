  

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
  
           @foreach ($cancel_salesorder as $cancel)

                <tr>
                    <td>{{$cancel->id}}</td>
                    <td>{{$cancel->so_number}}</td>
                    <td>{{ date('d-M-y', strtotime($cancel->so_date))}}</td>
                    <td>{{$cancel->customer}}</td>
                    <td>{{$cancel->sales_agent}}</td>
                    <td class="text-right">{{number_format($cancel->total_sales,2)}}</td>

                    <td class="text-center">
                        <label class="label label-danger" >{{$cancel->status}}</label> 
                    </td>
                    
                    <td class="text-center">

                    @if (!can('salesorder.edit'))
                        <div class="btn-group">
                            <a href="{{ route ('salesorder.edit', $cancel->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>
                    @endif


                    @if (!can('salesorder.delete'))
                        <div class="btn-group">
                            <a class="btn-primary btn btn-xs delete" onclick="confirmDelete('{{$cancel->id}}'); return false;" id="delete-btn"><i class="fa fa-trash"></i></a>
                        </div>
                    @endif


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



