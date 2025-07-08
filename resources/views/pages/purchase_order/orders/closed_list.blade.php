  

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
  
           @foreach ($closed_order as $closed)

                <tr>
                    <td>{{$closed->id}}</td>
                    <td>{{$closed->po_number}}</td>
                    <td>{{ date('m-d-y', strtotime($closed->po_date))}}</td>
                    <td>{{$closed->supplier}}</td>
                    <td class="text-right">{{number_format($closed->grand_total,2)}}</td>
                    <td class="text-center">{{$closed->status}}</td>
                    <td class="text-center">

                        <div class="btn-group">
                            <a href="{{ route ('purchase_order.edit', $closed->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



