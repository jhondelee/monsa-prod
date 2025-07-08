{!! Form::token(); !!}
{!! csrf_field() ; !!} 
                                                                             

    <div class="form-group">
        <input type="hidden" id="returns_id" name="id" value="{{$returns->id}}" />

        <label class="col-sm-2 control-label">SO Number :</label>
        <div class="col-sm-3">
            {!! Form::text('so_number',null, ['class'=>'form-control','readonly'=>true, 'id'=>'lbl_so_number']) !!}
        </div>

        <label class="col-sm-2 control-label">SO Date :</label>
        <div class="col-sm-3">
              {!! Form::text('so_date',$so_date, ['class'=>'form-control','readonly'=>true, 'id'=>'lbl_so_date']) !!}
        </div>

    </div>
 
    <div class="form-group">

        <label class="col-sm-2 control-label">Customer :</label>
        <div class="col-sm-3">
            {!! Form::text('customer',$customer, ['class'=>'form-control','readonly'=>true, 'id'=>'lbl_customer']) !!}
        </div>

        <label class="col-sm-2 control-label">Warehouse <span class="text-danger">*</span></label>
        <div class="col-sm-3 ">
            {!! Form::select ('location',$location, null,['placeholder' => 'Select Location...','class'=>'chosen-select','required'=>true,'id'=>'location'])!!}
        </div>

    </div>


    <div class="form-group">

          <label class="col-sm-2 control-label">Return Date <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('return_date',null, ['class'=>'form-control','required'=>true, 'id'=>'return_date']) !!}
            </div>
        </div>
      <label class="col-sm-2 control-label">Reference No. </label>
        <div class="col-sm-3">
           {!! Form::text('reference_no',null, ['class'=>'form-control text-center reference_no','placeholder' => 'Auto-Generated','readonly'=>true, 'id'=>'reference_no']) !!}
        </div>

    </div>

     <div class="form-group">

   
        <label class="col-sm-2 control-label">Reason</label>
        <div class="col-sm-3">
             {!! Form::textarea('reason',null, array('class' => 'form-control', 'rows' => 3,'id'=>'notes')) !!}
        </div>

     
        <label class="col-sm-2 control-label">Received by <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('received_by',$received_by, null,['placeholder' => 'Select Employee...','class'=>'chosen-select', 'required' => '','id'=>'received_by'])!!}
        </div>

    </div>



    <div class="hr-line-dashed"></div>


                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered dTable-return-item-table" id="dTable-return-item-table">                  

            <thead> 
                
                <tr>
                    
                    <th class="text-center">Id</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th class="text-center">Order Qty</th>
                    <th class="text-center">SRP</th>
                    <th class="text-center">Return Qty</th>
                    <th class="text-center">Amount </th>
                 

                </tr>

            </thead>

            <tbody>

                                                                      
            </tbody>

        </table>
        
        <hr>
    </div>
    <div class="row">
    <div class="col-md-8 form-horizontal"></div>
        <div class="col-md-4 form-horizontal pull-right">
        
            <div class="form-group">

                <label class="col-md-6 control-label">Total Amount</label>

                    <div class="col-md-6">

                        {!! Form::text('total_amount',$returns->amount, array('placeholder' => '0.00','class' => 'form-control text-right total_amount','style'=>'font-weight:bold;','id'=>'total_amount', 'readonly' => 'true' )) !!}

                    </div>

            </div>

        </div> 

    </div>

    <div class="hr-line-dashed"></div>

    <div class="row">

        <div class="col-md-12 form-horizontal">
                                             
            <div class="ibox-tools pull-right">
                 
                <button type="button" class="btn btn-danger btn-close" id="btn-close"><i class="fa fa-reply">&nbsp;</i>Back</button>
                 
                @if ($returns->status == 0)

                {!! Form::submit(' Save Changes', ['class' => 'btn btn-primary']) !!}  
                
                @endif
             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>



