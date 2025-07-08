
    <table class="table table-bordered table-hover dataTables-consumable-items" id="dataTables-consumable-items">
        
        <thead>

            <tr>
                <th>ID</th>
                <th>Reference No.</th>
                <th>Item Description</th>
                <th>Unit</th>
                <th>Request Qty</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Created by</th>
                <th>Action</th>  
            </tr>

        </thead>
                                        
        <tbody>

            @foreach($requestlist as $request)
                                            
                <tr>
                    
                    <td>{{$request->id}}</td>
                    <td>{{$request->reference_no}}</td>
                    <td>{{$request->description}}</td>
                    <td>{{$request->units}}</td>
                    <td>{{$request->request_qty}}</td>   
                    
                    <td class="text-center">
                        @if ($request->posted == 0)
                            <label class="label label-warning " >Unpost</label>
                        @else
                            <label class="label label-success " >Posted</label>
                        @endif
                    </td>
                    
                    <td>{{ date('m-d-Y', strtotime($request->created_at))}}</td>
                    <td>{{$request->emp_name}}</td> 
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="#" class="btn-primary btn btn-xs" id="btn-edit-request"
                            data-edt_id="{{$request->id}}"
                            data-edit_ref_no="{{$request->reference_no}}"
                            data-edit_name="{{$request->description}}"
                            data-edit_units="{{$request->units}}"
                            data-edit_req_qty="{{$request->request_qty}}"
                            data-edit_status="{{$request->posted}}"><i class="fa fa-pencil"></i></a>
                        </div> 
                        @if ($request->posted == 0)
                        <div class="btn-group">
                            <a href="#" class="btn-success btn btn-xs" id="post-btn" title="Post" onclick="confirmPost('{{$request->id}}'); return false;"><i class="fa fa-exclamation-circle"></i></a>
                        </div>
                        <div class="btn-group">
                            <a href="#" class="btn-primary btn btn-xs"  id="delete-btn" onclick="confirmDelete('{{$request->id}}'); return false;"><i class="fa fa-trash"></i></a>
                        </div>
                        @endif                
                    </td>

                </tr>  
            @endforeach
                                                                               
        </tbody>

    </table>




