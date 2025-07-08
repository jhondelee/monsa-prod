    
@extends('layouts.app')

@section('pageTitle','Roles')

@section('content')


      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>User Management</h2>

                <ol class="breadcrumb">

                    <li class="active">

                        <strong>Role List</strong>

                    </li>
                    
                </ol>

            </div>

        </div>
        @include('layouts.alert')
        @include('layouts.alert2')
        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">

                <div class="col-lg-12">

                    <div class="ibox float-e-margins">

                        <div class="ibox-title">

                            <h5>Edit Role</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">


                                     {!! Form::model($role, ['route' => ['role.update', $role->id]]) !!}

                                        @include('pages.user_management.role._form')
                                  
                                    {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>



  @endsection


@section('styles')
<link rel="stylesheet" href="{!! asset('css/plugins/gijgo.min.css') !!}" />
@endsection

@section('scripts')
<script src="{{ asset('/js/plugins/gijgo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

<script "text/javascript">


        function filter(element) {
            var value = $(element).val();
            $("div.route-item").each(function () {
                getTxt = $(this).text().toLowerCase();
                filterTxt = value.toLowerCase();
                
                if (getTxt.indexOf(filterTxt) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

         $('#search_routes').keyup(function() {
                filter(this); 
            }); 

         $('.scroll_content').slimscroll({
                height: '200px',
                width: '300px'
            });
  
         $("#select-tree-checkbox").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
            });

           var results = {!! json_encode($permissions->toArray(), JSON_HEX_TAG) !!};
            for(var i=0;i<=results.length;i++) {
                    $("div.route-item input[value='"+results[i].permission_id +"']").attr('checked','checked');
            }
        
</script>
@endsection
















