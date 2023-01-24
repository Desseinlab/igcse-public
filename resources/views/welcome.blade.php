@extends('website.layouts.app')

@section('contents')
<header>

    <!--Caurosal Start-->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach ($sliders as $slider)
                <div class="carousel-item {{ ++$loop->index == 1 ? 'active' : '' }}">
                    <img class="d-block w-100" src="{{ asset('storage/'.$slider->slider_image)}}" alt="First slide">
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!--Carousel Ends-->
</header>

<!-- background Image Section Start-->
<div class="container bg-image-section mt-2">
    <div class="chapter-div text-uppercase">
        <ul class="list-group  w-100  text-dark">
            @foreach ($chapters as $chapter)
                <li class="list-group-item chapter-bg p-0 m-0">
                    <a href="{{ route('chapter', ['slug' => $chapter->slug]) }}" class="d-block h-100 d-flex justify-content-center align-items-center">
                        <span>{{ $chapter->title }}</span>
                    </a>
                </li>
            @endforeach

        </ul>
    </div>
</div>
<!--background Image Section End -->

<!--About us section Start-->
<div class="container about-us mt-2">
    <div class="w-100 text-center text-uppercase">
        <h1>About Us</h1>
    </div>
    <div class="border rounded p-2 my-2">
        <div class="row">
            <div class="col-md-6">
                <div class="w-100">
                    <img class="w-100" src="{{ empty($about_us) ? asset('no-image.png') : asset('storage/'.$about_us->image) }}" alt="about img">
                </div>
            </div>
            <div class="col-md-6">
                <div class="w-100 text-justify">
                    {!! empty($about_us) ? old('details') : $about_us->details !!}
                </div>
            </div>

    </div>
    </div>
</div>
<!--About us section End-->

<!--Card Section Start -->
<div class="container my-2">
    <div class="w-100 text-center text-uppercase">
        <h1>Recent Lecture</h1>
    </div>
    <div class="row">
        @for ($i = 0; $i < (count($lectures)>=3 ? 3 : count($lectures)); $i++)
            <div class="col-sm-12 col-md-4">
                <a href="{{ route('lecture', ['slug' => $lectures[$i]->slug]) }}" class="card lecture-card">
                    @if ($lectures[$i]->cover_type == 2)
                        <img class="card-img-top" src="{{ asset('storage/'.$lectures[$i]->file)}}" alt="Card image cap">
                    @elseif ($lectures[$i]->cover_type == 3)
                        <img class="card-img-top" src="{{ asset('storage/'.$lectures[$i]->ppt_images[0]->file)}}" alt="Card image cap">
                    @elseif ($lectures[$i]->cover_type == 4)
                        <img class="card-img-top" src="{{ asset('pdf.png')}}" alt="Card image cap">
                    @else
                        @php
                            // $img = 'https://img.youtube.com/vi/';
                            // $pieces = explode('/', $item->link);
                            // $last_word = array_pop($pieces);
                            // $img = $img.$last_word.'/default.jpg'
                            $video_id = explode("?v=", $lectures[$i]->file);
                            $video_id = $video_id[1];
                            $thumbnail="http://img.youtube.com/vi/".$video_id."/hqdefault.jpg";
                            // $thumbnail = "";
                        @endphp
                        <img class="card-img-top" src="{{ $thumbnail }}" alt="Card image cap">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $lectures[$i]->chapter->title }}</h5>
                        <p class="card-text">{{ $lectures[$i]->title }}</p>
                    </div>
                </a>
            </div>
        @endfor
    </div>
</div>
<!--Card Section End -->

<!--Second Card Section Start-->
<div class="container my-3">
    <div class="row">
        @for ($i = 3; $i < (count($lectures)<3 ? 3 : count($lectures)); $i++)
            <div class="col-sm-12 col-md-6">
                <a href="{{ route('lecture', ['slug' => $lectures[$i]->slug]) }}" class="card lecture-card">
                    @if ($lectures[$i]->cover_type == 2)
                        <img class="card-img-top" src="{{ asset('storage/'.$lectures[$i]->file)}}" alt="Card image cap">
                    @elseif ($lectures[$i]->cover_type == 3)
                        <img class="card-img-top" src="{{ asset('storage/'.$lectures[$i]->ppt_images[0]->file)}}" alt="Card image cap">
                    @elseif ($lectures[$i]->cover_type == 4)
                        <img class="card-img-top" src="{{ asset('pdf.png')}}" alt="Card image cap">
                    @else
                        @php
                            dd($lectures[$i]);
                            // $img = 'https://img.youtube.com/vi/';
                            // $pieces = explode('/', $item->link);
                            // $last_word = array_pop($pieces);
                            // $img = $img.$last_word.'/default.jpg'
                            $video_id = explode("?v=", $lectures[$i]->file);
                            $video_id = $video_id[1];
                            $thumbnail="http://img.youtube.com/vi/".$video_id."/hqdefault.jpg";
                            // $thumbnail = "";
                        @endphp
                        <img class="card-img-top" src="{{ $thumbnail }}" alt="Card image cap">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $lectures[$i]->chapter->title }}</h5>
                        <p class="card-text">{{ $lectures[$i]->title }}</p>
                    </div>
                </a>
            </div>
        @endfor
    </div>
</div>
<!--Second Card Section End-->

<!--Contact us Section Start-->
<div class="container my-4">
    <div class="w-100 text-center text-uppercase">
        <h1>Contact us</h1>
    </div>
    <form action="{{ route('contact-us-submit') }}" method="Post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Name</label>
                <input type="text" class="form-control {{($errors->first('name') ? "border border-danger" : "")}}" 
                    id="name" name="name" placeholder="Your Name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control {{($errors->first('email') ? "border border-danger" : "")}}" 
                    id="email" name="email" placeholder="Your Email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger mb-1">{{ $message }}</div>
                    @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="subject">Subject</label>
                <input type="text" class="form-control {{($errors->first('subject') ? "border border-danger" : "")}}" 
                    id="subject" name="subject" placeholder="Email Subject" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="text-danger mb-1">{{ $message }}</div>
                    @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="comment">Message</label>
            <textarea class="form-control {{($errors->first('message') ? "border border-danger" : "")}}" 
                rows="5" name="message" id="comment">{{ old('message') }}</textarea>
                @error('message')
                    <div class="text-danger mb-1">{{ $message }}</div>
                @enderror
        </div>
        <button type="submit" class="btn btn-success">Send</button>
    </form>
</div>
<!--Contact us Section End-->
@endsection