<div id="myModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title"></h3>

            </div>
            <div class="modal-body">
                <div class="form-horizontal m-t-md">
                     
                        <div class="table-responsive">
                            <div class="scroll_content" style="width:100%; height:350px; margin: 0;padding: 0;overflow-y: scroll">
                            <table class="table table-bordered dataTables-add-items" id="dTable-ItemList-table">
                                <thead> 
                                    <tr>
                                        
                                        <th class="text-center">Item#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Unit Cost</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                
                                    <tbody >
                                           @foreach($items as $item)

                                                <tr>

                                                    <td class="text-center">{{$item->id}}</td>
                                                    <td>{{$item->name}}</td>
                                                    <td class="text-center">{{$item->units}}</td>
                                                    <td class="text-right">{{$item->unit_cost}}</td>
                                                    <td class="text-right">?</td>
                                                    <td class="text-right">?</td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                        <a class="btn-primary btn btn-xs btn-add-items" onclick="confirmAddItem('{{$item->id}}'); return false;"><i class="fa fa-plus"></i></a>

                                                        </div>
                                                    </td>

                                                </tr>

                                            @endforeach 
                                    </tbody>
                                
                            </table> 
                            </div>       
                        </div>
                        

                        <hr>

                    <div class="row">

                        <div class="col-md-12 form-horizontal">

                            <div class="ibox-tools pull-right">
                                
                                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" >Close</button>       

                            </div>

                        </div>

                    </div>
                
                </div>

            </div>
        </div>
    </div>  
  </div>  


