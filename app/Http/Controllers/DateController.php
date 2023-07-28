<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DateController extends Controller
{
    public function admin()
    {
        $date = Date::get();
        return view('admin.date.admin', compact('date'));
    }
    public function addDate()
    {
        return view('admin.date.addDate');
    }
    public function editDate($id)
    {
        $event = Date::where('uuid', $id)->first();
        return view('admin.date.editDate', compact('event'));
    }
    public function addNewDate(Request $request)
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
                $data = Date::where('uuid', $uuid)->first();
            } else {
                $data = new Date();
                $data->uuid = Str::uuid();
            }
            $image = $request->EventImage;
            if ($image) {
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $image->move('assets/images/date', $imagename);
                $data->image = $imagename;
            }
            $data->name = $request->EventName;
            $data->place = $request->EventPlace;
            $data->date = $request->EventDate;
            $data->description = $request->EventDescription;
            $save = $data->save();
            if ($save) {
                return response()->json(['success' => '/date']);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
    public function deleteDate($uuid){
        $data = Date::where('uuid', $uuid)->delete();
        return redirect()->back();
    }
}
