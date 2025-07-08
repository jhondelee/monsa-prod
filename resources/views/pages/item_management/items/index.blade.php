@extends('layouts.app')

@section('pageTitle','Item')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2></h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Items</strong>
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
                                <h5>Item List</h5>
          
                                 @if (!can('item.create'))
                                <div class="ibox-tools"> 

                                    <a href="{{route('item.create')}}" class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i>Item
                                    </a> 
                                </div>
                                @endif  

                            </div>

                            <div class="ibox-content">
                                    
                                <div class="table-responsive">
                                    <div class="col-sm-4">
                                        <form action="..." method="GET">
                                            @csrf 
                                            {!! Form::select ('item_name',$item_name,$val,['placeholder' => 'Select Name...','class'=>'chosen-select item_name','required'=>true,'id'=>'item_name'])!!}
                                        </form>
                                    </div>
                                    <table class="table table-striped table-hover dataTables-items"data-toggle="dataTable" data-form="deleteForm" id="dTable-ItemList-table">
                                        <thead>
       
                                        </thead>
                                        <tbody>

                                                                
                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            
          @include('pages.item_management.items.price')

@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">



$(document).ready(function(){
    var valueSelected = $('.item_name').val();
    $.ajax({
        url:  '{{ url('item/datatable') }}',
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}",
        value: valueSelected},  
        success:function(results){
                        //
           $('#dTable-ItemList-table').DataTable({
                                destroy: true,
                                pageLength: 100,
                                responsive: true, 
                                data: results,
                                dom: '<"html5buttons"B>lTfgitp',
                                buttons: [],
                                fixedColumns: true,
                                columns: [
                                    {data: 'id', title: 'Id'}, 
                                    {data: 'code', title: 'Code'},  
                                    {data: 'name', title: 'Name'},    
                                    {data: 'description', title: 'Item Description'},
                                    {data: 'unit_code', title: 'Units'},
                                    {data: 'free', title: 'Availability',
                                        render: function(data, type, row){
                                            if(row.free=='1'){
                                                return '<label class="label label-danger" >Free</label>  '
                                            }else{
                                                return '<label class="label label-warning" ></label>';
                                            }   
                                        }
                                    },
                                    {data: 'activated', title: 'Status',
                                        render: function(data, type, row){
                                            if(row.activated=='1'){
                                                return '<label class="label label-success" >Active</label>  '
                                            }else{
                                                return '<label class="label label-warning" >Inctive</label>';
                                            }   
                                        }
                                    },
                                    {data: null, title: 'Action',
                                        render: function(data, type, row){
                                                return '@if (!can('item.edit'))<a class="btn-danger btn btn-xs edit-modal" data-id="'+ row.id +'" data-descript="'+ row.description +'" data-srp="'+ row.srp +'"  data-unit_cost="'+ row.unit_cost +'"><i class="fa fa-money"></i></a>&nbsp;<a href="item/edit/'+row.id+'" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>@endif&nbsp;@if (!can('item.delete'))<a class="btn-primary btn btn-xs delete" onclick="confirmDelete('+row.id+'); return false;"><i class="fa fa-trash"></i></a>@endif';
                                        }
                                    },
                         
                                ],
                            })

        }
    }); 

});


        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/item/delete/"+data;
            });
        }


        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Area');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Update Cost/Srp');
            $('#id_edit').val($(this).data('id'));
            $('#descript_edit').val($(this).data('descript'));
            $('#srp_edit').val($(this).data('srp'));
            $('#unit_cost_edit').val($(this).data('unit_cost'));
            $('#editModal').modal('show');
        });


        $('.item_name').on('change', function (e) {
            var valueSelected = this.value;
             $.ajax({
                url:  '{{ url('item/getname') }}',
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}",
                value: valueSelected},  
                success:function(results){
                                //
                   $('#dTable-ItemList-table').DataTable({
                                        destroy: true,
                                        pageLength: 100,
                                        responsive: true,
                                        data: results,
                                        autoWidth: true,
                                        dom: '<"html5buttons"B>lTfgitp',
                                        buttons: [],
                                        fixedColumns: true,
                                        columns: [
                                            {data: 'id', title: 'Id'}, 
                                            {data: 'code', title: 'Code'},  
                                            {data: 'name', title: 'Name'},    
                                            {data: 'description', title: 'Item Description'},
                                            {data: 'unit_code', title: 'Units'},
                                            {data: 'free', title: 'Availability',
                                                render: function(data, type, row){
                                                    if(row.free=='1'){
                                                        return '<label class="label label-success" >Free</label>  '
                                                    }else{
                                                        return '<label class="label label-warning" ></label>';
                                                    }   
                                                }
                                            },
                                            {data: 'activated', title: 'Status',
                                                render: function(data, type, row){
                                                    if(row.activated=='1'){
                                                        return '<label class="label label-success" >Active</label>  '
                                                    }else{
                                                        return '<label class="label label-warning" >Inctive</label>';
                                                    }   
                                                }
                                            },
                                            {data: null, title: '  Action  ',
                                                render: function(data, type, row){
                                                        return '@if (!can('item.edit'))<a class="btn-danger btn btn-xs edit-modal" data-id="'+ row.id +'" data-descript="'+ row.description +'" data-srp="'+ row.srp +'"  data-unit_cost="'+ row.unit_cost +'"><i class="fa fa-money"></i></a>&nbsp;<a href="item/edit/'+row.id+'" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>@endif&nbsp;@if (!can('item.delete'))<a class="btn-primary btn btn-xs delete" onclick="confirmDelete('+row.id+'); return false;"><i class="fa fa-trash"></i></a>@endif';
                                                }
                                            },
                                 
                                        ],
                                    })

                }
            }); 

        });

        

    
    
</script>

@endsection