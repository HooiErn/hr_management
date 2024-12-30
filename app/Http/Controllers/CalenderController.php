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
        $events = Event::all(); // Fetch all events
        return view('form.calender', compact('events'));
    }

    // Save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'nameEvent' => 'required|string|max:255',
            'eventDate' => 'required|date',
        ]);
        
        DB::beginTransaction();
        try {
            $event = new Event;
            $event->name_calender = $request->nameEvent;

            // Convert the date format to YYYY-MM-DD
            $event->date_calender = \Carbon\Carbon::createFromFormat('m/d/Y', $request->eventDate)->format('Y-m-d');

            $event->save();
            
            DB::commit();
            Toastr::success('Create new event successfully :)','Success');
            return redirect()->route('calendar')->with('success', 'Event added successfully!'); // Redirect to calendar route with success message
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Event fail: ' . $e->getMessage(), 'Error'); // Log the error message
            return redirect()->back();
        }
    }

    // Update record
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $eventName = $request->eventName;
            $eventDate = $request->eventDate;

            $update = [
                'name_calender' => $eventName,
                'date_calender' => \Carbon\Carbon::createFromFormat('m/d/Y', $eventDate)->format('Y-m-d'),
            ];

            Event::where('id', $id)->update($update);
            DB::commit();
            Toastr::success('Event updated successfully :)','Success');
            return redirect()->route('calendar')->with('success', 'Event updated successfully!'); // Redirect with success message

        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Event update fail :)','Error');
            return redirect()->back();
        }
    }

    // Delete record
    public function deleteRecord($id)
    {
        DB::beginTransaction();
        try {
            Event::destroy($id); // Delete the event by ID
            DB::commit();
            Toastr::success('Event deleted successfully :)','Success');
            return redirect()->route('calendar')->with('success', 'Event deleted successfully!'); // Redirect with success message

        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Event deletion fail: ' . $e->getMessage(), 'Error'); // Log the error message
            return redirect()->back();
        }
    }
}
