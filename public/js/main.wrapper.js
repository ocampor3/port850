var main = {}

$(function(){


	//ajax block
	//$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	
	main.utils = {};




	main.alert = function(text){alert(text);};

	
	main.ajax = function(param,func,block=true){
		if(block)
		$.blockUI();
		param.beforeSend = function( xhr ) {
			
		};//

		$.ajax(param)

		.done(function(response,textStatus,jqXHR ) {
			func(response);
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {})
		.always(function( data, textStatus, errorThrown ) { 
			if(block)
			$.unblockUI();	
		});
		

	}

});