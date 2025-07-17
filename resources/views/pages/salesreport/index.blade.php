@extends('layouts.app')

@section('pageTitle','Sales Payment')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2></h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Sales Report</strong>
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
                                <h5>Sales Collection</h5>
          
                                <!--@if (!can('sales_payment.create'))
                                  @endif  -->

                                <div class="ibox-tools"> 


                                </div>
                            
                        </div>

                        <div class="ibox-content">
                            
                            <div class="form-group">  
                                <div class="col-sm-8">
                                    {!! Form::select ('select_report',[
                                        'Sales Report',
                                        'Sales Payment Report',
                                        'Customer Payment Report'
                                    ],null,['placeholder' => '--Select Report--','class'=>'chosen-select','id'=>'select_report'])!!}
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
@include('pages.salesreport.filter')
@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">





    function confirmDelete(data,model) {   
        $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
            $(this).attr("disabled","disabled");
            document.location.href="/sales-payment/delete/"+data;
        });
    }

     
        $(document).on('click', '.btn-generate', function() {

            var _report =  $('#select_report :selected').text();

                if( _report  == 'Sales Payment Report' || _report == 'Customer Payment Report' ){
                            
                    $('.modal-title').text('Report Filter');
                    $('#myModalReport').modal('show');

                }else{

                    toastr.warning('Please Select Report','Warning' )

                }

        });


        $(document).on('click', '.btn-submit', function() {

            var pay_mode = $('#pay_mode').val();

            if (pay_mode ==''){
                pay_mode = 0;
            }
 
            document.location.href="/sales-report/generate/"+pay_mode;
            
        });

    $(document).ready(function(){
        var _id = 0;
        $.ajax({
            url:  '{{ url('sales-payment/datatable') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: _id},  
            success:function(results){
            }
        }); 

    });
    
</script>

@endsection