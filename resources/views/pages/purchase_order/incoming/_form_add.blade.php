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

    </div>


    <div class="form-group">

          <label class="col-sm-2 control-label">DR Date <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('dr_date',null, ['class'=>'form-control', 'id'=>'dr_date']) !!}
            </div>
        </div>
      <label class="col-sm-2 control-label">DR Number <span class="text-danger">*</span></label>
        <div class="col-sm-3">
           {!! Form::text('dr_number',null, ['class'=>'form-control dr_number' ,'id'=>'dr_number']) !!}
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

        <label class="col-sm-2 control-label">Discount :</label>
        <div class="col-sm-2">
            {!! Form::text('discount_input',null, ['class'=>'form-control discount_input' ,'id'=>'discount_input']) !!}
        </div>

        <label class="col-sm-2 control-label">Total Amount :</label>
        <div class="col-sm-2">
            {!! Form::text('total_amount_input',null, ['class'=>'form-control total_amount_input' ,'id'=>'total_amount_input', 'readonly' => 'true']) !!}
        </div>

    </div>

    <div class="hr-line-dashed"></div>


                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered" id="dTable-receive-item-table">                  

            <thead> 
                
                <tr>
                    
                    <th class="text-center">Item No.</th>
                    <th class="text-center">Item Code</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Rec'd Qty</th>
                </tr>

            </thead>

            <tbody>

                                                                      
            </tbody>

        </table>
        
        <hr>
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

