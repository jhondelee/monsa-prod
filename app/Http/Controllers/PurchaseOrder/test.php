 

                        for( var i = 0 ; i <= results.length ; i++ ) {

                        $('#dTable-ItemList-table tbody').append("<tr><td class="text-center">results[i].id</td>\
                                <td>results[i].item_name</td>\
                                <td class="text-center">results[i].units</td>\
                                <td class="text-right">results[i].onhand_quantity</td>\
                                <td class="text-right">results[i].unit_cost</td>\
                                <td class="text-right">results[i].status</td>\
                                <td class="text-center">\
                                    <div class='btn-group'>\
                                        <a class='btn-primary btn btn-xs btn-add-items' onclick='confirmAddItem(results[i].id); return false;'><i class='fa fa-plus'></i></a>\
                                    </div>\
                                </td>\
                            </tr>");
                        }


         $(document).on('click', '.btn-show-item', function() {
            var id = $('#supplier_id').val();
            toastr.warning('YES YES YES'+id,'Notification!')
             if (id > 0) {

                    $.ajax({
                    url:  '{{ url('order/supplieritems') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: id}, 
                    success:function(results){

                             for( var i = 0 ; i <= results.length ; i++ ) {

                            $('#dTable-ItemList-table tbody').append("<tr><td class="text-center">results[i].id</td>\
                                <td>results[i].item_name</td>\
                                <td class="text-center">results[i].units</td>\
                                <td class="text-right">results[i].onhand_quantity</td>\
                                <td class="text-right">results[i].unit_cost</td>\
                                <td class="text-right">results[i].status</td>\
                                <td class="text-center">\
                                    <div class='btn-group'>\
                                        <a class='btn-primary btn btn-xs btn-add-items' onclick='confirmAddItem(results[i].id); return false;'><i class='fa fa-plus'></i></a>\
                                    </div>\
                                </td>\
                            </tr>");
                        }

                        
                            $('.modal-title').text('Add Item');
                            $('#myModal').modal('show'); 
                  
                        }   
                    }
                    
                } else {

                    toastr.warning('Please select supplier first','Notification!')
                }

        });