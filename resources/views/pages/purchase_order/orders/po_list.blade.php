  

 <table class="table table-striped table-bordered dataTables-po">
    <thead>
        <tr>
            <th>ID</th>
            <th>PO #</th>
            <th>PO Date</th>
            <th>Supplier</th>
            
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>                                                   
        </tr>
    </thead>
        <tbody>
  
           @foreach ($orders as $order_open)

                <tr>
                    
                    <td>{{$order_open->id}}</td>
                    <td>{{$order_open->po_number}}</td>
                    <td>{{ date('m-d-y', strtotime($order_open->po_date))}}</td>
                    <td>{{$order_open->supplier}}</td>
                    
                    <td class="text-center">
                        @IF($order_open->status == 'NEW')
                            <label class="label label-info" >NEW</label> 
                        @ELSE
                            {{$order_open->status}}
                        @ENDIF
                    </td>
                    <td class="text-center">

                    @if (!can('order.edit'))
                        <div class="btn-group">
                            <a href="{{ route ('order.edit', $order_open->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                        </div>
                    @endif

                    @if (!can('order.cancel'))
                        <div class="btn-group">
                            <a class="btn-primary btn btn-xs delete" onclick="confirmCancel('{{$order_open->id}}'); return false;"><i class="fa fa-ban"></i></a>
                        </div>
                    @endif
                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



