@extends('layouts.backend.app')

@section('title','Seo')

@section('content')
<div class="card"  >
	<div class="card-body">
		<h4 class="mb-20">{{ __('Seo Info') }}</h4>
		<form method="post"  class="basicform" action="{{ route('admin.seo.store') }}">
			@csrf
			<div class="custom-form">
				<div class="row">
					<div class="form-group col-lg-6">
						<label for="title">{{ __('Site Title') }}</label>
						<input type="text" name="title"  id="title" class="form-control" value="{{ $info->title }}" placeholder="Site Title">
					</div>
					<div class="form-group col-lg-6">
						<label for="twitterTitle">{{ __('Twitter Title') }}</label>
						<input type="text" name="twitterTitle"  id="twitterTitle" class="form-control" value="{{ $info->twitterTitle }}" placeholder="Twiiter Title">
					</div>
					<div class="form-group col-lg-6">
						<label for="canonical ">{{ __('Canonical URL') }}</label>
						<input type="text" name="canonical"  id="canonical" class="form-control" value="{{ $info->canonical }}" placeholder="Canonical URL">
					</div>
					<div class="form-group col-lg-6">
						<label for="tags">{{ __('Tags') }}</label>
						<input type="text" name="tags"  id="tags" class="form-control" value="{{ $info->tags }}" placeholder="Tags">
					</div>
					<div class="form-group col-lg-12">
						<label for="description">{{ __('Site description') }}</label>
						<textarea name="description" id="description" class="form-control" cols="30" rows="10">{{ $info->description }}</textarea>
					</div>
					<div class="form-group col-lg-12">
						<button class="btn btn-primary col-12 basicbtn" type="submit">{{ __('Update') }}</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
