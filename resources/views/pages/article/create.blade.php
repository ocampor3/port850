@extends('layouts.master')

@section('title')
Create Article | Appzmate 
@endsection

@section('page-title')
Create Article
@endsection

@section('content')

{!! Form::open([      
    'method' => 'POST',
    'action' => 'ArticleController@store',
    'files' => 'true',
    'onsubmit' => 'return validateURL();',
    ]) !!}

    <div class="panel panel-primary">       
        <div class="panel-body">
            <div class="form-horizontal">

                <div class="form-group"> <!-- FORM group first  --> 
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Icon: </label>
                            <div class="col-sm-10">
                                <input class="imgInput" type="file" name="icon">
                                <img class="inputPreview" src="#" alt="your image">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">              
                            <label class="col-sm-2 control-label">Title: </label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" maxlength="160" required />

                                @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category: </label>
                            <div class="col-sm-10">
                                {!! Form::select('categoryId', $category, null, ['class' => 'form-control', 'placeholder' => '--Select Category--', 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status: </label>
                            <div class="col-sm-3">
                                {!! Form::select('status', ['Live' => 'Live', 'Test' => 'Test', 'New' => 'New', 'TurnOff' => 'Turn Off'], null, ['class' => 'form-control', 'placeholder' => '--Select Status--' ,'required']) !!} 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Menu Footer: </label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="menufooter" value="1"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Share: </label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="allowshare" value="1"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Top Banner: </label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="topbannershow" value="1"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">File Type: </label>
                            <div class="col-sm-3">
                                {!! Form::select('type', ['Text' => 'Text File', 'File' => 'File Browse', 'DIRECTTEXT' => 'Direct Text', 'DIRECTTEXTFULL' => 'Direct Text Full', 'LINK' => 'Link', 'LINKExternal' => 'External Link', 'LINKInheritLogin' => 'Inherit Login Link', 'LINKLogin' => 'Login Link', 'LINKAutofill' => 'Auto Fill Link', 'LINKCredential' => 'Credential Link', 'LINKPassword' => 'Link with Password', 'GeoLocation' => 'Geo Location', 'CalendarEvent' => 'Calendar Event', 'LinkedArticle' => 'Linked Article'], null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Type--' ,'required']) !!} 
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('order') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">Sort Order: </label>
                            <div class="col-sm-2">
                                <input class="form-control" name="order" placeholder="0" min="1" type="number" required>
                                
                                @if ($errors->has('order'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('order') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div id="Text" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Value: </label>
                                <div class="col-sm-10">
                                    <input class="browsefile selText" type="file" name="textfile">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">File Name: </label>
                                <div class="col-display">
                                    <span class="filename_upload"></span>
                                </div>
                            </div>
                        </div> <!-- filetext -->

                        <div id="File" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Value: </label>
                                <div class="col-sm-10">
                                    <input class="browsefile selFile" type="file" name="browsefile">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">File Name: </label>
                                <div class="col-display">
                                    <span class="filename_upload"></span>
                                </div>
                            </div>
                        </div> <!-- filebrowse -->

                        <div id="DIRECTTEXT" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Value: </label>
                                <div class="col-sm-10">
                                    <textarea id="wysiwyg" class="form-control selDirText" name="value[DIRECTTEXT]" row="5" style="font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; resize: none;" placeholder="Enter text..."></textarea>
                                </div>
                            </div>
                        </div> <!-- directText -->

                        <div id="LINK" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-10">
                                    <input id="filelinkLINK" type="text" class="form-control selLinkNorm" name="value[LINK]">
                                    <span id="hb-LINK" style="display:none;" class="help-block"></span>
                                </div>
                            </div>
                        </div> <!-- link normal -->

                        <div id="LINKExternal" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-10">
                                    <input id="filelinkLINKExternal" type="text" class="form-control selLinkExt" name="value[LINKExternal]">
                                    <span id="hb-LINKExternal" style="display:none;" class="help-block"></span>
                                </div>
                            </div>
                        </div> <!-- link external -->

                        <div id="LINKLogin" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-10">
                                    <input id="filelinkLINKLogin" type="text" class="form-control selLinkLog" name="value[LINKLogin]">
                                    <span id="hb-LINKLogin" style="display:none;" class="help-block"></span>
                                </div>
                            </div> 
                        </div> <!-- link Login -->

                        <div id="LINKInheritLogin" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-10">
                                    <input id="filelinkLINKInheritLogin" type="text" class="form-control selLINKInherit" name="value[LINKInheritLogin]">
                                    <span id="hb-LINKInheritLogin" style="display:none;" class="help-block"></span>
                                </div>
                            </div> 
                        </div> <!-- link inherit Login -->

                        <div id="LINKAutofill" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-2">
                                    {!! Form::select('protocolLINKAutofill', ['http://' => "http://", 'https://' => "https://"], null, ['id' => 'protocolLINKAutofill','class' => 'form-control selLinkAuto', 'placeholder' => '--Select Type--']) !!} 
                                </div>

                                <div class="col-linkac">
                                    {username}:{password}@
                                </div>

                                <div class="col-linkac" style="width:45%;">
                                    <input id="filelinkProtLINKAutofill" class="form-control selLinkAuto" type="text" name="value[LINKAutofill]">
                                    <span id="hb-LINKAutofill" style="display:none;" class="help-block"></span>
                                </div>
                            </div>
                        </div> <!-- link auto -->

                        <div id="LINKCredential" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-2">
                                    {!! Form::select('protocolLINKCredential', ['http://' => "http://", 'https://' => "https://"], null, ['id' => 'protocolLINKCredential','class' => 'form-control selLinkCred', 'placeholder' => '--Select Type--']) !!} 
                                </div>

                                <div class="col-linkac">
                                    {username}:{password}@
                                </div>

                                <div class="col-linkac" style="width:45%;">
                                    <input id="filelinkProtLINKCredential" class="form-control selLinkCred" type="text" name="value[LINKCredential]">
                                    <span id="hb-LINKCredential" style="display:none;" class="help-block"></span>
                                </div>
                            </div> 
                        </div> <!-- link Credential -->

                        <div id="LINKPassword" class="article-hide">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link: </label>
                                <div class="col-sm-2" style="padding-right: 0px;">
                                    {!! Form::select('protocolLINKPassword', ['http://' => "http://", 'https://' => "https://"], null, ['id' => 'protocolLINKPassword','class' => 'form-control selLinkPassword', 'placeholder' => '--Select Type--']) !!} 
                                </div>

                               <!--  <div class="col-linkac">
                                    {username}:
                                </div> -->

                                <div class="col-lg-2 col-sm-12" style="padding:0px">
                                    <div class="input-group" style="width: 100%">
                                        <input id="filelinkPasswordUsername" class="form-control selLinkPassword " type="text" name="filelinkPassword[Username]" placeholder="Username" autocomplete="off" value="">
                                    </div>

                                    
                                </div>
                                <div class="col-lg-2 col-sm-12" style="padding:0px">
                                    <div class="input-group">
                                        <span class="input-group-addon">:</span>
                                        <input id="filelinkPasswordPassword" class="form-control selLinkPassword " type="text" name="filelinkPassword[Password]" placeholder="Password"  autocomplete="off" value="">

                                    </div>
                                    <span id="hb-LINKPassword" style="display:none;" class="help-block"></span>
                                </div>


                                <div class="col-lg-3 col-sm-12" style="padding:0px">
                                    <div class="input-group col-lg-12 col-sm-12">
                                     <span class="input-group-addon">@</span>
                                     <input id="filelinkProtLINKPassword" class="form-control selLinkPassword" type="text" name="value[LINKPassword]">
                                     <span id="hb-LINKPassword" style="display:none;" class="help-block"></span>
                                 </div>
                             </div>                               
                         </div>
                     </div> <!-- link with password -->

                     <div id="GeoLocation" class="article-hide">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Geo Location: </label>
                            <div class="col-sm-10">
                                <input id="geolocation" type="text" class="form-control selGeoLoc" name="value[GeoLocation]" placeholder="Latitude, Longitude (i.e. 44.968046, -94.420307)"/>
                                <span id="hb-GeoLocation" style="display:none;" class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Assigned User: </label>
                            <div class="col-sm-10">
                                {!! Form::select('geoLocAssignedUserId', $users, null, ['class' => 'form-control selGeoLoc', 'placeholder' => '--Select User--']) !!}
                            </div>
                        </div>
                    </div> <!-- geo location -->

                    <div id="CalendarEvent" class="article-hide">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description: </label>
                            <div class="col-sm-10">
                                <input id="caldescription" type="text" class="form-control selCalEvent" name="caldescription"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Date Start: </label>
                            <div class="col-sm-10">
                                <input id="caldatestart" type="datetime-local" class="form-control selCalEvent" name="caldatestart"/>
                                <span id="hb-CalDateStart" style="display:none;" class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Date End: </label>
                            <div class="col-sm-10">
                                <input id="caldateend" type="datetime-local" class="form-control selCalEvent" name="caldateend"/>
                                <span id="hb-CalDateEnd" style="display:none;" class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Timezone Start: </label>
                            <div class="col-sm-10">
                                <input id="caltzstart" type="text" class="form-control selCalEvent" name="caltzstart" placeholder="UTC"/>
                                <span id="hb-CalTzStart" style="display:none;" class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Timezone End: </label>
                            <div class="col-sm-10">
                                <input id="caltzend" type="text" class="form-control selCalEvent" name="caltzend" placeholder="UTC"/>
                                <span id="hb-CalTzEnd" style="display:none;" class="help-block"></span>
                            </div>
                        </div>
                    </div> <!-- calendar event -->

                    <div id="LinkedArticle" class="article-hide">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Linked Article: </label>
                            <div class="col-sm-10">
                                {!! Form::select('linkedArticleId', $allArticles, null, ['id' => 'type-option', 'class' => 'form-control selLinkedArticle', 'placeholder' => '--Select Article--']) !!} 
                                <span id="hb-LinkedArticle" style="display:none;" class="help-block"></span>
                            </div>
                        </div>
                    </div> <!-- linked article -->

                </div> <!-- col sm 6 -->
            </div> <!-- FORM group first  -->

            <div class="form-group">
                <div class="col-sm-3">
                    <div class="icon-edit">
                        <input type="submit" class="btn btn-success" value="Save"> 
                        <a href="{{ route('article.show',Session::get('SiteId')) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </div>

        </div> <!-- end of form-horizontal -->
    </div> <!-- panel body -->
</div> <!-- panel-primary -->
{!! Form::close() !!} 



<script type="text/javascript">
         $(function(){                                               
            setTimeout(function(){
                $("#filelinkPasswordPassword").attr("type","password");
                
            },1000);
        });
        //Change display for File type options    
        $('#type-option').change(function()
        {
            //get value
            var panelToShow = $(this).val();

            if(panelToShow == 'DIRECTTEXTFULL')
            {
                panelToShow = 'DIRECTTEXT'; 
            }

            //remove current active
            $('.article-show').removeClass('article-show').addClass('article-hide');

            //set choosen type to display
            $('#'+panelToShow).removeClass('article-hide').addClass('article-show');

            //set required fields
            switch(panelToShow)             
            {
                case "Text":
                $(".selText").attr('required',true);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;
                
                case "File":
                $(".selFile").attr('required',true);
                $(".selText").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "DIRECTTEXT":
                $(".selFile").attr('required',false);
                $(".selText").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "DIRECTTEXTFULL":
                $(".selFile").attr('required',false);
                $(".selText").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;
                
                case "LINK":
                $(".selLinkNorm").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKExternal":
                $(".selLinkExt").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKLogin":
                $(".selLinkLog").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKInheritLogin":
                $(".selLINKInherit").attr('required',true);
                $(".selLinkLog").attr('required',false);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkAuto").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKAutofill":
                $(".selLinkAuto").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKCredential":
                $(".selLinkCred").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);    
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LINKPassword":
                $(".selLinkPassword").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);    
                $(".selLINKInherit").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "GeoLocation":
                $(".selGeoLoc").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);    
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selCalEvent").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "CalendarEvent":
                $(".selCalEvent").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);    
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selLinkedArticle").attr('required',false);
                break;

                case "LinkedArticle":
                $(".selLinkedArticle").attr('required',true);
                $(".selText").attr('required',false);
                $(".selFile").attr('required',false);
                $(".selLinkNorm").attr('required',false);
                $(".selLinkExt").attr('required',false);
                $(".selLinkLog").attr('required',false);
                $(".selLinkAuto").attr('required',false);    
                $(".selLINKInherit").attr('required',false);
                $(".selLinkPassword").attr('required',false);
                $(".selLinkCred").attr('required',false);
                $(".selGeoLoc").attr('required',false);
                $(".selCalEvent").attr('required',false);
                break;
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
                            $input.next('.inputPreview').attr('src', e.target.result).show();
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


        //------------------ FILE UPLOAD -------------//
        function fileURL() {

            var $input = $(this);                                               //input object
            var filePath = $(this)[0].value;                                    // file

            //check selected dropdown value and set valid extensions
            if($("#type-option").val() == "Text")
            {
                var validExtn = ["txt", "text"];                                // text files
            }
            else if($("#type-option").val() == "File")
            {
                var validExtn = ["pdf",                                         // pdf files
                                 "bmp", "jpeg", "jpg", "png", "tif", "tiff",    // image files
                                 "avi", "flv", "m2v", "m4v", "mkv", "mov",      // video files
                                 "mp2", "mp4", "mpg", "mpeg", "mpv", "wmv", 
                                 "doc", "docx", "xls", "xlsx", "ppt","pptx", "rtf"];   // office files
                             }  

            //get file extension
            var extn = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

            //get filename
            var filename = filePath.replace(/C:\\fakepath\\/i, '');


            //check if extn is in the array of valid extensions
            if($.inArray(extn, validExtn) >= 0)
            {
                if (typeof (FileReader) != "undefined") 
                {            
                    //set filename display
                    $(".filename_upload").text(filename);
                } 
                else 
                {
                    alert("This browser does not support FileReader.");
                }
            }
            // invalid extension
            else
            {
                $(this)[0].value = '';

                alert("Invalid file for 'Value' field. Valid file extensions are ("+validExtn+") only.");
            }
        }
        var test ;
        $(".browsefile").change(fileURL);   

        // ------- TINY MCE, WYSIWYG TEXT EDITOR ----------- //
        test = tinymce.init({ selector:'textarea#wysiwyg',                    
         theme: 'modern',
         plugins: [
         'advlist autolink lists link charmap print preview hr anchor pagebreak',
         'searchreplace wordcount visualblocks visualchars code fullscreen',
         'insertdatetime nonbreaking save contextmenu directionality',
         'template paste textcolor colorpicker textpattern image'
         ],
         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
         toolbar2: 'print preview | forecolor backcolor | fontselect |  fontsizeselect',
         image_advtab: true,
         templates: [
         { title: 'Test template 1', content: 'Test 1' },
         { title: 'Test template 2', content: 'Test 2' }
         ],
         content_css: [
         '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
         '//www.tinymce.com/css/codepen.min.css',                              
         ],   
         forced_root_block : 'div',
         fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
         file_browser_callback: function(field_name, url, type, win){

         },
         relative_urls : false,
         remove_script_host : false,
         convert_urls : true,


     });   

        //check if valid URL
        function validateURL() 
        {           
            var div = $("#type-option").val();            
            
            if(div == 'LINKAutofill' || div == 'LINKCredential' || div == 'LINKPassword')
            {
                var textval = $('#protocol'+div).val();

                if(div == 'LINKPassword') {
                    textval = textval + $('#filelinkPasswordUsername').val()+':'+$('#filelinkPasswordPassword').val() + '@';
                }

                textval = textval + $('#filelinkProt'+div).val();                
            }
            
            else if(div == 'LINK' || div == 'LINKExternal' || div == 'LINKLogin' || div == 'LINKInheritLogin')
            {
                var textval = $('#filelink'+div).val();
            }

            else if(div == 'Text' || div == 'FILE' || div == 'DIRECTTEXT' || div == 'GeoLocation' || div == 'CalendarEvent' || div == 'LinkedArticle')
            {
                return true;
            }
            //console.log(encodeURIComponent(textval.trim()));

            function isUrlValid(url) {
                return /^(http?|https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
            }
            
            if(isUrlValid(textval.replace(/ /g,"%20")) == false)
            {   
                var div = $("#type-option").val();

                $("#"+div).addClass("has-error");
                $("#hb-"+div).css("display","block");
                $("#hb-"+div).text("Invalid URL. Don't forget to include 'http://' or 'https://' on the URL.");                

                return false;
            }   
        }

        //link textbox on focus return to original style
        $( "#filelink" ).focus(function() {
            var div = $("#type-option").val();
            $("#"+div).removeClass("has-error");
            $("#hb-link").css("display","none");
        });            
        
    </script>

    @endsection


