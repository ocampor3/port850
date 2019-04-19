@extends('layouts.master')

@section('title')
    Edit Site | Appzmate 
@endsection

@section('page-title')
    Editing Site <b>{{$site->Title}}</b>
@endsection

@section('content')

    {!! Form::model($site, [
        'method' => 'PATCH',
        'route' => ['site.update', $site->Id],
        'files' => 'true'
    ]) !!}

    <div id="panel-style" class="panel panel-primary">              
        <div class="panel-body">

            <div class="form-horizontal"> 
                <div class="form-group">
                    
                    <div id="imgActive1" class="col-sm-3 div-center">
                        <div class="icon-edit">
                            <a class="btn btn-success" onClick="hideImgActive(1);">Change Icon</a>
                        </div>

                        <div class="crud-icon">
                            <div class="icon-img">
                                <img src="{{$site->Icon}}" alt="Image"> 
                            </div>
                        </div>                          
                    </div>
                
                    <div id="imgReplace1" class="col-sm-3 div-center" style="display:none;">
                        <div class="icon-edit">
                            <input type="file" class="imgInput" id="fileUpload" type="file" name="images">
                        </div>

                        <div class="crud-icon">
                            <div class="icon-img">
                                <img id="newIcon" class="inputPreview" src="#" alt="your image">
                            </div>
                        </div>                            
                    </div>


                    <div class="col-sm-9">

                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">              
                            <label class="col-sm-2 control-label">Title: </label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" value="{{ $site->Title }}" maxlength="160" required />
                            
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">   
                            <label for="title" class="col-sm-2 control-label">Site URL:</label>    
                            <div class="col-sm-10">                              
                                <input type="text" value="{{ $site->SiteUrl }}" name="url" class="form-control" maxlength = "255" required />

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>       
                        </div>

                        <div class="form-group {{ $errors->has('sitecode') ? ' has-error' : '' }}">   
                            <label for="title" class="col-sm-2 control-label">Site Code:</label>    
                            <div class="col-sm-10">                              
                                <input type="text" value="{{ $site->SiteCode }}" name="sitecode" class="form-control" maxlength = "100" required />

                                @if ($errors->has('sitecode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sitecode') }}</strong>
                                    </span>
                                @endif
                            </div>       
                        </div>

                        <div class="form-group">   
                            <label for="title" class="col-sm-2 control-label">Parent Site Code:</label>    
                            <div class="col-sm-3">  
                                @if ($parentSite)                            
                                    {!! Form::select('parentid', $allSites, $parentSite->Id, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Site Code--']) !!} 
                                @else
                                    {!! Form::select('parentid', $allSites, 0, ['id' => 'Type-option', 'class' => 'form-control', 'placeholder' => '--Select Site Code--']) !!} 
                                @endif
                            </div>       
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password Required: </label>
                            <div class="col-sm-10">
                                @if($site->PasswordRequired == 1)
                                    <input type="checkbox" name="passwordrequired[]" checked/>
                                @else
                                    <input type="checkbox" name="passwordrequired[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Menu Footer: </label>
                            <div class="col-sm-10">
                                @if($site->MenuFooter == 1)
                                    <input type="checkbox" name="menufooter[]" checked/>
                                @else
                                    <input type="checkbox" name="menufooter[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Favorites: </label>
                            <div class="col-sm-10">
                                @if($site->AllowFavorites == 1)
                                    <input type="checkbox" name="allowfavorites[]" checked/>
                                @else
                                    <input type="checkbox" name="allowfavorites[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Top Banner: </label>
                            <div class="col-sm-10">
                                @if($site->TopBannerShow == 1)
                                    <input type="checkbox" name="topbannershow[]" checked/>
                                @else
                                    <input type="checkbox" name="topbannershow[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Hamburger Footer: </label>
                            <div class="col-sm-10">
                                @if($site->HamburgerFooter == 1)
                                    <input type="checkbox" name="hamburgerfooter[]" checked/>
                                @else
                                    <input type="checkbox" name="hamburgerfooter[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show In Login: </label>
                            <div class="col-sm-10">
                                @if($site->ShowInLogin == 1)
                                    <input type="checkbox" name="showinlogin[]" checked/>
                                @else
                                    <input type="checkbox" name="showinlogin[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">   
                            <label for="title" class="col-sm-2 control-label">Status:</label>    
                            <div class="col-sm-4">                              
                                {!! Form::select('status', ['Live' => 'Live', 'Test' => 'Test', 'New' => 'New', 'TurnOff' => 'Turn Off'], $site->Status, 
                                    ['id' => 'col-half-width', 'class' => 'form-control', 
                                    'placeholder' => '--Select Status--', 'required']) !!} 
                            </div>       
                        </div>

                        <div class="form-group">   
                            <label for="title" class="col-sm-2 control-label">View Type:</label>    
                            <div class="col-sm-4">                              
                                {!! Form::select('viewtype',['None' => 'None', 'GridView' => 'Grid View','ListView' => 'List View', 'WindowsView' => 'Windows View'], $site['theme']->ViewType, 
                                    ['id' => 'col-half-width', 'class' => 'form-control', 
                                    'placeholder' => '--Select View Type--','required']) !!} 
                            </div>       
                        </div>

                        <div class="form-group">   
                            <label for="title" class="col-sm-2 control-label">Subview Type:</label>    
                            <div class="col-sm-4">                              
                                {!! Form::select('subviewtype',['None' => 'None', 'GridView' => 'Grid View', 'ListView' => 'List View', 'WindowsView' => 'Windows View'], $site['theme']->SubViewType, 
                                    ['id' => 'col-half-width', 'class' => 'form-control', 
                                    'placeholder' => '--Select View Type--', 'required']) !!} 
                            </div>       
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Is Article: </label>
                            <div class="col-sm-10">
                                @if($article!=null)
                                    <input type="checkbox" id="isArticle" name="isArticle[]" checked/>
                                @else
                                    <input type="checkbox" id="isArticle" name="isArticle[]" />
                                @endif
                            </div>
                        </div>

                        @if($article!=null)
                        <div id="Article" class="site-show">
                        @else
                        <div id="Article" class="site-hide">
                        @endif
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Article: </label>
                                <div class="col-sm-10">
                                {!! Form::select('articleId', $allArticles, $article != null ? $article->Id: null, ['id' => 'type-option', 'class' => 'form-control selArticle', 'placeholder' => '--Select Article--']) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="form-group">   
                    <label for="title" class="col-sm-2 control-label"></label>    
                    <div class="col-sm-10">                              
                        <input type="submit" class="btn btn-success" value="Update"> 
                        <a href="{{ route('site.index') }}" class="btn btn-danger">Cancel</a>
                    </div>       
                </div>

            </div> <!-- form horizontal -->

        </div> <!-- panel body -->
    </div> <!-- primary panel -->

    {!! Form::close() !!}
    <script type="text/javascript">

        $("#isArticle").change(function() {
            if($("#isArticle:checked").val()) {
                // show article combo box
                $('#Article').removeClass('site-hide').addClass('site-show');
                $(".selArticle").attr('required',true);
            } else {
                // hide article combo box
                $('#Article').removeClass('site-show').addClass('site-hide');
                $(".selArticle").attr('required',false);
            }
        });

        //------------------ IMAGE PREVIEW -------------//
        function readURL() {
            var $input = $(this);      

            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

            if (extn == "png" || extn == "jpg" || extn == "jpeg") 
            {
                if (typeof (FileReader) != "undefined") 
                {            
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#newIcon').attr('src', e.target.result).show();
                            $(".imgPreview").hide();
                        }
                        reader.readAsDataURL(this.files[0]);                
                    }
                } 
                else 
                {
                    alert("This browser does not support FileReader.");
                }
            } 
            else
            {
                $input.next('.inputPreview').hide();
                $(this)[0].value = '';

                alert("Please select images only.");
            }
        }

        $(".imgInput").change(readURL);


        // ------- HIDING PREVIEW TO CHANGE IMAGE -------- //        
        function hideImgActive($id){    
            $("#imgActive"+$id).hide();
            $("#imgReplace"+$id).show();
        }
    </script>

@endsection
