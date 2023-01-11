@extends('website.layouts.app')

@section('header-links')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/assets/owl.carousel.min.css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css" />
@endsection

@section('contents')
<!--Left part Start-->
<div class="container" style="margin-top: 100px;">
    <div class="row">
        <div class="col-12">
            @if ($data->cover_type == 2)
                <img class="card-img-top" src="{{ asset('storage/'.$data->file)}}" alt="Card image cap">
            @elseif ($data->cover_type == 3)
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div id="slider-counter" class="slider-counter text-center"></div>
                    <div class="carousel-inner owl-carousel">
                        @foreach ($data->ppt_images as $ppt_image)
                            
                            <div class="carousel-item active">
                                <img class="d-block w-100 img-fluid" src="{{ asset('storage/'.$ppt_image->file)}}" alt="First slide">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
            <div class=" lecture-cover">
                @php
                    // $img = 'https://img.youtube.com/vi/';
                    // $pieces = explode('/', $item->link);
                    // $last_word = array_pop($pieces);
                    // $img = $img.$last_word.'/default.jpg'
                    $video_id = explode("?v=", $data->file);
                    $video_id = $video_id[1];
                    $thumbnail="http://img.youtube.com/vi/".$video_id."/hqdefault.jpg";
                    // $thumbnail = "";
                @endphp
                <img class="card-img-top" src="{{ $thumbnail }}" alt="Card image cap">
                <button class="m-0 p-0 open-youtube-pupup youtube-popup-btn" data-src="{{ $data->link }}"><i class="fas fa-play m-0 p-0"></i></button>
            </div>
            @endif
            <br>
            <div class="social-icon d-flex justify-content-end">
                <span class="mr-2">
                    <a href="#">Facebook</a>
                </span>
                <span class="mr-2">
                    <a href="#">Twitter</a>
                </span>
                <span>
                    <a href="#">YouTube</a>
                </span>
            </div>
            <hr>
            <h2>{{ $data->title }}</h2>
            <p class="lecture-content py-2 px-1">
                {!! $data->description !!}
            </p>
            <hr>

            <div class="row pagination-section">
                <div class="col-4">
                    <a href="{{ empty($previous) ? '' : route('lecture', ['slug' => $previous->slug]) }}" class="{{empty($previous) ? 'disabled-link' : '' }}" >Previous</a>
                </div>
                <div class="col-4 py-2 complete bg-success">
                    <a href="{{ route('user.quiz', ['slug' => $data->slug]) }}">Complete</a>
                </div>
                <div class="col-4 next">
                    <a href="{{ empty($next) ? '' : route('lecture', ['slug' => $next->slug]) }}" class="{{empty($next) ? 'disabled-link' : '' }}" >Next</a>
                </div>
            </div>
        </div>
        <!--Left part End-->
    </div>
</div>
@endsection

@section('footer-links')
<script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/owl.carousel.min.js'>
</script>
<script src="{{ asset('all/website/Js/script.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>
<script>
    $(document).ready(function() {  

        $('.open-youtube-pupup').click(function () {
            var src = $(this).attr("data-src")
            $.magnificPopup.open({
                items: {
                    src: src
                },
                type: 'iframe'
            });
        })
    });
</script>
@endsection