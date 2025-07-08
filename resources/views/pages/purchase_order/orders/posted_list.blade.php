  

 <table class="table table-striped table-bordered dataTables-po" data-toggle="dataTable" data-form="deleteForm" >
    <thead>
        <tr>
            <th>ID</th>
            <th>PO #</th>
            <th>PO Date</th>
            <th>Supplier</th>
            <th>Total Amount</th>   
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>                                                   
        </tr>
    </thead>
        <tbody>
  
           @foreach ($posted_order as $posted)

                <tr>
                    <td>{{$posted->id}}</td>
                    <td>{{$posted->po_number}}</td>
                    <td>{{ date('d-M-y', strtotime($posted->po_date))}}</td>
                    <td>{{$posted->supplier}}</td>
                    <td class="text-right">{{number_format($posted->grand_total,2)}}</td>
                    <td class="text-center">
                        <label class="label label-warning" >{{$posted->status}}</label> 
                    </td>
          
                    <td class="text-center">

                        <div class="btn-group">
                            <a href="{{ route ('order.edit', $posted->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



