@extends('layouts.master')

@section('title')
Create Account | Appzmate 
@endsection

@section('page-title')
Create Account
@endsection

@section('content')

{!! Form::open([      
    'method' => 'POST',
    'action' => 'UserController@store',
    'files' => 'true',
    'onsubmit' => 'return validateFields();'
    ]) !!}


    <div class="panel panel-primary">       
        <div class="panel-body">
            <div class="form-horizontal">

                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">   
                    <label class="col-sm-2 control-label">Full Name: </label>
                    <div class="col-sm-3">
                        <input class="form-control" name="name" type="text" maxlength="160" value="{{ old('name') }}" required />

                        @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif

                    </div>  
                </div>          

                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                    <label class="col-sm-2 control-label">Username: </label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" name="username" maxlength="160" value="{{ old('username') }}" required />

                        @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-sm-2 control-label">Password: </label>
                    <div class="col-sm-3">
                        <input class="form-control" name="password" type="password" minlength="6" required />

                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('confirmPassword') ? ' has-error' : '' }}">
                    <label class="col-sm-2 control-label">Re-type Password: </label>
                    <div class="col-sm-3">
                        <input class="form-control" name="confirmPassword" type="password" minlength="6" required />

                        @if ($errors->has('confirmPassword'))
                        <span class="help-block">
                            <strong>{{ $errors->first('confirmPassword') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('pincode') ? ' has-error' : '' }}">
                    <label class="col-sm-2 control-label">Default Pincode: </label>
                    <div class="col-sm-3">
                        <input class="form-control" name="pincode" type="text" />

                        @if ($errors->has('pincode'))
                        <span class="help-block">
                            <strong>{{ $errors->first('pincode') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
                <div class="form-group">
                    <label class="col-sm-2 control-label">User Group: </label>
                    <div class="col-sm-3">
                        @if(Auth::user()->UserGroup == 'Admin')

                        {!! Form::select('usergroup', ['Admin ' => 'Admin','Owner ' => 'Owner',
                        'Member' => 'Member', 'Visitor' => 'Visitor'], null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Type--' ,'required']) !!} 

                        @else

                        {!! Form::select('usergroup', ['Owner ' => 'Owner',
                        'Member' => 'Member', 'Visitor' => 'Visitor'], null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Type--' ,'required']) !!} 

                        @endif
                    </div>
                </div>

                    <!-- @if(Auth::user()->UserGroup == 'Admin')
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Site to Handle: </label>
                            <div class="col-sm-3">
                                {!! Form::select('sitecode', $site, null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Type--' ,'required']) !!} 
                            </div>
                        </div>
                        @endif -->
                        @endif

                        @if(Auth::user()->UserGroup == 'Admin' || Auth::user()->UserGroup == 'Owner')
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label">Security Group: </label>
                            <div class="col-sm-3">
                                {!! Form::select('securitygroup', $securitygroups, null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Type--' ,'required']) !!} 
                            </div>
                        </div> -->
                        @endif

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Default Web Domain: </label>
                            <div class="col-sm-3">
                                <input class="form-control" id="domain" name="domain" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Default Web Username: </label>
                            <div class="col-sm-3">
                                <input class="form-control" id="domainuserid" name="domainuserid" type="text" />
                                <span class="help-block" id="help-domain-userid-block" style='display:none;'>
                                    <strong><label id="domain-userid-error"></label></strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Default Web Password: </label>
                            <div class="col-sm-3">
                                <input class="form-control" id="domainpassword" name="domainpassword" type="password" />
                                <span class="help-block" id="help-domain-password-block" style='display:none;'>
                                    <strong><label id="domain-password-error"></label></strong>
                                </span>
                            </div>
                        </div>

                        @if(Auth::user()->UserGroup == 'Admin' || Auth::user()->UserGroup == 'Owner')
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Sites Handled</label>
                            <div class="col-sm-10">
                                <a id="addProperty" class="btn btn-success" onClick="addProperty();"><span class="glyphicon glyphicon-plus"></span> Add Sites Handled</a>
                                <span class="help-block" id="help-sites-handled-block" style='display:none;'>
                                    <strong><label id="sites-handled-error"></label></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" style="display:none;">   
                            <div class="col-sm-10"> 
                                <div id="propDD">

                                    @if(Auth::user()->UserGroup == 'Admin')
                                    <div class="col-sm-3">                              
                                        {!! Form::select('sitecodeTemp', $site, null, ['id' => '', 'class' => 'form-control sitedropdown', 'placeholder' => '--Select Site--']) !!}
                                    </div>

                                    <div class="col-sm-3">                              
                                        {!! Form::select('sitecodesgTemp', [], null, ['id' => '', 'class' => 'form-control securityGroupDropDown', 'placeholder' => '--Select Security Group--']) !!}
                                    </div>
                                    @else

                                    <div class="col-sm-3">                              
                                        {!! Form::select('sitecodeTemp', Auth::user()->handledSites->pluck('Title', 'Id'), null, ['id' => '', 'class' => 'form-control sitedropdown', 'placeholder' => '--Select Site--']) !!}
                                    </div>

                                    <div class="col-sm-3">                              

                                       {!! Form::select('sitecodesgTemp', [], null, ['id' => '', 'class' => 'form-control securityGroupDropDown', 'placeholder' => '--Select Security Group--']) !!}


                                   </div>

                                   @endif                        

                                   <div>
                                    {!! Form::button('<span class="glyphicon glyphicon-trash"></span>', array('id' => 'btnRemove' ,'type' => 'button', 'class' => 'btn btn-danger','onClick' => 'delDiv(this.id);')) !!}    
                                </div>
                                <br>
                            </div>  
                        </div>
                    </div>

                    <div class="form-group">                     
                        <label for="title" class="col-sm-2 control-label"></label> 
                        <div id="dynamicProperty" class="col-sm-10">    


                        </div>                    
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Save"> 
                            <a href="{{ route('user.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>

                </div> <!-- end of form-horizontal -->
            </div> <!-- panel body -->
        </div> <!-- panel-primary -->

        {!! Form::close() !!}

        <script type="text/javascript">

         // ------- ADDING DYNAMIC PANEL  ----------- //                         

         var $ddTemp = $("#propDD");              
         var propHash = 1;
         var ug =  {!! "'".Auth::user()->UserGroup."'" !!}

         function addProperty()
         {                                    
            var $newDD = $ddTemp.clone();       

            $newDD.attr("id","divpropDD"+propHash);
            $newDD.find(".btn.btn-danger")
            .attr("id","btnRemovepropDD"+propHash);

            $newDD.find("#detail_name")
            .attr("required","true");

            $newDD.find("#detail_value")
            .attr("required","true");

            $newDD.find("select[name='sitecodeTemp']").attr("name","sitecode[]");
            $newDD.find("select[name='sitecodesgTemp']").attr("name","sitecodesg[]");

            $("#dynamicProperty").append($newDD.fadeIn());

            propHash = propHash + 1;
            SubscribeFunctions();
        }  

        function delDiv(id){  
            var propHash = propHash - 1;

            var divID = id.replace("btnRemove","div"); 
            var element = document.getElementById(divID);

            element.parentNode.removeChild(element);
        }
        
        // -------- AUTO ADJUST TEXTAREA FOR VALUE -------- //
        function textAreaAdjust(o) {

            if((o.value.length) > 50)
            {
                o.style.height = "1px";
                o.style.height = (25+o.scrollHeight)+"px";
            }
        }

        function validateFields() {
            //console.log(validateSites(),validateDomainFields());

            return validateSites() && validateDomainFields();
        }

        function validateSites() {

            var size = document.getElementsByName('sitecode[]').length;
            for(i=0; i<size; i++) {
                if(document.getElementsByName('sitecode[]')[i].value != '') return true;
            }
            $('#sites-handled-error').text("Please provide at least one site handled.");
            $('#help-sites-handled-block').css('display','block');
            
            return ug =="Owner"
            
        }

        function validateDomainFields() {
            var domain = $('#domain').val();
            var domainuserid = $('#domainuserid').val();
            var domainpassword = $('#domainpassword').val();

            // reset
            $('#domain-userid-error').text('');
            $('#domain-password-error').text('');
            $('#help-domain-userid-block').css('display','none');
            $('#help-domain-password-block').css('display','none');

            if(domain != '' && domainuserid == '') {
                $('#domain-userid-error').text("Username should not be empty if there is a default domain.");
                $('#help-domain-userid-block').css('display','block');
                return false;
            }

            if(domainuserid != '' && domainpassword == '') {
                $('#domain-password-error').text("Please provide default domain password.");
                $('#help-domain-password-block').css('display','block');
                return false;
            }

            return true;
        }

        function SubscribeFunctions(){

            $( ".sitedropdown" ).on( "change", function() {
              var id = $( this ).parent().parent().attr("id");
              var value = $("#"+id+" select.sitedropdown").find(":selected").val();
              updateSGDropdown(id,value);
          });

        }
        function updateSGDropdown(id,val){

            var param = {
                url:'/User/getSecurityGrouBySiteId',
                data:{SiteId:val},
                dataType:"JSON",
                type:'GET'

            }


            main.ajax(param,function(response){

                $("#"+id+" select.securityGroupDropDown option").remove()
                var $el = $("#"+id+" select.securityGroupDropDown");
                var arr = response.data;
                
                $el.append($("<option></option>").attr("value", "").text('--Select Security Group--'));
                for(var item in arr){
                    $el.append($("<option></option>").attr("value", arr[item].Id).text(arr[item].DisplayName));
                }



            });



            disableDropDown();

        }

        function disableDropDown(){

             //disable options
             var values = $("select[name='sitecode[]']")
             .map(function(){return $(this).find(":selected").val();}).get();

             $("select[name='sitecode[]'] option").each(function(i,val){ 
                var parent = $(this).parent().find(":selected").val();
                if(values.indexOf(String($(this).attr('value')))>=0 && parent!=String($(this).attr('value'))){
                    //console.log($(this).attr("value")) 
                    $(this).prop("disabled",true);
                    $(this).css("background-color", "#B0ABAA");

                }else{
                    $(this).prop("disabled",false);
                    $(this).css("background-color", "transparent");

                }


            });


         };

         $(function(){

            SubscribeFunctions();

        })

    </script>

    @endsection 
