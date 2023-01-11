<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Chapter;
use App\Models\AboutUs;
use App\Models\Lecture;
use App\Models\Contact_us;
use App\Models\Result;

use Auth;

class PublicController extends Controller
{
    public function welcome()
    {
        // dd(Chapter::select('id','title','slug', 'status')->where('status', 1)->get());
        // dd();
        return view('welcome',
            [
                'sliders' => Slider::where('status', 1)->get(),
                'chapters' => Chapter::latest()->where('status', 1)->get(),
                'about_us' => AboutUs::first(),
                'lectures' => Lecture::latest()->take(5)->with('ppt_images')->where('status', 1)->get(),
            ]
        );
    }

    public function about_us()
    {
        // dd(Chapter::select('id','title','slug', 'status')->where('status', 1)->get());
        // dd();
        return view('website.pages.about',
            [
                'about_us' => AboutUs::first(),
            ]
        );
    }

    public function contact()
    {
        // dd(Chapter::select('id','title','slug', 'status')->where('status', 1)->get());
        // dd();
        return view('website.pages.contact');
    }

    public function chapter($slug)
    {
        return view('website.pages.chapter',
            [
                'from' => 'single-chapter',
                'id' => Chapter::where('slug', $slug)->first()->id,
                'datas' => Chapter::where('status', 1)->with('lectures')->get(),
            ]
        );
    }

    public function all_chapter(Type $var = null)
    {
        return view('website.pages.chapter',
            [
                'from' => 'all-chapter',
                'id' => 0,
                'datas' => Chapter::where('status', 1)->with('lectures')->get(),
            ]
        );
    }

    public function lecture($slug)
    {

        $lecture = Lecture::where('slug', $slug)->with('chapter')->first();


        $chapter = Chapter::first();
        $chapter_one_lecture_one = Lecture::where('chapter_id', $chapter->id)->first();
        // return Lecture::where('id', '<', $lecture->id)->where('status', 1)->first();
        if((!Auth::check() || Auth::check()) && $lecture->chapter->id == $chapter->id && $chapter_one_lecture_one->id == $lecture->id){
            // return "here";
            return view('website.pages.lecture',
                [
                    'data' => Lecture::where('slug', $slug)->with('chapter')->first(),
                    'previous' => Lecture::where('chapter_id', $lecture->chapter_id)->where('id', '<', $lecture->id)->where('status', 1)->first(),
                    'next' => Lecture::where('chapter_id', $lecture->chapter_id)->where('id', '>', $lecture->id)->where('status', 1)->first(),
                ]
            );
        }elseif (!Auth::check() && $chapter_one_lecture_one->id != $lecture->id) {
            return redirect()->route('login')->with('error','Upps!! You have to login first.');
        }else {
            
            $result_data = Result::where('user_id', Auth::user()->id)->first();
            if (empty($result_data)) {
                // return "sff0";
                $first_lecture = $chapter_one_lecture_one;
                return redirect()->route('lecture', ['slug' => $first_lecture->slug])
                            ->with('error', 'At Starting You have to complete first chapter lecture one');

            }else {
                // return $result_data;
                $result_lecture = Lecture::find($result_data->lecture_id);
                if ($result_data->status == 0) {
                    $result_lecture = $chapter_one_lecture_one;
                    return redirect()->route('lecture', ['slug' => $result_lecture->slug])
                                ->with('error', 'You have not passed this lecture. First complete it.');
                }
            }

            if (!empty($result_data)) {
                $next_lecture = Lecture::where('chapter_id', $result_lecture->chapter_id)->where('id', '>', $result_lecture->id)->where('status', 1)->first();
                if ($next_lecture->id == $lecture->id) {
                    return view('website.pages.lecture',
                        [
                            'data' => $next_lecture,
                            'previous' => Lecture::where('chapter_id', $next_lecture->chapter_id)->where('id', '<', $next_lecture->id)->where('status', 1)->first(),
                            'next' => Lecture::where('chapter_id', $next_lecture->chapter_id)->where('id', '>', $next_lecture->id)->where('status', 1)->first(),
                        ]
                    );
                }else {
                    return redirect()->route('lecture', ['slug' => $next_lecture->slug])
                                ->with('error', 'You have not completed this lecture. First complete it.');
                }
            }
            return view('website.pages.lecture',
                [
                    'data' => $lecture,
                    'previous' => Lecture::where('chapter_id', $lecture->chapter_id)->where('id', '<', $lecture->id)->where('status', 1)->first(),
                    'next' => Lecture::where('chapter_id', $lecture->chapter_id)->where('id', '>', $lecture->id)->where('status', 1)->first(),
                ]
            );

        }

        return "here";
        
    }


    public function contact_us_submit(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:255|string',
            'email' => 'required|email|max:255|string',
            'subject' => 'required|max:255|string',
            'message' => 'required|max:500|string'
        ]);

        $data = New Contact_us;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->subject = $request->subject;
        $data->message = $request->message;
        $data->save();

        if ($data) {
            return redirect()->back()->with('success','Message Sent Successfully');
    	}else{
    		return redirect()->back()->with('error','Upps!! Something Error.');
    	}
    }
}
