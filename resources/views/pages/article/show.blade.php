@extends('layouts.master')

@section('title')
    {{Session::get('SiteCode')}}'s Articles | Roche 
@endsection

@section('page-title')
   <b>{{ Session::get('SiteCode') }}</b>'s Article List
@endsection

@section('content')

	@if(Auth::user()->UserGroup != 'Visitor')
		<div class="top-btn">
			<a href="{{ route('article.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW ARTICLE </b></a>
		</div>
	@endif

	<!-- save to session the current page so we can redirect back to this page-->
	{{ Session::put('return_page',Request::url().'?page='.$article->currentPage()) }}
	
	<div class="dataTables_wrapper form-inline dt-bootstrap">
		<div class="row">
			<div class="col-sm-12">
				<div class="dataTables_length">
					<label style="font-weight:400 !important;margin-right: 100px;">
						Show
				        
				   		{!! Form::select('pageshow', ['25' => '25','50' => '50','100' => '100','250' => '250','500' => '500'],$paginate, ['id' =>'showpage' ,'class' => 'form-control input-sm', 'style' => 'width: 60px;' ,'placeholder' => '--Select Type--']) !!} 
				       	
				       	entries
			    	</label>

			    	<label style="font-weight:400 !important;margin-right: 100px;">
						Filter by Category : 

				        @if($filter_category != null)
				   			{!! Form::select('category',$category,$filter_category, ['id' =>'showcategory' ,'class' => 'form-control input-sm','style' => 'width: 150px;' ,'placeholder' => '--Select Category--']) !!}
				   		@else
				   			{!! Form::select('category',$category,null, ['id' =>'showcategory' ,'class' => 'form-control input-sm','style' => 'width: 150px;' ,'placeholder' => '--Select Category--']) !!}
				   		@endif 
				       	
			    	</label>

			    	<label style="font-weight:400 !important;">
						Filter by User : 

				        @if($filter_user != null)
				   			{!! Form::select('user',$users,$filter_user, ['id' =>'showuser' ,'class' => 'form-control input-sm','style' => 'width: 150px;' ,'placeholder' => '--Select User--']) !!}
				   		@else
				   			{!! Form::select('user',$users,null, ['id' =>'showuser' ,'class' => 'form-control input-sm','style' => 'width: 150px;' ,'placeholder' => '--Select User--']) !!}
				   		@endif 
				       	
			    	</label>
			    </div>
			</div>
		</div>
	</div>

	<table class="theme">
		<tbody>
			<tr>
				<th>Article Title</th>
				<th>File Type</th>				
				<th style="width: 20%;">Category</th>				
				<th>Status</th>
				<th>Value</th>
				<th>Modified Date</th>
				<th>Action</th>
			</tr>	

			@foreach($article as $art)
				<tr>
				 	<td>{{$art->Title}}</td>
				 	<td>{{$art->Type}}</td>				 				
					<td>{{substr($art['category'][0]->Name,0,30)}}</td>	
				 	<td>{{$art->Status}}</td>				
					
					@if($art->Type == 'DIRECTTEXT' || $art->Type == 'DIRECTTEXTFULL')
						<td>{!! $art->Value !!}</td>

					@elseif($art->Type == 'LINKPassword')
						<td>{!! $art->EncryptedLinkValue !!}</td>

					@elseif($art->Type == 'LinkedArticle')
						<td><a href="{{ route('showArticle', $art->ArticleId) }}">{!! $art->LinkedArticle->Title !!}</a></td>
					
					@elseif($art->Type == 'LINK' || $art->Type == 'LINKExternal' || $art->Type == 'LINKLogin' || $art->Type == 'LINKInheritLogin' || $art->Type == 'LINKAutofill' || $art->Type == 'LINKCredential')
				 		<td><a href="{{$art->Value}}" target="_blank">{{ $art->Value }}</a></td>

				 	@elseif($art->Type == 'File' || $art->Type == 'Text')
				 		<td><a href="{{$art->Value}}" download="'".$art->FileName."'" target="_blank">{{ $art->FileName }}</a></td>

				 	@else
				 		<td></td>
					@endif

				 	<td>
				 		@if ($art->ModifiedDate == null)
				 			{{$art->CreatedDate}}
				 		@else
				 			{{$art->ModifiedDate}}
				 		@endif
				 	</td>
				  	<td>
					  	<a href="{{ route('showArticle', $art->Id) }}" class="btn btn-primary" role="button"><span class="fa fa-eye"></span></a>	
			        </td>
				</tr>
			@endforeach
		</tbody>
	</table>

	@if ($article->items() == [])           
        <div class="none-available"> NO ARTICLES TO DISPLAY </div>            
    @endif
	
	<div class="dataTables_paginate paging_simple_numbers" style="text-align: center;">   
		{{ $article->links('vendor.pagination.bootstrap-4') }} 
    </div> 

    <script type="text/javascript">

    	//if dropdown value of show page changed
    	$('#showpage').change(function(){
    		var paginate = $(this).val();

			$.cookie("article_pageshow",paginate);
		    
		    location.reload();
		});

		//if dropdown value of category filter changed
    	$('#showcategory').change(function(){
    		var cat = $(this).val();

			$.cookie("article_categoryfilter",cat);
		    
		    location.reload();
		});

		//if dropdown value of user filter changed
    	$('#showuser').change(function(){
    		var user = $(this).val();

			$.cookie("article_userfilter",user);
		    
		    location.reload();
		});

    </script>

@endsection
