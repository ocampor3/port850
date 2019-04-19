@extends('layouts.master')

@section('title')
    {{ Session::get('SiteCode') }}'s Pinned Articles | Appzmate 
@endsection

@section('page-title')
   <b>{{ Session::get('SiteCode') }}</b>'s Pinned Articles
@endsection

@section('content')

    <div class="show-return">
        <a href="{{ route('site.show', Session::get('SiteCode')) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
    </div>

    {!! Form::open([      
        'method' => 'POST',
        'action' => 'PinnedArticleController@store'
    ]) !!}

    <div class="form-horizontal">
        <div class="panel panel-primary">
            <div class="panel-body">
                
                <div class="form-group">
                    <div class="col-sm-10">
                        <a id="addProperty" class="btn btn-success" onClick="addProperty();"><span class="glyphicon glyphicon-plus"></span> Add Pinned Article</a>
                    </div>
                </div>

                <div class="form-group" style="display:none;">   
                    <div class="col-sm-10"> 
                        <div id="propDD">                            
                            <div class="col-sm-5">                              
                                {!! Form::select('pinned_article[]', $all_articles, null, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Article--']) !!}
                            </div>                        
                            
                            <div>
                                {!! Form::button('<span class="glyphicon glyphicon-trash"></span>', array('id' => 'btnRemove' ,'type' => 'button', 'class' => 'btn btn-danger','onClick' => 'delDiv(this.id);')) !!}    
                            </div>
                            <br>                            
                        </div>                                               
                    </div>
                </div>

                <div class="form-group">
                    <div id="dynamicProperty" class="col-sm-10">  
                        
                        @foreach($pinned_articles as $key => $pa)
                            <div id="divpropDD{{$key}}" style="display:block;">
                                <div class="col-sm-5">
                                {!! Form::select('pinned_article[]', $all_articles ,$pa->Id, ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Article--']) !!}
                                </div>
                            
                                <div>
                                    {!! Form::button('<span class="glyphicon glyphicon-trash"></span>', array('id' => 'btnRemovepropDD'."$key",'type' => 'button', 'class' => 'btn btn-danger','onClick' => 'delDiv(this.id);')) !!}    
                                </div>
                                <br>
                            </div>
                        @endforeach                    
                    </div>                    
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10">
                <input type="submit" class="btn btn-warning" value="Update"> 
                <a href="{{ route('pinnedarticle.show',Session::get('SiteId')) }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </div> <!-- form horizontal-->

    <script type="text/javascript">

        // ------- ADDING DYNAMIC PANEL  ----------- //                         
        var $ddTemp = $("#propDD");              
        var propHash = {{count($pinned_articles)}};
        function addProperty()
        {                                    
            propHash++;
            var $newDD = $ddTemp.clone();       

            $newDD.attr("id","divpropDD"+propHash);
            $newDD.find(".btn.btn-danger")
                    .attr("id","btnRemovepropDD"+propHash);

            $newDD.find("#detail_name")
                    .attr("required","true");

            $newDD.find("#detail_value")
                    .attr("required","true");

            $("#dynamicProperty").append($newDD.fadeIn());
        }  

        function delDiv(id){    
            propHash--;

            var divID = id.replace("btnRemove","div"); 
            var element = document.getElementById(divID);

            element.parentNode.removeChild(element);
        }
    </script>

@endsection