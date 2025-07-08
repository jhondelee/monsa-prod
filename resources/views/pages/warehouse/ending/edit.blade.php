    
@extends('layouts.app')

@section('pageTitle','Ending Inventory')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Ending Inventory</h2>

                <ol class="breadcrumb">
                    <li>
                        <a href="{{route('main')}}">Home</a>
                    </li>
                    <li class="active">
                        <strong>Ending Inventory List</strong>
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

                            <h5>Edit Ending Inventory</h5>
                            <div class="ibox-tools"> 
                                  <a href="{{route('ending.index')}}"class="btn btn-sm btn-primary" id='btn-cancel'><i class="fa fa-reply">&nbsp;</i>Back</a> 
                                </div>
                            
                            
                        </div>

                        <div class="ibox-content">

                            @if($endinginventory->status == 'UNPOSTED')
                            <button type="button" class="btn btn-success" onclick="confirmPost('{{$endinginventory->id}}'); return false;"><i class="fa fa-exclamation-circle">&nbsp;</i>Post</button>
                            @endif

                            <a href="{{route('ending.print',$endinginventory->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a>     &nbsp;
                            <div class="form-horizontal m-t-md">

                            {!! Form::model($endinginventory, ['route'=>['ending.update', $endinginventory->id]]) !!}

                                 @include('pages.warehouse.ending._form_edit')
                                  
                            {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




@endsection



@section('scripts')

<script type="text/javascript">


        // allow only numeric with decimal
        $("#item_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });


        function confirmPost(data,model) {   
         $('#confirmPost').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#post-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/ending/post/"+data;
            });
        }

        
  

</script>

@endsection













