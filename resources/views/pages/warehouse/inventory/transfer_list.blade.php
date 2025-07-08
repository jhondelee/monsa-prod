

<a href="/transfer/add" class="btn btn-warning">
<i class="fa fa-exchange">&nbsp;</i>Create Transfer Order</a>

<div class="hr-line-dashed"></div>
<table class="table table-striped table-hover dataTables-trasfer" >
    <thead>
        <tr>
            <th>Reference No.</th>
            <th>Source Loc.</th>
            <th>Destination Loc.</th>
            <th>Creator</th>
            <th>Date Transfer</th>
            <th>Status</th>
            <th class="text-center">Action</th>   
        </tr>
    </thead>
    <tbody>
        @foreach($transferLists as $transferList)
            <tr>           
                <td>{{$transferList->reference_no}}</td>
                <td>{{$transferList->source}}</td>
                <td>{{$transferList->destination}}</td>
                <td>{{$transferList->created_by}}</td>
                <td>{{date('m-d-Y', strtotime($transferList->transfer_date))}}</td>
                <td>
                    @if($transferList->status == 'CREATED')
                        <label class="label label-info" >Pending</label> 
                    @else
                        <label class="label label-danger" >Posted</label> 
                    @endif
                </td>
                
                <td class="text-center">
                    @if (!can('transfer.edit'))
                        <div class="btn-group">
                            <a href="{{route('transfer.edit',$transferList->id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                        </div>
                    @endif
                    @if($trasferList->status != 'POSTED')
                        @if (!can('transfer.delete'))
                             <div class="btn-group">
                                <a class="btn-danger btn btn-xs" onclick="confirmDeleteOrder('{{$transferList       ->id}}'); return false;"><i class="fa fa-trash"></i></a>
                            </div>
                        @endif  
                    @endif
                </td>
            </tr>
        @endforeach                                                           
    </tbody>
</table>
