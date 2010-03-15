/*
$(document).ready(function(){
	$('.json').change(function() {
		if($(this).val().length != 0) {
			$.getJSON(Html.url($(this).attr("id")),
				{carId: $(this).val()},
				function(carModels) {
					if(carModels !== null) {
						populateCarModelList(carModels);
					}
			});
		}
	});
});

/**
 * Js version of HtmlHelper.
 **
var Html = {
	/**
	 * generate a url from a css class or array.
	 *
	 *
	url: function(data){
		var parts = data.split(' ');
		var addressUrl = [];

		$.each(parts, function(key, value){
			addressUrl.action     = Html._urlGetPart('action', value);
			addressUrl.controller = Html._urlGetPart('controller', value);
			addressUrl.plugin     = Html._urlGetPart('plugin', value);
			//addressUrl.admin      = this._urlGetPart('plugin', value);
		});
		console.log(addressUrl);
	},

	/**
	 * get parts from a class used to build an url array.
	 *
	 * @param string type part of the array action/controller/plugin
	 * @param string data the part of the class to check for the params.
	 *
	 * @access public
	 * @return void
	 **
	_urlGetPart: function(type, data){
		sendBack = '';
		if (data.indexOf(type) !== -1) {
			action = data.split(':');
			sendBack = action[1];
		}

		else{
			sendBack = Infinitas.params.type;
		}

		if (type == 'action') {
			sendBack = sendBack.split('admin_');
		}

		return sendBack;
	}
}*/

$('html').html();