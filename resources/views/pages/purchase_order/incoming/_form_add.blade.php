{!! Form::token(); !!}
{!! csrf_field() ; !!} 
                                                                             

    
    <div class="form-group">  
        <div class="col-sm-8 ">
            {!! Form::select ('search',$po_number, null,['placeholder' => 'Select PO Number...','class'=>'chosen-select','id'=>'search'])!!}
        </div>

        <div class="col-sm-3">
            <button type="button" class="btn btn-primary" id="btn-search">Search Purchase Order #</button>
        </div>
     </div>

    <div class="hr-line-dashed"></div>

    <div class="form-group">
        <input type="hidden" id="order_id" name="order_id"/>

        <label class="col-sm-2 control-label">PO Number :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h5" id="po_number"></p></div>
            <input type="hidden" id="po_number_input" name="po_number_input"/>
            <input type="hidden" name="supplier_id" id="supplier_id" value="">
        </div>

        <label class="col-sm-2 control-label">PO Date :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h5" id="po_date" name="po_date"></p></div>
        </div>


    </div>


    <div class="form-group">

        <label class="col-sm-2 control-label">Prepared by :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h5" id="prepared_by"></p></div>
        </div>


        <label class="col-sm-2 control-label">Approved by :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h5" id="approved_by"></p></div>
        </div>

    </div>

 
    <div class="form-group">

        <label class="col-sm-2 control-label">Supplier :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h5" id="supplier"></p></div>
        </div>

        <label class="col-sm-2 control-label">Warehouse <span class="text-danger">*</span></label>
        <div class="col-sm-3">
                 {!! Form::select ('location',$location, null,['placeholder' => 'Choose Location...','class'=>'chosen-select','required'=>true,'id'=>'location'])!!}
        </div>
    </div>


    <div class="form-group">

          <label class="col-sm-2 control-label">DR Date <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('dr_date',null, ['class'=>'form-control','required'=>true , 'id'=>'dr_date']) !!}
            </div>
        </div>
      <label class="col-sm-2 control-label">DR Number <span class="text-danger">*</span></label>
        <div class="col-sm-3">
           {!! Form::text('dr_number',null, ['class'=>'form-control dr_number' ,'required'=>true  ,'id'=>'dr_number']) !!}
        </div>

    </div>

     <div class="form-group">

   
        <label class="col-sm-2 control-label">Notes</label>
        <div class="col-sm-3">
             {!! Form::textarea('notes',null, array('class' => 'form-control', 'rows' => 3,'id'=>'notes')) !!}
        </div>

     
        <label class="col-sm-2 control-label">Received by <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('received_by',$received_by, null,['placeholder' => 'Select Employee...','class'=>'chosen-select','required'=>true,'id'=>'received_by'])!!}
        </div>

    </div>



    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-3">
            <a class='btn btn-primary btn-xs btn-show-item' id="btn-show-item"><i class='fa fa-plus'></i> Item</a>
        </div>
    </div>

                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered dTable-receive-item-table" id="dTable-receive-item-table">                  

            <thead> 
                
                <tr>
                    
                    <th class="text-center">Item No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-center">Order Qty</th>
                    <th class="text-center">Rec'd Qty</th>
                    @if (!can('item.unit_cost'))
                    <th>Unit Cost</th>
                    @endif
                    <th>Total Amout</th>

                </tr>

            </thead>

            <tbody>

                                                                      
            </tbody>

        </table>
        
        <hr>
    </div>
                       <div class="row">
                                <div class="col-md-8 form-horizontal"></div>
                                
                                <div class="col-md-4 form-horizontal">
                                   
                                    <div class="form-group">
                                        <label class="col-md-6 control-label"> Amount Discount</label>
                                        <div class="col-md-6">
                                            {!! Form::text('discount_input',null, array('placeholder' => '0.00','class' => 'form-control text-right discount_input','id'=>'discount_input')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label">Total Amount</label>
                                        <div class="col-md-6">
                                            {!! Form::text('grand_total_amount',null, array('placeholder' => '0.00','class' => 'form-control text-right grand_total_amount','id'=>'grand_total_amount', 'readonly' => 'true' )) !!}
                                        </div>
                                    </div>
                                </div>  
                                          
                            </div> 
                               
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-12 form-horizontal">
 
            <div class="ibox-tools pull-right">
                 
                <button type="button" class="btn btn-danger btn-close" id="btn-close"><i class="fa fa-reply">&nbsp;</i>Back</button>
                 
                 
                {!! Form::submit(' Save Changes', ['class' => 'btn btn-primary']) !!}  

             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>

