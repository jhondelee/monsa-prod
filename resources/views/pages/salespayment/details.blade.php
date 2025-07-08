<div id="ShowPayemntModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title">Show Details</h3>

            </div>
            <div class="modal-body">
                <div class="form-horizontal m-t-md">
                    

                             <div class="form-group">
                                 <label class="col-sm-4 control-label">Payment Date <span class="text-danger">*</span></label>
                                <div  class="col-sm-7">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        {!! Form::text('_date_payment',null, ['class'=>'form-control','id'=>'_date_payment']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Transaction No.<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    {!! Form::text('_trasanction_no',null, ['class'=>'form-control _trasanction_no','required'=> true]) !!}
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Payment Mode <span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    {!! Form::text('_payment_mode_id',null, ['class'=>'form-control _payment_mode_id']) !!}

                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Name</label>
                                <div class="col-sm-7">
                                    {!! Form::text('_bank_name',null, ['class'=>'form-control _bank_name']) !!}
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Account No.</label>
                                <div class="col-sm-7">
                                    {!! Form::text('_bank_account_no',null, ['class'=>'form-control _bank_account_no']) !!}
                                </div>

                            </div>



                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Account Name</label>
                                <div class="col-sm-7">
                                    {!! Form::text('_bank_account_name',null, ['class'=>'form-control _bank_account_name']) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amount Paid<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {!! Form::text('_amount_collected',null, ['class'=>'form-control text-right _amount_collected','placeholder' => '0.00', 'required'=>true]) !!}
                                    </div>
                            </div>

                            <div class="form-group">

                            
                                <label class="col-sm-4 control-label">Collector <span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                       {!! Form::text('_collected_by',null, ['class'=>'form-control _collected_by','placeholder' => '0.00', 'required'=>true]) !!}

                                </div>

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

