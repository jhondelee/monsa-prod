<?php

namespace App\Http\Controllers\SalesMobileTools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User as Users;
use App\Brochure;
use Response;
use DB;


class BrochureController extends Controller
{
    public function __construct(
            Users $user
        )
    {
        $this->user = $user;
        $this->middleware('auth');  
    }


    public function index()
    {
        $brochures = DB::select("select * from brochures order by id desc");
        
        return view('pages.salesmobiletools.brochure.index',compact('brochures'));
    }


    public function upload_file(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'item_picture'  => 'required'
        ]);

        if ( $request->hasFile('item_picture') )  {

            $brochure = New Brochure;

            $brochure->name = $request->name;

            $brochure->remarks = $request->remarks;

            $brochure->created_by = auth()->user()->id;

            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('uploaded_file'), $item_picture );    
            $brochure->docs = $item_picture;


            $brochure->save();

             return redirect()->route('brochure.index')
                             ->with('success','File uploaded successfully!');
        }else{

             return redirect()->route('brochure.index')
                             ->with('warning','No File uploaded!');
        }


    }


    public function destroy($id)
    {
 
        $brochure = Brochure::find($id);

        $brochure->delete();
        
        return redirect()->route('brochure.index')
                             ->with('success','File delete successfully!');
    }

    public function getDownload($id)
    {
        $brochure = Brochure::find($id);

        $file_name = $brochure->name;

        $file_path = $brochure->docs;

        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/uploaded_file/$file_path";

        $headers = array(
                  'Content-Type: application/pdf',
                );

        return Response::download($file,$file_name, $headers);
    }

}
