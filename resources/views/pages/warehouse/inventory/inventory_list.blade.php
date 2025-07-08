
    @if (!can('inventory.create'))
        <a href="#" class="btn btn-warning btn-sm add-inventory-item">
        <i class="fa fa-plus">&nbsp;</i>Inventory</a>

    @endif   
     <!--<a href="{{route('inventory.print')}}" class="btn btn-info btn-sm print-inventory-item">
        <i class="fa fa-print">&nbsp;</i>Print Warehouse</a>
    -->
         <a href="{{route('inventory.print-inventory')}}" class="btn btn-info btn-sm print-inventory-item">
        <i class="fa fa-print">&nbsp;</i>Print Inventory</a>
    <div class="hr-line-dashed"></div>
    <table class="table table-striped table-hover dataTables-items">
        <thead>
            <tr>

                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Units</th>
                <th>On Hand</th>
                <th>SRP</th>
                <th>Location</th>
                <th>Status</th>
                <th class="text-center">Action</th>  

            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
                <tr>           
                    <td>{{$inventory->id}}</td>
                    <td>
                        @if ($inventory->picture!="")
                            <img class="img-thumbnail img-responsive text-center"  width="56" height="56" src="/item_image/{!! $inventory->picture !!}"/>
                        @else
                            <img class="img-thumbnail img-responsive text-center"  width="56" height="56" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                        @endif
                        {{$inventory->name}}
                    </td>
                    <td>{{$inventory->description}}</td>
                    <td>{{$inventory->units}}</td>
                    <td>{{$inventory->unit_quantity}}</td>
                    <td>{{$inventory->srp}}</td>
                    <td>{{$inventory->location}}</td>
                    <td>@if ($inventory->status =="In Stock")
                            <label class="label label-success" >
                        @elseif ($inventory->status =="Reorder")
                            <label class="label label-warning" >
                        @elseif ($inventory->status =="Critical")
                            <label class="label label-danger" >
                        @endif
                            {{$inventory->status}}</label></td>
                    <td class="text-center">
                            <div class="btn-group">
                                <a href="{{route('inventory.show',$inventory->item_id)}}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                            </div>
                    </td>
                </tr>
            @endforeach                                                           
        </tbody>
    </table>



