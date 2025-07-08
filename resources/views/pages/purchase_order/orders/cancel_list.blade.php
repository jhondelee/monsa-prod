  

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
  
           @foreach ($cancel_order as $cancel)

                <tr>
                    <td>{{$cancel->id}}</td>
                    <td>{{$cancel->po_number}}</td>
                    <td>{{ date('d-M-y', strtotime($cancel->po_date))}}</td>
                    <td>{{$cancel->supplier}}</td>
                    <td class="text-right">{{number_format($cancel->grand_total,2)}}</td>

                    <td class="text-center">
                        <label class="label label-danger" >{{$cancel->status}}</label> 
                    </td>
                    
                    <td class="text-center">

                    @if (!can('order.edit'))
                        <div class="btn-group">
                            <a href="{{ route ('order.edit', $cancel->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>
                    @endif


                    @if (!can('order.delete'))
                        <div class="btn-group">
                            <a class="btn-primary btn btn-xs delete" onclick="confirmDelete('{{$cancel->id}}'); return false;" id="delete-btn"><i class="fa fa-trash"></i></a>
                        </div>
                    @endif


                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



