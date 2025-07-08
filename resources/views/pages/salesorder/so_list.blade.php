  

 <table class="table table-striped table-bordered dataTables-po">
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
  
           @foreach ($salesorders as $salesorders_open)

                <tr>
                    
                    <td>{{$salesorders_open->id}}</td>
                    <td>{{$salesorders_open->so_number}}</td>
                    <td>{{ date('m-d-y', strtotime($salesorders_open->so_date))}}</td>
                    <td>{{$salesorders_open->customer}}</td>
                    <td>{{$salesorders_open->sales_agent}}</td>
                    <td>{{$salesorders_open->total_sales}}</td>
                    <td class="text-center">
                        @IF($salesorders_open->status == 'NEW')
                            <label class="label label-danger" >NEW</label> 
                        @ELSE
                            {{$salesorders_open->status}}
                        @ENDIF
                    </td>
                    <td class="text-center">

                    @if (!can('salesorder.edit'))
                        <div class="btn-group">
                            <a href="{{ route ('salesorder.edit', $salesorders_open->id) }}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                        </div>
                    @endif

                    @if (!can('salesorder.cancel'))
                        <div class="btn-group">
                            <a class="btn-primary btn btn-xs delete" onclick="confirmCancel('{{$salesorders_open->id}}'); return false;"><i class="fa fa-ban"></i></a>
                        </div>
                    @endif
                    </td>

                </tr>

             @endforeach
                                                                                                           
        </tbody>
</table> 



