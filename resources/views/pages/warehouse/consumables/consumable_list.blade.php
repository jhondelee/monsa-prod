@if (!can('consumables.create'))
                         
    <a href="#" class="btn btn-warning btn-sm add-consumable-item"  id="add-product"><i class="fa fa-plus">&nbsp;</i> Consumable</a> 

    <a href="{{route('consumables.print')}}" class="btn btn-info btn-sm print-item"  id="btn-print"><i class="fa fa-print">&nbsp;</i>Print</a>
                                
@endif
   <div class="hr-line-dashed"></div>
    <table class="table table-bordered table-hover dataTables-consumable-items" id="dataTables-consumable-items">
        
        <thead>

            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>  
            </tr>

        </thead>
                                        
        <tbody>

            @foreach($consumables as $consumable)
                                            
                <tr>
                    
                    <td>{{$consumable->id}}</td>
                    
                    <td>
                        
                        @if ($consumable->picture!="")

                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" src="/item_image/{!! $consumable->picture !!}"/>

                        @else

                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                        
                        @endif

                        {{$consumable->description}}
                    </td>
                    <td>{{$consumable->units}}</td>
                    <td>{{$consumable->onhand_quantity}}</td>   
                    <td>{{$consumable->location}}</td>
                    <td class="text-center"><label class="label label-warning " >{{$consumable->status}}</label></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('consumables.show',$consumable->item_id)}}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                        </div>
                        <div class="btn-group">

                            <a href="#" class="btn-success btn btn-xs add-ticket-request" id="add-ticket-request"
                            data-id="{{$consumable->id}}"
                            data-name="{{$consumable->description}}"
                            data-units="{{$consumable->units}}">
                            <i class="fa fa-ticket"></i></a>
                            
                        </div>
                    </td>

                </tr>  
            @endforeach
                                                                               
        </tbody>

    </table>
