@extends('admin.layouts.app')
@section('header-links')

@endsection

@section('contents')

@if(Auth::check() && Auth::user()->role_id == 1)
    <?php $url_group = 'superadmin' ?>
@elseif(Auth::check() && Auth::user()->role_id == 2)
    <?php $url_group = 'admin' ?>
@elseif(Auth::check() && Auth::user()->role_id == 3)
    <?php $url_group = 'staff' ?>
@endif

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="back-btn-container">
                            
                        </div>
                        <div class="custom-heading-wrapper">
                            <h1 class="m-0 p-0 custom-heading">Edit Lecture</h1>
                            <div class="under-line-wrapper d-flex justify-content-around align-items-center">
                                <span class="left-line w-100"></span>
                                <span class="diamond"></span>
                                <span class="right-line w-100"></span>
                            </div>
                        </div>
                        <div>
                            <a class="btn btn-primary" href="{{ route($url_group.'.lecture.edit', $data->id) }}">
                                <i class="fas fa-plus"></i></i> Reload
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 from-wrapper">
                        <form action="{{ route($url_group.'.lecture.update', $data->id) }}" method="post" enctype="multipart/form-data"
                            class="submit-form-form">
                            @csrf
                            @method('PUT')
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon5">Chapter</span>
                                            </div>
                                            <select class="form-control {{($errors->first('title') ? "border border-danger" : "")}}" 
                                                name="chapter" id="">
                                                <option value="" {{ old('chapter') == '' ? 'selected' : '' }}>Select Option</option>
                                                @foreach ($chapters as $chapter)
                                                    <option value="{{ $chapter->id }}" {{ $data->chapter_id == $chapter->id ? 'selected' : '' }}>{{ $chapter->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon5">Title</span>
                                            </div>
                                            <input 
                                                type="text" 
                                                class="form-control {{($errors->first('title') ? "border border-danger" : "")}}" 
                                                name="title" value="{{ $data->title }}" 
                                                placeholder="Enter Title" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon5">Cover Type</span>
                                            </div>
                                            <div class="form-control {{($errors->first('cover_type') ? "border border-danger" : "")}} cover-type">
                                                <input 
                                                    type="radio" 
                                                    name="cover_type" value="1" id="video"  {{ $data->cover_type == 1 ? 'checked' : '' }}><label for="video">Video</label>
                                                <input 
                                                    type="radio" 
                                                    name="cover_type" value="2" id="image" {{ $data->cover_type == 2 ? 'checked' : '' }}><label for="image">Image</label>
                                                <input 
                                                    type="radio" 
                                                    name="cover_type" value="3" id="slider" {{ $data->cover_type == 3 ? 'checked' : '' }}><label for="slider">Slider</label>
                                                <input 
                                                    type="radio" 
                                                    name="cover_type" value="4" id="pdf" {{ $data->cover_type == 4 ? 'checked' : '' }}><label for="pdf">PDF</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <input type="hidden" name="old_file" value="{{ $data->file }}">
                                    <div class="form-group video {{ $data->cover_type == 1 ? 'show' : 'hide' }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon5">Video Link</span>
                                            </div>
                                            <input 
                                                type="text" 
                                                class="form-control {{($errors->first('file') ? "border border-danger" : "")}}" 
                                                name="file" value="{{ $data->file }}" placeholder="Enter Video Link">
                                        </div>
                                    </div>
                                    <div class="form-group not-video {{ $data->cover_type == 4 || $data->cover_type == 3 || $data->cover_type == 2 ? 'show' : 'hide' }}">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text" id="basic-addon5">File</span>
                                            </div>
                                            @php
                                                $accept_type = '';
                                                if ($data->cover_type == 2){
                                                    $accept_type = "image/*";
                                                }elseif ($data->cover_type == 3) {
                                                    $accept_type = "pps,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation";
                                                }elseif ($data->cover_type == 4) {
                                                    $accept_type = "application/pdf";
                                                }
                                            @endphp
                                            <input 
                                                type="file" 
                                                class="form-control {{($errors->first('file') ? "border border-danger" : "")}} file-input" 
                                                name="file"
                                                accept="{{ $accept_type }}">
                                            <div class="border border-dark d-flex justify-content-center align-items-center">
                                                <a href="{{ route($url_group.'.lecture.pdf-view', $data->id) }}" target="_blank" rel="noopener noreferrer" title="Old Pdf file"><i class="fas fa-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend w-100">
                                                <span class="input-group-text w-100" id="basic-addon5">Description</span>
                                            </div>
                                            <textarea class="form-control {{($errors->first('description') ? "border border-danger" : "")}}" 
                                                name="description"
                                                placeholder="Enter Description"
                                                id="editor" contenteditable="true">{!! $data->description !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary submit-btn submit-btn-form" data-name="form">
                                            <i class="fas fa-check"></i> Save
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.footer')
</div>
@endsection

@section('footer-links')
{{-- <script src="{{ asset('all/admin/ckeditor5/build/ckeditor.js') }}"></script> --}}
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    } );

    $(document).ready(function () {
        $('.ck-rounded-corners').addClass('w-100');

        $('.submit-btn').click(function() {
            var name = $(this).attr('data-name');
            $(".submit-btn-"+name).attr("disabled", true);
            $('.submit-btn-'+name).html('Processing...');
            $('.submit-form-'+name).submit();
            return true;
        });

        $('.cover-type input').click(function() {
            let val = $(this).val();
            if (val == 1) {
                $('.video').removeClass('hide');
                $('.video').addClass('show');
                $('.not-video').removeClass('show');
                $('.not-video').addClass('hide');
            }
            else{
                if (val == 2) {
                    $('.file-input').attr("accept", "image/*");
                }else if(val == 3){
                    $('.file-input').attr("accept", "pps,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation");
                }else if(val == 4){
                    $('.file-input').attr("accept", "application/pdf");
                }
                $('.not-video').removeClass('hide');
                $('.not-video').addClass('show');
                $('.video').removeClass('show');
                $('.video').addClass('hide');
            }
        });
    })
</script>
@endsection