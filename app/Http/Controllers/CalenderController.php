<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Event;
use DB;

class CalenderController extends Controller
{
    public function calender()
    {
        $event = Event::all();
        return view('form.calender',compact('event'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'nameEvent' => 'required|string|max:255',
            'EventDate' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $event = new Event;
            $event->name_calender = $request->nameEvent;
            $event->date_calender  = $request->eventDate;
            $event->save();
            
            DB::commit();
            Toastr::success('Create new event successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Event fail :)','Error');
            return redirect()->back();
        }
    }
    // update
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $id           = $request->id;
            $eventName  = $request->eventName;
            $eventDate  = $request->eventDate;

            $update = [

                'id'           => $id,
                'name_event' => $eventName,
                'date_event' => $eventDate,
            ];

            Event::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Event updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Event update fail :)','Error');
            return redirect()->back();
        }
    }
}
