{!! Form::token(); !!}
{!! csrf_field() ; !!} 
    <div class="form-group">
        <label class="col-sm-2 control-label">PO Number </label>
        <div class="col-sm-2">
            {!! Form::text('so_number',null, ['class'=>'form-control so_number', 'readonly','placeholder'=>'Auto Generage' ,'id'=>'so_number']) !!}
        </div>

        <label class="col-sm-3 control-label">SO Date <span class="text-danger">*</span></label>
        <div  class="col-sm-3 ">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('so_date',null, ['class'=>'form-control so_date', 'required'=>true, 'id'=>'so_date']) !!}
            </div>
        </div>
    </div>


    <div class="form-group">

              <label class="col-sm-2 control-label">Customer <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('customer_id',$customer_id, null,['placeholder' => 'Select Customer...','class'=>'chosen-select','required'=>true, 'id'=>'customer_id'])!!}
        </div>

        <label class="col-sm-2 control-label">Main Agent <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('employee_id',$employee_agent, null,['placeholder' => 'Select Main Agent...','class'=>'chosen-select','required'=>true])!!}
        </div>


    </div>

 
    <div class="form-group">

     
        <label class="col-sm-2 control-label">Prepared by </label>
        <div class="col-sm-3">
            {!! Form::text('prepared_by',$creator, ['class'=>'form-control', 'readonly']) !!}
        </div>

        <label class="col-sm-2 control-label">Approved by <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('approved_by',$employee, null,['placeholder' => 'Select Approver...','class'=>'chosen-select','required'=>true])!!}
            
        </div>


    </div>

    <div class="form-group">

       <label class="col-sm-2 control-label">Remarks </label>
        <div class="col-sm-3">
             {!! Form::textarea('remarks',null, array('class' => 'form-control', 'rows' => 3)) !!}
        </div>





    </div>

     <div class="form-group">
                 <label class="col-sm-2 control-label">Warehouse <span class="text-danger">*</span></label>
                <div class="col-sm-3">
            {!! Form::select ('location',$location, null,['placeholder' => 'Select Warehouse...','class'=>'chosen-select', 'id'=>'location'])!!} 
        </div>
     </div>


    <div class="hr-line-dashed"></div>

    <div class="form-group">


        <div class="col-sm-3">
            <a class='btn btn-primary btn-sm btn-show-item' id="btn-show-item"><i class='fa fa-plus'></i> Item</a>

        </div>

    </div>
                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered dTable-selected-item-table" id="dTable-selected-item-table">                  

            <thead> 
                <tr>

                    <th class="text-center">Id</th>
                    <th>Item Description</th>
                    <th>#Stock/Unit</th>
                    <th class="text-center">Order Qty</th>
                    <th class="text-center">SRP</th>
                    <th class="text-center">₱ Disc.</th>
                    <th class="text-center">% Disc.</th>
                    <th class="text-center">Set Price</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Remove </th>    
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
                                    <!--<div class="form-group">
                                        <label class="col-md-6 control-label">₱ Added</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_amount_added',null, array('placeholder' => '0.00','class' => 'form-control text-right total_amount_added','id'=>'total_amount_added', 'readonly' => 'true')) !!}
                                        </div>
                                    </div>
                                                                        <div class="form-group">
                                        <label class="col-md-6 control-label">% Discount</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_percent_discount',null, array('placeholder' => '0.00','class' => 'form-control text-right total_percent_discount','id'=>'total_percent_discount', 'readonly' => 'true')) !!}
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group">
                                        <label class="col-md-6 control-label">Subtotal</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_subamount',$subAmount, array('placeholder' => '0.00','class' => 'form-control text-right total_subamount','id'=>'total_subamount', 'readonly' => 'true')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label"> Amount Discount</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_amount_discount',null, array('placeholder' => '0.00','class' => 'form-control text-right total_amount_discount','id'=>'total_amount_discount', 'readonly' => 'true')) !!}
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-6 control-label">Total Amount</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_sales',null, array('placeholder' => '0.00','class' => 'form-control text-right total_sales','id'=>'total_sales', 'readonly' => 'true' )) !!}
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

                @if ($salesorder_status == 'NEW')
                        

                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}                       
        
                @endif  

             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>

