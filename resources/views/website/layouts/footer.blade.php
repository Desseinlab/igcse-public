@php
    $system_info = system_info();
@endphp
<!--Footer Start-->
<div class="container-fluid mt-5 footer py-2">
    <div class="container row">
        <div class="col-md-6">
            <h1 class="text-light">IGCSE</h1>
            <ul class="list-group icon-list">
                <li>{{ $system_info->address}}</li>
                <li>{{ $system_info->email}}</li>
                <li>{{ $system_info->phone}}</li>
               
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li><a style="color: white, text-decoration: none" class="nav-link" href="{{route('about')}}">About</a></li>
                <li><a style="color: white, text-decoration: none" class="nav-link" href="{{ route('all-chapter') }}">Chapter</a></li>
                <li><a style="color: white, text-decoration: none" class="nav-link" href="{{route('contact')}}">Contact</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group icon-list">
                <li><a style="color: white; text-decoration: none" href="{{ $system_info->facebook_link }}" target="_blank" >Facebook</a></li>
                <li><a style="color: white; text-decoration: none" href="{{ $system_info->twitter_link }}" target="_blank" >Instagram</a></li>
                <li><a style="color: white; text-decoration: none" href="{{ $system_info->instagram_link }}" target="_blank" >twitter</a></li>
            </ul>
        </div>
    </div>
</div>
<!--Footer End-->