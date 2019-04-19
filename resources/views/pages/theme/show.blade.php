@extends('layouts.master')

@section('title')
    {{ Session::get('SiteCode') }}'s Theme | Appzmate 
@endsection

@section('page-title')
   <b>{{ Session::get('SiteCode') }}</b>'s Theme
@endsection

@section('content')

    <div class="show-return">
        <a href="{{ route('site.show', Session::get('SiteCode')) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
    </div>

    <div class="form-horizontal">
        <div class="pg-categories-blue">
            IMAGE ASSETS
        </div>

        <div class="form-group">
            <!-- site icon -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($siteDet->Icon)
                            <img src="{{ $siteDet->Icon }}" alt="site icon">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="site icon">
                        @endif
                    </div>

                    <div class="caption">
                        Site Icon
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('edit-site-icon',$siteDet->Id) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>
            
            <!-- background image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_bg']->Contents)
                            <img src="{{ $theme['img_bg']->Contents }}" alt="background image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="background image">  
                        @endif
                    </div>

                    <div class="caption">
                        Background Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'bg_img','Background Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- banner image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_banner'])
                            <img src="{{ $theme['img_banner']->Contents }}" alt="banner image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="banner image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Banner Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'banner_img','Banner Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- arrow back -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_arrowback'])
                            <img src="{{ $theme['img_arrowback']->Contents }}" alt="arrow back image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="arrow back image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Arrow Back Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'arrow_back_img','Arrow Back Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- arrow collpase -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_arrowcollapse'])
                            <img src="{{ $theme['img_arrowcollapse']->Contents }}" alt="arrow collapse image"> 
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="arrow collapse image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Arrow Collapse Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'arrow_collapse_img','Arrow Collapse Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- arrow expand -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_arrowexpand'])
                            <img src="{{ $theme['img_arrowexpand']->Contents }}" alt="arrow expand image"> 
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="arrow expand image">  
                        @endif

                         
                    </div>

                    <div class="caption">
                        Arrow Expand Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'arrow_expand_img','Arrow Expand Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- arrow next -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_arrownext'])
                            <img src="{{ $theme['img_arrownext']->Contents }}" alt="arrow next image">
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="arrow next image">  
                        @endif

                         
                    </div>

                    <div class="caption">
                        Arrow Next Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'arrow_next_img','Arrow Next Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- hamburger image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_hamburger'])
                            <img src="{{ $theme['img_hamburger']->Contents }}" alt="hamburger icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="hamburger icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Hamburger Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'hamburger_img','Hamburger Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- Home Image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_home'])
                            <img src="{{ $theme['img_home']->Contents }}" alt="home icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="home icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Home Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'home_img','Home Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>
            
            <!-- mail image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_mail'])
                            <img src="{{ $theme['img_mail']->Contents }}" alt="mail icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="mail icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Mail Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'mail_img','Mail Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- scroll btm image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_scrollbtm'])
                            <img src="{{ $theme['img_scrollbtm']->Contents }}" alt="scroll bottom icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="scroll bottom icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Scroll Bottom Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'scroll_btm_img','Scroll Bottom Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- scroll top image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_scrolltop'])
                            <img src="{{ $theme['img_scrolltop']->Contents }}" alt="scroll top icon image">
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="scroll top icon image">  
                        @endif
                          
                    </div>

                    <div class="caption">
                        Scroll Top Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'scroll_top_img','Scroll Top Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- search image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_search'])
                            <img src="{{ $theme['img_search']->Contents }}" alt="search icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="search icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Search Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'search_img','Search Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

             <!-- filter image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_filter'])
                            <img src="{{ $theme['img_filter']->Contents }}" alt="filter icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="filter icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Filter Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'filter_img','Filter Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>


            <!-- share image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_share'])
                            <img src="{{ $theme['img_share']->Contents }}" alt="share icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="share icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Share Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'share_img','Share Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- sync image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_sync'])
                            <img src="{{ $theme['img_sync']->Contents }}" alt="sync icon image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="sync icon image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Sync Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'sync_img','Sync Icon Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- logo image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_logo'])
                            <img src="{{ $theme['img_logo']->Contents }}" alt="logo image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="logo image">  
                        @endif
                    </div>

                    <div class="caption">
                        Logo Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'logo_img','Logo Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div>
                </div>
            </div>

            <!-- main screen box image -->
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div class="img-resize change-bg">
                        @if($theme['img_mainscreen'])
                            <img src="{{ $theme['img_mainscreen']->Contents }}" alt="main screen box image">  
                        @else
                            <img src="{{ url('/images/no_image_icon.png') }}" alt="main screen box image">  
                        @endif
                        
                    </div>

                    <div class="caption">
                        Main Screen Box Image
                    </div>

                    <div class="edit-field">
                        <a href="{{ route('editImagefield',[Session::get('ThemeId'),'main_screen_box_img','Main Screen Box Image']) }}" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Edit</a>   
                    </div> 
                </div>
            </div>
        </div> <!-- form group -->
    </div> <!-- form horizontal-->


    <div class="form-horizontal">
        <div class="pg-categories-blue">
            TEXT ASSETS
            <div class="pull-right">
                <a href="{{ route('editContent',[Session::get('ThemeId')]) }}" class="theme-btn"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            </div>
        </div>
    </div> <!-- form horizontal -->

    <table class="theme">
        <tbody>
            <tr>
                <th class="width-quarter">Field Name</th>
                <th>Value/s</th>
                <th></th>
            </tr> 

            <tr>
                <td>Button Color</td>
                <td>{{$theme->ButtonColor}}</td>
                <td><div class="theme-val-color" style="background-color: {{$theme->ButtonColor}};"></td>
            </tr> 

            <tr>
                <td>Separator Color</td>
                <td>{{$theme->SeparatorColor}}</td>
                <td><div class="theme-val-color" style="background-color:{{$theme->SeparatorColor}};"></td>
            </tr>

            <tr>
                <td>Text Color</td>
                <td>{{$theme->TextColor}}</td>
                <td><div class="theme-val-color" style="background-color:{{$theme->TextColor}};"></td>
            </tr>

            <tr>
                <td class="theme-field-name">About Title</td>
                <td colspan="2">{{$theme->AboutTitle}}</td>
            </tr>

            <tr>
                <td class="theme-field-name">About Content</td>
                <td colspan="2">{!! $theme->AboutContent !!}</td>
            </tr>

            <tr>
                <td class="theme-field-name">Contact Title</td>
                <td colspan="2">{{$theme->ContactTitle}}</td>
            </tr>

            <tr>
                <td class="theme-field-name">Contact Content</td>
                <td colspan="2">{!! $theme->ContactContent !!}</td>
            </tr>

            <tr>
                <td class="theme-field-name">Facts Title</td>
                <td colspan="2">{{$theme->FactsTitle}}</td>
            </tr>

            <tr>
                <td class="theme-field-name">Facts Content</td>
                <td colspan="2">{!! $theme->FactsContent !!}</td>
            </tr>

            <tr>
                <td class="theme-field-name">View Type</td>
                <td colspan="2">{{$theme->ViewType}}</td>
            </tr>

            <tr>
                <td class="theme-field-name">SubView Type</td>
                <td colspan="2">{{$theme->SubViewType}}</td>
            </tr>
        </tbody>
    </table>

@endsection