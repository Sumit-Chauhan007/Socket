<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function admin()
    {
        $events = Events::get();
        return view('admin.event.admin', compact('events'));
    }
    public function addEvent()
    {
        return view('admin.event.addEvent');
    }
    public function editEvent($id)
    {
        $event = Events::where('uuid', $id)->first();
        return view('admin.event.editEvent', compact('event'));
    }
    public function addDate()
    {
        return view('admin.event.addDate');
    }
    public function addNewEvent(Request $request)
    {
        $uuid = $request->id;
        $rules = [
            'EventName' => 'min:5',
            'EventPlace' => 'min:5',
            'EventDate' => 'min:5',
            'EventDescription' => 'min:5 | max:150 ',
        ];
        if(!$uuid){
            $rules['EventImage'] =  'mimes:jpeg,jpg,png|max:2000|required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $uuid = $request->id;
            if ($uuid) {

                $data = Events::where('uuid', $uuid)->first();
            } else {
                $data = new Events();
                $data->uuid = Str::uuid();
            }
            $image = $request->EventImage;
            if ($image) {
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->move('assets/images/event', $imagename);
                $data->image = $imagename;
            }
            $data->name = $request->EventName;
            $data->place = $request->EventPlace;
            $data->date = $request->EventDate;
            $data->description = $request->EventDescription;
            $save = $data->save();
            if ($save) {
                return response()->json(['success' => '/event']);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
    public function deleteEvent($uuid){
        $data = Events::where('uuid', $uuid)->delete();
        return redirect()->back();
    }
}
