    <div id="myModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="ibox3">
            <div class="ibox-content">
                                    <div class="sk-spinner sk-spinner-wave">
                                        <label>Loading...</label>
                                        <div class="sk-rect1"></div>
                                        <div class="sk-rect2"></div>
                                        <div class="sk-rect3"></div>
                                        <div class="sk-rect4"></div>
                                        <div class="sk-rect5"></div>
                                    </div> 
           
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title"></h3>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::select ('item_name',$item_name,null,['placeholder' => 'Select Item Name...','class'=>'chosen-select item_name','required'=>true,'id'=>'item_name'])!!}
                    </div>
                </div>
                <div class="form-horizontal m-t-md">
            
                        <div class="table-responsive">

                            <div class="scroll_content" style="width:100%; height:350px; margin: 0;padding: 0;overflow-y: scroll">
                            <table class="table table-bordered dTable-ItemList-table" id="dTable-ItemList-table">
                                <thead> 
                                        <tr>
                                            <th><input type="checkbox" class="largerCheckbox" id="ChkAll" /></th>
                                            <th class="text-center">ID</th>
                                            <th>Description</th>
                                            <th>Units</th>
                                            <th>SRP</th>
                                            <!--<th class="text-center">Remove</th>-->
                                        </tr>

                                </thead>
                                
                                    <tbody >

                                        <!--@foreach($items as $item)

                                            <tr>
                                                <td>{{$item->id}}</td>
                                                <td>{{$item->description}}</td>
                                                <td>{{$item->unit_code}}</td>
                                                <td>{{$item->srp}}</td>
                                                <td class='text-center'>
                                                    <a href="#" class="btn-info btn btn-xs add-item-button" id="add-item-button"
                                                    data-item_id="{{$item->id}}"
                                                    data-item_name="{{$item->name}}"
                                                    data-item_descript="{{$item->description}}"
                                                    data-item_untis="{{$item->unit_code}}"
                                                    data-item_srp="{{$item->srp}}"
                                                    data-item_cost="{{$item->unit_cost}}"><i class="fa fa-plus"></i>
                                                    </a>
                                                </td>
                                                 <td class='text-center'><input type='checkbox' name='chk_add[]' id='chk_add' value='{{$item->id}}'/></td>

                                            </tr>

                                        @endforeach-->
                                           
                                    </tbody>
                                
                            </table> 
                            </div>       
                        </div>
                        

                        <hr>

                    <div class="row">

                        <div class="col-md-12 form-horizontal">

                            <div class="ibox-tools pull-right">
                                
                                <button type="button" class="btn btn-primary btn-add" id="add-selected">Add</button> 
                                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" >Close</button>
                                    
                            </div>

                        </div>

                    </div>
                
                </div>

            </div>
        </div>
         </div>
    </div>  
  </div>  


