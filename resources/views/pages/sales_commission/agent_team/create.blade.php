        
@extends('layouts.app')

@section('pageTitle','Sales Commission')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">

        <div class="col-lg-10">

            <h2>Agent Team</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Agent Team</strong>

                    </li>
                                      
                </ol>

        </div>

    </div>

       @include('layouts.alert')
       @include('layouts.deletemodal')

    <div class="wrapper wrapper-content  animated fadeInRight">
        
        <div class="row">
            
            <div class="col-lg-12">
                
                <div class="ibox float-e-margins">
                    
                    <div class="ibox-title">
                                     
                        <h4>Agent Team</h4> 
                            
                    </div>
                        
                    <div class="ibox-content">
                            
                        <div class="form-horizontal m-t-md">

                        {!! Form::open(array('route' => array('team.store','method'=>'POST'))) !!}

                            @include('pages.sales_commission.agent_team._form')
                                  
                        {!! Form::close() !!}

                        </div>

                    </div>

                </div>

            </div>

        </div> 

    </div>  
        
@endsection

@section('styles')
<link href="/css/plugins/footable/footable.core.css" rel="stylesheet">
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

        $(document).ready(function(){
            $('#btn-add_sub').on('click', function(){
                var _agentID = $('.employee_id').val();
                var _mainRate = $('.share_percentage').val();
                var _subAgent = $('.sub_agent').val();
                var _subAgentName = $('.sub_agent :selected').text();
                var _subRate = $('.sub_rate').val();

                if ( !_agentID ) {

                    toastr.warning('Please select Agent Name','Warning')
                    return false;
                }

                if ( !_mainRate ) {

                    toastr.warning('Please input Main Agent Rate','Warning')
                     return false;
                }

                if ( !_subAgent ) {

                    toastr.warning('Please select Sub Agent Name','Warning')
                     return false;
                }       

                if ( !_subRate ) {

                    toastr.warning('Please input Sub Agent Rate','Warning')
                     return false;
                }  


                       $('#dTable-sub-table tbody').append("<tr><td><input type='hidden' name='main_id[]' class='form-control input-sm text-center main_id' required=true size='2'  value="+ _agentID +" readonly><input type='text' name='sub_id[]' class='form-control input-sm text-center sub_id' readonly=true size='4'  value="+ _subAgent +"   id ='sub_id'></td>\
                            <td>"+ _subAgentName+"</td>\
                            <td>\
                            <input type='text' name='sub_rate[]' class='form-control input-sm text-center sub_rate' readonly=true size='4'  value="+ _subRate +"   id ='sub_rate'>\
                            </td>\
                           <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td>\
                        </tr>");             
         
            });

        });
      
        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/agent-team"; 
            });
        });
        
        $('#dTable-sub-table ').on('click', '#delete_line', function(){
            $(this).closest('tr').remove();
        });
        
        /* var _total_amount = 0;
                        var _ctr = 0;

                        for( var i = 0 ; i <= results.length ; i++ ) {

                                $('#dTable-selected-item-table tbody').append("<tr>\
                                    <td class='text-center'>"+results[i].so_number+"</td>\
                                    <td class='text-center'>"+results[i].so_date+"</td>\
                                    <td class='text-center'>"+results[i].status+"</td>\
                                    <td class='text-center'>"+results[i].sub_agent+"</td>\
                                    <td class='text-center'><input type='text' name='total_sales[]' class='form-control input-sm text-right total_sales' size='4'   id='total_sales' value='"+results[i].total_sales+"'></td></tr>");
                                
                                _ctr = _ctr + 1;
                        }  

                        if ( _ctr = 0 )
                        {
                            toastr.warning('No records will be generated on the selected date','Warning')
                        }  
        */

</script>

@endsection
