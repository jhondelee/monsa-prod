    <a href="{{route('returns.create')}}" class="btn btn-warning btn-sm">
    <i class="fa fa-plus">&nbsp;</i>Search SO Number</a>

    <div class="hr-line-dashed"></div>
    <table class="table table-bordered table-hover dataTables-return" id="dataTables-return">
                                        <thead>
                                        <tr>

                                            
                                            <th>Id</th>
                                            <th>Reference No.</th>
                                            <th>SO Number</th>
                                            <th>Return Date</th>
                                            <th>Customer</th>

                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($returns as $return)
                                                <tr>
                                                    <td>{{$return->id}}</td>
                                                    <td>{{$return->reference_no}}</td>
                                                    <td>{{$return->so_number}}</td>
                                                    <td>{{date('m-d-Y', strtotime($return->return_date))}}</td>
                                                    <td>{{$return->customer_name}}</td>

                                                    <td class="text-center">
                                                        @if (!can('returns.edit'))
                                                        <div class="btn-group">
                                                            <a href="{{route('returns.edit',$return->id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('returns.delete'))
                                                            <div class="btn-group">
                                                              <a class="btn-primary btn btn-xs delete" onclick="confirmDelete('{{$return->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        @endif
                                                    </td>

                                                </tr>
                                             @endforeach
    
                                                                               
                                        </tbody>

                                    </table>




 
