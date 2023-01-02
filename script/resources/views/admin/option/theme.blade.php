@extends('layouts.backend.app')

@section('title','Edit Site Settings')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>''])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <h4>{{ __('Site Settings') }}</h4>
                    @if (Session::has('message'))
                    <p class="alert alert-danger">
                        {{ Session::get('message') }}
                    </p>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.theme.settings.update', $theme->id) }}" enctype="multipart/form-data" class="basicform">
                  @csrf
                  @method('PUT')
                  @php 
                  $theme = json_decode($theme->value) ?? '';
                  @endphp
                  <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Logo') }}</label>
                    <div class="col-sm-12 col-md-7">
                       
                       <input type="file" class="form-control" name="logo" id="">
                       {{ __('Prev photo') }}: <img class="mt-2" src="{{ asset('uploads/logo.png') }}" alt="" width="100">
                    </div>
                 </div>
                 <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Favicon') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="file" class="form-control" name="favicon" id="">
                        {{ __('Prev Icon') }}: <img class="mt-2" src="{{ asset('uploads/favicon.ico') }}" alt=""  height="20">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Menu Top Left Button Text') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control" name="top_left_text" value="{{ $theme->top_left_text ?? '' }}" id="top_text">                
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Menu Top Left Button Link') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control" name="top_left_link" value="{{ $theme->top_left_link ?? '' }}" >                
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Menu Top Right Button Text') }}</label>
                    <div class="col-sm-12 col-md-7">
                       <input type="text" class="form-control" name="top_right_text" value="{{ $theme->top_right_text ?? '' }}" id="top_text">                
                   </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Menu Top Right Button Link') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control" name="top_right_link" value="{{ $theme->top_right_link ?? '' }}" >                
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Footer Description') }}</label>
                    <div class="col-sm-12 col-md-7">                       
                        <textarea name="footer_description" class="form-control">{{ $theme->footer_description ?? '' }}</textarea>
                    </div>
                </div>
               <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Address') }}</label>
                    <div class="col-sm-12 col-md-7">                       
                        <textarea name="newsletter_address" class="form-control">{{ $theme->newsletter_address ?? '' }}</textarea>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Social Link') }}</label>
                    <div class="col-sm-12 col-md-7">                       
                        <div class="row">
                            <div class="col-md-5"> 
                                <label for="">{{ __('Iconify Icon') }}</label> <br>
                            </div>
                            <div class="col-md-5">
                                <label for="">{{ __('Link') }}</label><br>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0);" class="add_button btn btn-outline-primary btn-block" title="Add field">{{ __('Add') }} <i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="form-group field_wrapper">
                            @foreach ($theme->social ?? [] as $key => $item)
                            <div class="row">
                                <div class="col-md-5"><br>
                                    <input type="text" value="{{ $item->icon }}" data-key="{{ $key }}" class="form-control" name="social[{{ $key }}][icon]" value='fab fa-facebook'> 
                                </div>
                                <div class="col-md-5"><br>
                                    <input type="text" value="{{ $item->link }}" class="form-control" name="social[{{ $key }}][link]" class=""> 
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <a href="javascript:void(0);" class="remove_button btn btn-danger btn-block" title="Add field"><i class="fas fa-minus"></i></a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                    <div class="col-sm-12 col-md-7">
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    'use strict';

    $(document).ready(function(){
        var x = 0; //Initial field counter is 1
        var count = 100;
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper

        //Once add button is clicked
        $(addButton).on('click',function(){
            //Check maximum number of input fields
            if(x < maxField){ 
                //Increment field counter
                var fieldHTML = `<div class='row'><div class="col-md-5"> 
                <br>
                <input type="text" required class="form-control" name="social[${count}][icon]" value=""> 
                </div>
                <div class="col-md-5">
                <br>
                <input type="text" required class="form-control" name="social[${count}][link]" class=""> 
                </div>
                <div class="col-md-2">
                <br>
                <a href="javascript:void(0);" class="remove_button btn btn-danger btn-block" title="Add field"><i class="fas fa-minus"></i></a>
                                </div><div>`; //New input field html 
                                x++;
                                count++;
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').parent('div.row').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>
@endpush




