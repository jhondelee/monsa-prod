<?php

namespace App\Http\Controllers\SalesMobileTools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User as Users;
use App\EventPhoto;
use DB;

class EventPhotoController extends Controller
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
        $events = DB::select("select * from event_photo order by id desc");
        
        return view('pages.salesmobiletools.eventphoto.index',compact('events'));
    }


    public function upload_image(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'item_picture'  => 'required'
        ]);

        if ( $request->hasFile('item_picture') )  {

            $events = New EventPhoto;

            $events->name = $request->name;

            $events->remarks = $request->remarks;

            $events->created_by = auth()->user()->id;

            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('item_image'), $item_picture );    
            $events->picture = $item_picture;

            $events->save();

             return redirect()->route('event.index')
                             ->with('success','Image uploaded successfully!');
        }else{

             return redirect()->route('event.index')
                             ->with('warning','No image uploaded!');
        }


    }


    public function destroy($id)
    {
        $events = EventPhoto::find($id);

        $events->delete();
        
        return redirect()->route('event.index')
                             ->with('success','Image uploaded successfully!');
    }



}
