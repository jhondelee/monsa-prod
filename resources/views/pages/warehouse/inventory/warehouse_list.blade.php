
@if (!can('warehouse.create'))
    <a href="#" class="btn btn-warning btn-sm add-inventory-item">
    <i class="fa fa-plus">&nbsp;</i>Inventory</a>
@endif   
<div class="hr-line-dashed"></div>
<table class="table table-striped table-hover dataTables-items">
    <thead>
        <tr>

            <th>Item Code</th>
            <th>Description</th>
            <th>On Hand</th>
            <th>Units</th>
            <th>Category</th>
         
        </tr>
    </thead>
    <tbody>
        @foreach($warehouseList as $warehouse)
            <tr>           
                
                <td>
                    <!--@if ($warehouse->picture!="")
                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" src="/item_image/{!! $warehouse->picture !!}"/>
                    @else
                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                    @endif-->
                    {{$warehouse->item_code}}
                </td>
                <td>{{$warehouse->item_description}}</td>
                <td>{{$warehouse->quantity}}</td>
                <td>{{$warehouse->units}}</td>
                <td>{{$warehouse->category}}</td>

            </tr>
        @endforeach                                                           
    </tbody>
</table>