<div id="myModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                <h3 class="modal-title"></h3>

            </div>
            <div class="modal-body">
                <div class="form-horizontal m-t-md">
                     
                        <div class="table-responsive">
                            <div class="scroll_content" style="width:100%; height:350px; margin: 0;padding: 0;overflow-y: scroll">
                            <table class="table table-bordered dataTables-add-items" id="dTable-ItemList-table">
                                <thead> 
                                    <tr>
                                        
                                        <th class="text-center">Id</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                
                                    <tbody >
                                             @foreach($items as $item)

                                                <tr>
                                                    <td>{{$item->id}}</td>
                                                    <td>{{$item->name}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>{{$item->units}}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                           <a class="btn-primary btn btn-xs btn-add-items" onclick="clickToAdditem('{{$item->id}}'); return false;"><i class="fa fa-plus"></i></a>
                                                        </div>
                                                    </td>

                                                </tr>

                                            @endforeach
                                    </tbody>
                                
                            </table> 
                            </div>       
                        </div>
                        

                        <hr>

                    <div class="row">

                        <div class="col-md-12 form-horizontal">

                            <div class="ibox-tools pull-right">
                                
                                <button type="button" class="btn btn-danger btn-close "data-dismiss="modal" >Close</button>       

                            </div>

                        </div>

                    </div>
                
                </div>

            </div>
        </div>
    </div>  
  </div>  


@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
    
    var id = $('#supplier_id').val();

        $('#dTable-components-item-table tbody').empty();

                $.ajax({
                        url:  '{{ url('supplier/showitems') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: id}, 
                        success:function(results){

                            for(var i=0;i<=results.length;i++) {

                                 $('#dTable-components-item-table tbody').append("<tr>\
                                <td><input type='text' name='id[]' class='form-control input-sm text-center id' required=true size='4'  value="+ results[i].id +" readonly></td>\
                                <td>"+ results[i].name +"</td>\
                                <td>"+ results[i].description +"</td>\
                                <td>"+ results[i].units +"</td>\
                                <td style='text-align:center;'>\
                                    <div class='checkbox checkbox-success'>\
                                            <input type='checkbox' name='remove'><label for='remove'></label>\
                                    </div>\
                                </td>\
                                </tr>");

                            }          
                            
                        }
                })
        });


     function clickToAdditem(data) {

            var id = data;

                $.ajax({
                        url:  '{{ url('supplier/supplied') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: id}, 
                        success:function(results){
                                               
                            $('#dTable-components-item-table tbody').append("<tr>\
                                <td><input type='text' name='id[]' class='form-control input-sm text-center id' required=true size='4'  value="+ results.id +" readonly></td>\
                                <td>"+ results.name +"</td>\
                                <td>"+ results.description +"</td>\
                                <td>"+ results.units +"</td>\
                                <td style='text-align:center;'>\
                                    <div class='checkbox checkbox-success'>\
                                            <input type='checkbox' name='remove'><label for='remove'></label>\
                                    </div>\
                                </td>\
                            </tr>"); 

                                    toastr.success(results.description +' has been added','Success!')
                            }
                        })
                        
         }

    $(document).ready(function(){
    $('.dataTables-add-items').DataTable({
                pageLength: 10,
                responsive: true,
            
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'ExampleFile'},
                    //{extend: 'pdf', title: 'Supplied Item List'},

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

    $(document).on('click', '.btn-show-item', function() {
           $('.modal-title').text('Add Item');
           $('#myModal').modal('show');
    });

        
        $(".btn-remove").click(function(){
            $("table tbody").find('input[name="remove"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });
       
     function submit_validate() {
            var ctr = $('#dTable-components-item-table>tbody>tr').length;
            if (ctr > 0){
                $('#supplieditem_form').submit();
            }else{
                toastr.warning('No Items to be save!','Invalid!')
            }
         }



</script>

@endsection