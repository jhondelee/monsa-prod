@extends('layouts.app')

@section('pageTitle','Customer')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2></h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Customer</strong>
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
                                <h5>Customer List</h5>
          
                                 @if (!can('customer.create'))
                                <div class="ibox-tools"> 
                                    <a href="{{route('customer.create')}}" class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i>Customer
                                    </a> 
                                </div>
                                @endif  

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-items"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Area</th>
                                            <th>Created_by</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($customers as $customer)

                                                <tr>

                                                    
                                                    <td>{{$customer->id}}</td>
                                                    <td>{{$customer->customer_name}}</td>
                                                    <td>{{$customer->address}}</td>
                                                    <td>{{$customer->customer_area}}</td>
                                                    <td>{{$customer->created_by}}</td>
                                                    <td class="text-center">
                                                         @if (!can('customer.edit'))
                                                        <div class="btn-group">
                                                            <a href="{{route('customer.edit',$customer->id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('customer.delete'))
                                                        <div class="btn-group">
                                                          <a class="btn-primary btn btn-xs delete" onclick="confirmDelete('{{$customer->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                        @endif
                                                    </td>

                                                </tr>

                                            @endforeach
                                                                               
                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            
          
@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>

<script type="text/javascript">
    


        $(document).ready(function(){
              $('.dataTables-items').DataTable({
                pageLength: 50,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Suppier List'},
                    {extend: 'pdf', title: 'Suppliers'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/customer/delete/"+data;
            });
        }


  
        
        
        
  
      
           
    
    
</script>

@endsection