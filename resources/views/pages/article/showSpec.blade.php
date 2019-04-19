@extends('layouts.master')

@section('title')
    {{ $article->Title }}'s Details | Appzmate 
@endsection

@section('page-title')
    Article <b>{{ $article->Title }}</b>'s Details
@endsection

@section('content') 

    <div class="show-return">
        <a href="{{ url(Session::get('return_page')) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
    </div>

    <div class="panel panel-primary">
        <div class="panel-body">

            <div class="crude-icon">
                <div class="icon-img">
                    @if($article->Icon)
                        <img src="{{$article->Icon}}" alt="Icon Image"> 
                    @else
                        <img src="{{ url('/images/no_image_icon.png') }}" alt="NO IMAGE">
                    @endif
                </div>

                <div class="caption">
                    <b>
                    @if ($article->IconName == null)
                        NO ICON
                    @else
                        {{$article->IconName}}
                    @endif  
                    </b>  
                </div>
            </div>

            <div class="show-content">
                <div class="col-row">   
                    <div class="col-label">Category: </div> 
                    <div class="col-content">
                        {{$article['category'][0]->Name}}
                    </div>                          
                </div>

                <div class="col-row">
                    <div class="col-label">Status: </div>
                    <div class="col-content">{{$article->Status}}</div>
                </div>

                <div class="col-row">
                    <div class="col-label">Show Menu Footer: </div>
                    @if($article->MenuFooter == 1)
                        <span class="fa fa-check"></span>
                    @else
                        <span class="fa fa-close"></span>
                    @endif
                </div>

                <div class="col-row">
                    <div class="col-label">Allow Share: </div>
                    @if($article->AllowShare == 1)
                        <span class="fa fa-check"></span>
                    @else
                        <span class="fa fa-close"></span>
                    @endif
                </div>

                <div class="col-row">
                    <div class="col-label">Show Top Banner: </div>
                    @if($article->TopBannerShow == 1)
                        <span class="fa fa-check"></span>
                    @else
                        <span class="fa fa-close"></span>
                    @endif
                </div>

                <div class="col-row">
                    <div class="col-label">File Type: </div>
                    <div class="col-content">{{$article->Type}}</div>
                </div>

                <div class="col-row">  
                    <div class="col-label">Value: </div>
                    @if($article->Type == 'File' || $article->Type == 'Text')
                        <div class="col-content"><a target="_blank" href="{{$article->Value}}" download="{{$article->FileName}}">{{ $article->FileName }}</a></div>
                    @elseif($article->Type == 'LINK' || $article->Type == 'LINKExternal' || $article->Type == 'LINKLogin' || $article->Type == 'LINKInheritLogin' || $article->Type == 'LINKAutofill' || $article->Type == 'LINKCredential')
                        <div class="col-content"><a target="_blank" href="{{$article->Value}}">{{ $article->Value }}</a></div>
                    @elseif($article->Type == 'LinkedArticle')
                        <div class="col-content"><a href="{{ route('showArticle', $article->ArticleId) }}">{!! $article->LinkedArticle->Title !!}</a></div>
                    @elseif($article->Type == 'LINKPassword')
                        <div class="col-content">{!! $article->EncryptedLinkValue !!}</div>
                    @elseif($article->Type == 'CalendarEvent')
                        <div class="col-content">{!! $article->CalendarValue->Description !!}</div>
                    @else
                        <div class="col-content">{!! $article->Value !!}</div>
                    @endif    
                </div>

                @if($article->Type == 'TEXT' || $article->Type == 'FILE')
                    <div class="col-row">
                        <div class="col-label">File Name: </div>
                        <div class="col-content">{{$article->FileName}}</div>
                    </div>
                @endif

                @if($article->Type == 'GeoLocation')
                    <div class="col-row">
                        <div class="col-label">Assigned User: </div>
                        <div class="col-content">{{$article->geoLocAssignedUser->FullName}}</div>
                    </div>
                @endif

                @if($article->Type == 'CalendarEvent')
                    <div class="col-row">
                        <div class="col-label">Date Start: </div>
                        <div class="col-content">{{ $article->CalendarValue->DateStart.' '.$article->CalendarValue->TimezoneStart }}</div>
                    </div>
                    <div class="col-row">
                        <div class="col-label">Date End: </div>
                        <div class="col-content">{{ $article->CalendarValue->DateEnd.' '.$article->CalendarValue->TimezoneEnd }}</div>
                    </div>
                @endif

                @if(Auth::user()->UserGroup != 'Visitor')
                    <div class="show-action pull-right">
                        <a href="{{ route('article.edit', $article->Id) }}" class="btn btn-warning"><span class="fa fa-edit"></span> Edit</a>

                        {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['article.destroy', $article->Id],
                                'style' => 'display:inline-block;',
                            ]) !!}

                            {!! Form::button('<span class="fa fa-trash">   </span>', 
                                    array(  'id' => 'btnDel', 
                                            'class' => 'btn btn-danger',
                                            'title' => 'Delete Article?',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#confirmDelete',
                                            'data-title' => 'Delete Article: '.$article->Title,
                                            'data-message' => 'Article will be permanently deleted. Are you sure you want to delete this Article?',
                                            'data-btncancel' => 'btn-default',
                                            'data-btnaction' => 'btn-danger',
                                            'data-btntxt' => 'Confirm'
                            )) !!}

                            {!! Form::close() !!}
                    </div>
                @endif

            </div> <!-- show-content -->
        
        </div> <!-- panel body -->
    </div> <!-- panel primary -->

     @include('modals.delete')

@endsection