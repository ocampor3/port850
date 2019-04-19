@extends('layouts.master')

@section('page-title')
   ARTICLE PAGE
@endsection

@section('content')

<div class="top-btn">
	<a href="{{ route('article.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW ARTICLE </b></a>
</div>

<table class="theme">
	<tbody>
		<tr>
			<th>Article Title</th>
			<th class="div-center">Modified Date</th>
			<th>Action</th>
		</tr>	

		@foreach($article as $art)
			<tr>
			 	<td>{{$art->Title}}</td>
			 	<td class="div-center">
			 		@if ($art->ModifiedDate == null)
			 			{{$art->CreatedDate}}
			 		@else
			 			{{$art->ModifiedDate}}
			 		@endif
			 	</td>
			  	<td>
				  	<a href="{{ route('article.show', $art->Id) }}" class="btn btn-primary" role="button"><span class="fa fa-eye"></span></a>	
		        </td>
			</tr>
		@endforeach
	</tbody>
</table>


@endsection
