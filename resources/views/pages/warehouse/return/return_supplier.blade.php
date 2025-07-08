
    <div class="hr-line-dashed"></div>
    <table class="table table-bordered table-hover dataTables-return" id="dataTables-return">
          <thead>
                <tr>                       
                    <th>Id</th>
                    <th>Item</th>
                    <th>Unit Qty</th>
                    <th>Return Date</th>
                    <th>Supplier</th>                     
                </tr>
                    </thead>
                        <tbody>
                            @foreach($returntosuppliers as $return)
                                <tr>
                                    <td>{{$return->id}}</td>
                                    <td>{{$return->description}}</td>
                                    <td>{{$return->unit_qty}}</td>
                                    <td>{{date('m-d-Y', strtotime($return->return_date))}}</td>
                                    <td>{{$return->supplier_name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
    </table>




 
