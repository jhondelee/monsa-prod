    <a href="{{route('returns.create')}}" class="btn btn-warning btn-sm">
    <i class="fa fa-plus">&nbsp;</i>Return Item</a>

    <div class="hr-line-dashed"></div>
    <table class="table table-striped table-hover dataTables-items">
        <thead>
            <tr>

                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Units</th>
                <th>On Hand</th>
                <th>Location</th>
                <th class="text-center">Action</th>  

            </tr>
        </thead>
        <tbody>
            @foreach($returnLists as $returnList)
                <tr>           
                    <td>{{$returnList->id}}</td>
                    <td>
                        @if ($returnList->picture!="")
                            <img class="img-thumbnail img-responsive text-center"  width="56" height="56" src="/item_image/{!! $returnList->picture !!}"/>
                        @else
                            <img class="img-thumbnail img-responsive text-center"  width="56" height="56" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                        @endif
                        {{$returnList->name}}
                    </td>
                    <td>{{$returnList->description}}</td>
                    <td>{{$returnList->units}}</td>
                    <td>{{$returnList->unit_quantity}}</td>
                    <td>{{$returnList->location}}</td>
                    <td class="text-center">
                            <div class="btn-group tooltip-demo">
                                <a href="{{route('inventory.show',$returnList->item_id)}}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                            </div>
                            <div class="btn-group tooltip-demo">
                                <a href="#" class="btn-danger btn btn-xs rtn-supplier"><i class="fa fa-space-shuttle" data-toggle="tooltip" data-placement="top" title="Return to Supplier" 
                                id ="rtn-supplier"
                                data-rtn_invenid="{{$returnList->id}}"
                                data-rtn_id="{{$returnList->item_id}}"
                                data-rtn_name="{{$returnList->description}}"
                                data-rtn_unit="{{$returnList->unit_quantity}}"
                                data-rtn_date="{{ date('Y-m-d') }}"
                                data-rtn_loc="{{ $returnList->loc_id }}"
                                data-userid="{{$user_id}}">                               
                                </i></a>

                            </div>
                            <div class="btn-group tooltip-demo">
                                <a href="#" class="btn-success btn btn-xs rtn-inventory"><i class="fa fa-send-o" data-toggle="tooltip" data-placement="top" title="Return to Inventory" id ="rtn-inventory"
                                data-rtn_invenid="{{$returnList->id}}"
                                data-rtn_id="{{$returnList->item_id}}"
                                data-rtn_name="{{$returnList->description}}"
                                data-rtn_unit="{{$returnList->unit_quantity}}"
                                data-rtn_date="{{ date('Y-m-d') }}"
                                data-rtn_loc="{{ $returnList->loc_id }}"
                                data-userid="{{$user_id}}">
                                </i></a>
                            </div>
                             
                    </td>
                </tr>
            @endforeach                                                           
        </tbody>
    </table>
