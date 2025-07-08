{!! Form::token(); !!}
{!! csrf_field() ; !!} 
    <div class="form-group">
        <label class="col-sm-2 control-label">Agent Name <span class="text-danger">*</span></label>
        <div class="col-sm-3">
         {!! Form::select ('employee_id',$employee, null,['placeholder' => 'Select Agent...','class'=>'chosen-select employee_id','required'=>true])!!}
        </div>

        <label class="col-sm-3   control-label">Date From <span class="text-danger">*</span></label>
        <div  class="col-sm-3 ">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('from_date',null, ['class'=>'form-control from_date', 'required'=>true]) !!}
            </div>
        </div>
    </div>


 
    <div class="form-group">

     
        <label class="col-sm-2 control-label">Prepared by </label>
        <div class="col-sm-3">
            {!! Form::text('creator',$creator, ['class'=>'form-control', 'readonly']) !!}
        </div>

        <label class="col-sm-3 control-label">Date To <span class="text-danger">*</span></label>
        <div  class="col-sm-3 ">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('to_date',null, ['class'=>'form-control to_date', 'required'=>true]) !!}
            </div>
        </div>


    </div>




    <div class="hr-line-dashed"></div>

                        


    <div class="form-group">
        <div class="col-sm-3">
            <a class='btn btn-success btn-xs btn-generate' id="btn-generate"><i class='fa fa-undo'></i> Generate S.C.</a>
        </div>
    </div>
                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered dTable-selected-item-table" id="dTable-selected-item-table">                  

            <thead> 
                <tr>

                    <th class="text-center">SO Number</th>
                    <th>Customer</th>
                    <th>SO Date</th>
                    <th>SO Status</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Total Sales</th>

                </tr>   
            </thead>

            <tbody>

                                                                      
            </tbody>

        </table>
        
        <hr>
    </div>
    <br>

 

    <!-- start row -->
                            <div class="row">
                                <div class="col-md-8 form-horizontal"></div>
                                
                                <div class="col-md-4 form-horizontal">

                                    <div class="form-group">
                                    <label class="col-md-6 control-label">Total Sales</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_sales',null, array('placeholder' => '0.00','class' => 'form-control text-right','id'=>'total_sales', 'readonly' => 'true' )) !!}

                                            {!! Form::hidden('total_sales_amount',null, array('id'=>'total_sales_amount' )) !!}
  
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                    <label class="col-md-6 control-label">Rate %</label>
                                        <div class="col-md-6">
                                            {!! Form::text('rate',null, array('placeholder' => '0.00','class' => 'form-control text-right rate','id'=>'rate', 'readonly' => 'true' )) !!}
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group">
                                    <label class="col-md-6 control-label">Total Commision</label>
                                        <div class="col-md-6">
                                            {!! Form::text('total_commission',$total_com, array('placeholder' => '0.00','class' => 'form-control text-right total_commission','id'=>'total_commission', 'readonly' => 'true' )) !!}
                                        </div>
                                    </div>
                                </div>   
                                   
                             </div>  

   <div class="hr-line-dashed"></div>

        <div class="col-md-12 form-horizontal" id="div-agents">



    

        </div> 

    </div> 

    
    <!-- end row -->    
  
 
    <div class="row">

        <div class="col-md-12 form-horizontal">
        <div class="hr-line-dashed"></div>
            <div class="ibox-tools pull-right">
                 
            <button type="button" class="btn btn-danger btn-close" id="btn-close">Close</button>

            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}                       

             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>

