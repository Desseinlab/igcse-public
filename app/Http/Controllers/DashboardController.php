<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SlugController;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use App\Models\User;
use App\Models\Lecture;
use App\Models\MCQ;
use App\Models\Result;
use App\Models\Chapter;
use Auth;
use File;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard',
            [
                'total_chapter' => Chapter::where('status', 1)->count(),
                'total_lecture' => Lecture::where('status', 1)->count(),
                'results' => Result::select('user_id', 'lecture_id', 'mcq_ids', 'total_correct', 'total_wrong', 'status')
                            ->with('lecture')
                            ->where('user_id', Auth::user()->id)->get()
            ]
        );
    }

    public function profile()
    {
        return view('user.pages.profile');
    }

    public function profile_update(Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'name' => 'required|max:250',
            'phone' => 'nullable|max:250',
            'email' => [
                'required',
                Rule::unique('users', 'email')->ignore(Auth::user()->id),
            ],
            'address' => 'nullable|max:250',
            'avatar' => 'nullable'
        ]);

        // return $request->all();

        try{
            DB::beginTransaction(); //transaction start
    
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            

            $avatar = $request->old_avatar;
            if(request()->hasFile('avatar') ){

                if($request->old_avatar){
                    if(File::exists('storage/'.$request->old_avatar)) {
                        unlink('storage/'.$request->old_avatar);
                    }
                }

                $slug = $to_store_name = app('App\Http\Controllers\Admin\SlugController')->making_slug($request->system_name);
                // Get filename with the extension
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filenameWithExt = str_replace(' ', '', $filenameWithExt);
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('avatar')->getClientOriginalExtension();
                // Filename to store
                $image= 'user/avatar/'.$slug.'.'.$extension;
                // Upload Image
                $path = $request->file('avatar')->storeAs('public', $image);
                // $resize = Image::make('storage/'.$image)->resize(360,200);
                // $resize->save();
    
                $avatar = $image;
            }

            $user->avatar = $avatar;
            $user->save();
    
            DB::commit(); //transaction end
    
            return redirect()->back()->with('success','Data Updated Successfully');

        }
        catch (\Exception $e) {
            // return $e->getMessage();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function password_update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['required','same:new_password'],
        ]);
   
        User::find(Auth::user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->back()->with('success','Password Updated Successfully');
    }

    public function counrse_info()
    {
        return view('user.pages.counrse-info',
            [
                'results' => Result::select('user_id', 'lecture_id', 'mcq_ids', 'total_correct', 'total_wrong', 'status')
                            ->with('lecture')
                            ->where('user_id', Auth::user()->id)->get()
            ]
        );
    }

    public function quiz($slug)
    {
        // return "Not Yet Done";
        $lecture = Lecture::where('slug', $slug)->with('chapter')->first();

        return view('user.pages.quiz',
            [
                'lecture' => $lecture,
                'datas' => MCQ::where('lecture_id', $lecture->id)->get()
            ]
        );
    }
}
