@extends('layouts.app')

@section('pageTitle','Sales Commission Report')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2></h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Sales Commision Report</strong>
                        </li>
                       
                    </ol>

                </div>

        </div>

       @include('layouts.alert')
       @include('layouts.deletemodal')
    
        <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Sales Commissions</h5>
          
                                <div class="ibox-tools"> 


                                </div>
                            
                        </div>

                        <div class="ibox-content">
                            
                            <div class="form-group">  
                                <div class="col-sm-8">
                                    {!! Form::select ('commssion_report',[
                                        'Agent Commission Report'
                                    ],null,['placeholder' => '--Select Commission Report--','class'=>'chosen-select','id'=>'commssion_report'])!!}
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-primary btn-generate" id="btn-generate">Generate</button>
                                </div>
                             </div>
                                                       
                                <div class="row">
                                    <div class="col-md-12 form-horizontal">
                             
                                        <div class="ibox-tools pull-right">
 


                                         </div>

                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>

                        </div>

                        </div>
                    </div>

                </div>

            </div>
@include('pages.sales_commission.report.filter')
@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">





    function confirmDelete(data,model) {   
        $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
            $(this).attr("disabled","disabled");
            document.location.href="/commission-report/delete/"+data;
        });
    }

     
        $(document).on('click', '.btn-generate', function() {

            var _report =  $('#commssion_report :selected').text();

                if( _report  == 'Agent Commission Report'){

                    $('.modal-title').text('Sales Commission Filter');
                    $('#myModalReport').modal('show');
                    
                } 
                
        });

    
</script>

@endsection