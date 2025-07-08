<div id="AddPayemntModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title"><i class="fa fa-plus">&nbsp;</i></h3>

            </div>
            <div class="modal-body">
                <div class="form-horizontal m-t-md">
                    
                    {!! Form::model($salespayments, ['route' => ['sales_payment.storeitems', $salespayments->id],'id'=>'storeitems_form']) !!}

                                        
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                            {!! Form::hidden('sales_payment_id',$salespayments->id, ['class'=>'form-control id','id'=>'salespayment_id']) !!}
 
                             <div class="form-group">
                                 <label class="col-sm-4 control-label">Payment Date <span class="text-danger">*</span></label>
                                <div  class="col-sm-7">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        {!! Form::text('date_payment',null, ['class'=>'form-control','id'=>'date_payment']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Transaction No.<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    {!! Form::text('trasanction_no',null, ['class'=>'form-control trasanction_no','required'=> true]) !!}
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Payment Mode <span class="text-danger">*</span></label>
                                <div class="col-sm-7">

                                    {!! Form::select ('payment_mode_id',$payment_mode, null,['placeholder' => 'Choose Payment Mode...','class'=>'chosen-select required payment_mode_id'])!!}
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div> 

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Name</label>
                                <div class="col-sm-7">
                                    {!! Form::text('bank_name',null, ['class'=>'form-control bank_name']) !!}
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Account No.</label>
                                <div class="col-sm-7">
                                    {!! Form::text('bank_account_no',null, ['class'=>'form-control bank_account_no']) !!}
                                </div>

                            </div>



                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Account Name</label>
                                <div class="col-sm-7">
                                    {!! Form::text('bank_account_name',null, ['class'=>'form-control bank_account_name']) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amount Paid<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {!! Form::text('amount_collected',null, ['class'=>'form-control text-right amount_collected','placeholder' => '0.00', 'required'=>true]) !!}
                                    </div>
                            </div>

                            <div class="form-group">

                            
                                <label class="col-sm-4 control-label">Collector <span class="text-danger">*</span></label>
                                <div class="col-sm-7">

                                    {!! Form::select ('collected_by',$collector, null,['placeholder' => 'Choose Collector...','class'=>'chosen-select required collected_by'])!!}
                                </div>

                            </div>
                                
                                
                            </div>

                             <hr>
                           
                            <div class="row">

                                <div class="col-md-12 form-horizontal">

                                    <div class="ibox-tools pull-right">
                                        
                                        <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" >Close</button>  

                                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}  

                                    </div>

                                </div>

                            </div>

                        
                            {!! Form::close() !!}

                        
                        


                
                </div>

            </div>
        </div>
    </div>  
  </div>  

