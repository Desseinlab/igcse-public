<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SlugController;
use App\Models\SystemInfo;
use App\Models\Contact_us;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use File;
date_default_timezone_set("Asia/Dhaka");

class SettingController extends Controller
{
    public function system_info()
    {
        $row = SystemInfo::where('id', 1)->first();
        return view('admin.pages.settings.system-info', compact('row'));
    }

    public function system_info_update(Request $request)
    {
        $this->validate($request,[
            'system_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'system_name' => 'required|max:255|string',
            'email' => 'required|email|max:255|string',
            'phone' => 'required',
            'address' => 'required|max:255|string'
        ]);

        try {

            $system_logo = $request->old_system_logo;
            if(request()->hasFile('system_logo') ){

                if($request->old_system_logo){
                    if(File::exists('storage/'.$request->old_system_logo)) {
                        unlink('storage/'.$request->old_system_logo);
                    }
                }

                $slug = $to_store_name = app('App\Http\Controllers\Admin\SlugController')->making_slug($request->system_name);
                // Get filename with the extension
                $filenameWithExt = $request->file('system_logo')->getClientOriginalName();
                $filenameWithExt = str_replace(' ', '', $filenameWithExt);
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('system_logo')->getClientOriginalExtension();
                // Filename to store
                $image= 'system-info/'.$slug.'.'.$extension;
                // Upload Image
                $path = $request->file('system_logo')->storeAs('public', $image);
                // $resize = Image::make('storage/'.$image)->resize(360,200);
                // $resize->save();
    
                $system_logo = $image;
            }

            // $this->buildXMLHeader();
            DB::beginTransaction(); //transaction start
            $data = SystemInfo::where('id', 1)->first();
            $data->system_logo = $system_logo;
            $data->system_name = $request->system_name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->facebook_link = $request->facebook_link;
            $data->twitter_link = $request->twitter_link;
            $data->instagram_link = $request->instagram_link;
            $data->passing_percentage = $request->passing_percentage;
            $data->save();
            DB::commit(); //transaction end

            if ($data) {
                return redirect()->back()->with('success', 'Data Updated successfully');
            }else{
                return redirect()->back()->with('error','Upps!! Something Error.');
            }
        } 
        catch (\Exception $e) {
            // return $e->getMessage();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function contact_us()
    {
        $datas = Contact_us::all();
        return view('admin.pages.settings.contact_us', compact('datas'));
    }
}
