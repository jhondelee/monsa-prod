{!! Form::token(); !!}
{!! csrf_field() ; !!} 
    <div class="form-group">
        <label class="col-sm-2 control-label">PO Number </label>
        <div class="col-sm-2">
            {!! Form::text('po_number',null, ['class'=>'form-control pr_number', 'readonly','placeholder'=>'Auto Generage' ,'id'=>'po_number']) !!}
        </div>

        <label class="col-sm-3 control-label">PO Date <span class="text-danger">*</span></label>
        <div  class="col-sm-3 ">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('po_date',null, ['class'=>'form-control', 'required'=>true]) !!}
            </div>
        </div>
    </div>


    <div class="form-group">

              <label class="col-sm-2 control-label">Supplier <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('supplier_id',$supplier, null,['placeholder' => 'Select Supplier...','class'=>'chosen-select','required'=>true, 'id'=>'supplier_id'])!!}
        </div>

        <label class="col-sm-2 control-label">Approved by <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('approved_by',$approver, null,['placeholder' => 'Select Approver...','class'=>'chosen-select','required'=>true])!!}
        </div>


    </div>

 
    <div class="form-group">

     
        <label class="col-sm-2 control-label">Prepared by </label>
        <div class="col-sm-3">
            {!! Form::text('prepared_by',$creator, ['class'=>'form-control', 'readonly']) !!}
        </div>


    </div>

    <div class="form-group">

       <label class="col-sm-2 control-label">Remarks</label>
        <div class="col-sm-3">
             {!! Form::textarea('remarks',null, array('class' => 'form-control', 'rows' => 3)) !!}
        </div>
    </div>




    <div class="hr-line-dashed"></div>

    <div class="form-group">
        <div class="col-sm-3">
            <a class='btn btn-primary btn-xs btn-show-item' id="btn-show-item"><i class='fa fa-plus'></i> Item</a>
        </div>
    </div>
                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered dTable-selected-item-table" id="dTable-selected-item-table">                  

            <thead> 
                <tr>

                    <th class="text-center">Id</th>
                    <th>Description</th>
                    <th>Qty/Unit</th>
                    <th class="text-center">Order Qty</th>
                    <th class="text-center">Remove <a class='btn btn-danger btn-xs btn-remove pull-right'><i class='fa fa-minus'></i></a>
                </tr>   
            </thead>

            <tbody>

                                                                      
            </tbody>

        </table>
        
        <hr>
    </div>
    <!-- start row -->
                            <div class="row">
                                <div class="col-md-8 form-horizontal"></div>
                                
                                <div class="col-md-4 form-horizontal">
                                   
                                    <div class="form-group">
                                        <!--<label class="col-md-6 control-label"> Discount</label>-->
                                        <div class="col-md-6">
                                            {!! Form::hidden('discount',null, array('placeholder' => '0.00','class' => 'form-control text-right','id'=>'discount')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!--<label class="col-md-6 control-label">Total Amount</label>-->
                                        <div class="col-md-6">
                                            {!! Form::hidden('grand_total',null, array('placeholder' => '0.00','class' => 'form-control text-right grand_total','id'=>'grand_total', 'readonly' => 'true' )) !!}
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-2 form-horizontal">
                                </div>       
                                                                
                            </div> 
    <!-- end row -->    
                               
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-12 form-horizontal">
 
            <div class="ibox-tools pull-right">
                 
                <button type="button" class="btn btn-danger btn-close" id="btn-close">Close</button>

                @if ($order_status == 'NEW')
                        
                <button type="button" class="btn btn-primary" onclick="submit_validate()"> Save Changes</button>
                <button type="submit" id="btn-submit" style="display:none;"></button>                       
        
                @endif


      

             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>

