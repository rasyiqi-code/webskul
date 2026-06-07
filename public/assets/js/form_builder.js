/**
 * @codename    Javascript Form Builder
 * @author      Anton Sofyan | https://facebook.com/antonsofyan
 * @copyright   (c) 2015-2019
 */

if (typeof jQuery === 'undefined') {
	throw new Error('FormBuilder\'s JavaScript requires jQuery')
}

"use strict";

/*
 * FORM BUILDER CLASS DEFINITION
 */
function FormBuilder(name, options) {
	var _this = this;
	window[ name ] = _this;
	if (!options.controller) throw new Error('FormBuilder requires "controller" object key on the 2nd parameter');
	if (!options.fields) throw new Error('FormBuilder requires "fields" object on the 2nd parameter');
	_this.options = $.extend( {
		id: 0,
		name: name,
		controller: null,
		extra_params: {},
		save_action: 'save',
		form_action: 'find_id',
		upload_action: 'upload',
		fields: []
	}, options );
}

(function() {

	/**
	 * On Edit
	 */
	this.OnEdit = function(id) {
		var _this = this, O = _this.options;
		O.id = id || 0;
		_this.RenderForm();
		$('.modal-form').modal({
			show:true,
			backdrop:'static'
		});
		_H.Loading( true );
		$.post(_BASE_URL + O.controller + '/' + O.form_action, {id: id}, function(response) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			for( var z in O.fields) {
				var field = O.fields[ z ];
				var el = $('#' + field.name);
				el.val('');
				if (field.type !== 'password') {
					// Set Value
					switch ( field.type ) {
						case 'number':
						case 'float':
							el.val(res[field.name] || 0);
							break;
						case 'select':
							el.val(res[field.name]).trigger( 'change' );
							break;
						default:
							el.val(res[field.name]);
							break;
					}
				}
			}
		}).fail( function( xhr, textStatus, errorThrown ) {
			_H.Loading( false );
			xhr.textStatus = textStatus;
			xhr.errorThrown = errorThrown;
			if ( !errorThrown ) errorThrown = 'Unable to load resource, network connection or server is down?';
			_this.Notify('error', textStatus + ' ' + errorThrown + '<br/>' + xhr.responseText );
		});
		$('.modal-dialog').addClass('modal-lg');
		$('.reset').hide();
		$('.submit').show().html('<i class="fa fa-save"></i> UPDATE');
		$('.submit').attr('onclick', O.name + '.Submit(event)');
		$('.form-horizontal').removeAttr('id enctype');
	};

	/**
	 * Submit function
	 */
	this.Submit = function(event) {
		event.preventDefault();
		var _this = this, O = _this.options;
		var serialize = $('.form-fields').find(':input').serializeArray();
		var dataset = {};

		// assign post data
		dataset['id'] = O.id;
		for (var z in serialize) {
			dataset[serialize[ z ].name] = serialize[ z ].value;
		}

		if (Object.keys(O.extra_params).length > 0) {
			for (var z in O.extra_params) {
				dataset[ z ] = O.extra_params[ z ];
			}
		}

		// find input type checkbox
		var checkbox = $('.form-fields').find('input[type="checkbox"]');
		if (checkbox.length) {
			checkbox.each(function(){
				dataset[ this.name ] = this.checked.toString(); // string value is "true" or "false"
			});
		}

		// show loading
		_H.Loading( true );
		// Post Data
		$.post(_BASE_URL + O.controller + '/' + O.save_action, dataset, function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			_this.Notify(res.status, _this.Message(res.message));
			if (res.status == 'success') {
				var _grid = O.name.split("_FORM");
				window[ _grid[ 0 ] ].Reload();
				$('.modal-form').modal('hide');
			}
		}).fail( function( xhr, textStatus, errorThrown ) {
			_H.Loading( false );
			xhr.textStatus = textStatus;
			xhr.errorThrown = errorThrown;
			if( !errorThrown ) errorThrown = 'Unable to load resource, network connection or server is down?';
			_this.Notify('error', textStatus + ' ' + errorThrown + '<br/>' + xhr.responseText );
		});
	};

	/**
	 * On Show / Add New
	 */
	this.OnShow = function() {
		var _this = this, O = _this.options;
		O.id = 0; // Reset id to 0
		// Generate Form
		_this.RenderForm();
		// Show Modal
		$('.modal-form').modal({
			show:true,
			backdrop:'static'
		});
		// Set Devault value
		for ( var z in O.fields ) {
			var field = O.fields[ z ];
			if (field.type === 'number' || field.type === 'float') {
				$('#' + field.name).val(0);
			}
		}
		$('.modal-dialog').addClass('modal-lg');
		$('.reset').show();
		$('.submit').show().html('<i class="fa fa-save"></i> SAVE');
		$('.submit').attr('onclick', O.name + '.Submit(event)');
		$('.form-horizontal').removeAttr('id enctype');
	};

	/**
	 * On Upload
	 */
	this.OnUpload = function( id ) {
		var _this = this, O = _this.options;
		O.id = id || 0;
		if (id > 0) {
			_this.RenderFormUpload();
			$('.modal-dialog').removeClass('modal-lg');
			$('.reset').hide();
			$('.submit').show().html('<i class="fa fa-upload"></i> UPLOAD');
			$('.submit').attr('onclick', O.name + '.Upload(event)');
			$('.modal-form').modal({
				show:true,
				backdrop:'static'
			});
		}
	};

	/**
	 * handler Upload File
	 */
	this.Upload = function( e ) {
		e.preventDefault();
		var _this = this, O = _this.options;
		var dataset = new FormData();
		dataset.append('id', O.id);
		dataset.append('file', $('input[type="file"]')[ 0 ].files[ 0 ]);
		_H.Loading( true );
		$.ajax({
			url: _BASE_URL + O.controller + '/' + O.upload_action,
			type: 'POST',
			data: dataset,
			contentType: false,
			processData: false,
			success : function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				res.action == 'error' ? _this.Notify(res.status, res.message) : _this.Notify(res.status, _this.Message(res.message));
				var _grid = O.name.split("_FORM");
				window[ _grid[ 0 ] ].Reload();
				$('.modal-form').modal('hide');
			},
			error: function() {
				_H.Loading( false );
			}
		});
	}

	/**
	 * Render Form Function
	 */
	this.RenderForm = function() {
		var _this = this, O = _this.options;
		var field = '';
		for (var z in O.fields) {
			field += _this.RenderField(O.fields[ z ]);
		}

		// Remove and rerendered form
		$('.form-fields').empty().html(field);

		// Date Picker
		$( document ).find( 'input.date:enabled' ).datetimepicker({
			format: 'yyyy-mm-dd',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			fontAwesome: true
		});

		// Date Time Picker
		$( document ).find( 'input.datetime:enabled' ).datetimepicker({
			format: 'yyyy-mm-dd hh:ii:ss',
			todayBtn: true,
			minDate: '0001-01-01',
			setDate: new Date(),
			todayHighlight: true,
			autoclose: true,
			minuteStep: 5,
			startView: 2,
			fontAwesome: true
		});

		// Time Picker
		$( document ).find( 'input.time:enabled' ).datetimepicker({
			format: 'hh:ii:ss',
			weekStart: 1,
			autoclose: 1,
			startView: 1,
			minView: 0,
			maxView: 1,
			forceParse: 0,
			fontAwesome: true
		});

		// Select2
		$( document ).find('.select2:enabled').select2();

		// Input type number
		$( document ).find('.number').number( true, 0 );

		// Input type float
		$( document ).find( '.float' ).number( true, 2 );
	};

	/**
	 * Render Form Upload Function
	 */
	this.RenderFormUpload = function() {
		var _this = this;
		var field = _this.RenderField({'name':'file','type':'file', 'label':''});
		$('.form-fields').empty().html(field);
	};

	/**
	 * Render Field Function
	 */
	this.RenderField = function( obj ) {
		var _this = this;
		var field = '<div class="form-group">';
		field +='<label class="col-sm-4 control-label" for="' + obj.name + '">' + obj.label + '</label>';
		field += '<div class="col-sm-8">';
		switch(obj.type) {
			case 'number':
				field +='<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm number" style="text-align:right;" id="' + obj.name + '" name="' + obj.name + '">';
				break;
			case 'float':
				field +='<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm float" style="text-align:right;" id="' + obj.name + '" name="' + obj.name + '">';
				break;
			case 'email':
				field += '<div class="input-group">';
				field += '<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm" id="' + obj.name + '" name="' + obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '">';
				field += '<div class="input-group-addon input-sm"><i class="fa fa-envelope-o"></i></div>';
				field += '</div>';
				break;
			case 'textarea':
				field += '<textarea rows="5" class="form-control input-sm" id="' + obj.name + '" name="' + obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '"></textarea>';
				break;
			case 'select':
				field += '<select style="width:100%" class="form-control select2" '+ (obj.required ? 'required':'') +' id="' + obj.name + '" name="' + obj.name + '">';
				if (Object.keys(obj.datasource).length) {
					for (var z in obj.datasource) {
						field += '<option value="' + z + '">' + obj.datasource[ z ] + '</option>';
					}
				}
				field += '</select>';
				break;
			case 'password':
				field += '<div class="input-group">';
				field += '<input autocomplete="off" type="password" '+ (obj.required ? 'required':'') +' class="form-control input-sm" id="' + obj.name + '" name="' + obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '">';
				field += '<div class="input-group-addon input-sm"><i class="fa fa-key"></i></div>';
				field += '</div>';
				break;
			case 'file':
				field += '<input id="' + obj.name + '" type="file" name="' + obj.name + '" style="margin-top:8px;">';
				break;
			case 'image':
				field +='<input onchange="' + _this.Preview(this) + '" type="file" '+ (obj.required ? 'required':'') +' id="' + obj.name + '" name="' + obj.name + '" style="margin-top:8px;">';
				field +='<img id="preview" style="margin:10px 0; max-width:450px;">';
				break;
			case 'checkbox':
				field +='<input type="checkbox" '+ (obj.required ? 'required':'') +' id="' + obj.name + '" name="' + obj.name + '" style="margin-top:8px;width:20px;height:20px;">';
				break;
			case 'date':
				field += '<div class="input-group date">';
				field += '<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm date" id="' + obj.name + '" name="' + obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '">';
				field += '<div class="input-group-addon input-sm"><i class="fa fa-calendar"></i></div>';
				field += '</div>';
				break;
			case 'time': // time
				field += '<div class="input-group">';
				field += '<input type="text" class="form-control time input-sm" id="' + obj.name + '" name="'+obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '">';
				field += '<div class="input-group-addon input-sm"><i class="fa fa-clock-o"></i></div>';
				field += '</div>';
				break;
			case 'datetime':
				field += '<div class="input-group">';
				field += '<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm datetime" id="' + obj.name + '" name="' + obj.name + '" placeholder="' + (obj.placeholder ? obj.placeholder : '') + '">';
				field += '<div class="input-group-addon input-sm"><i class="fa fa-calendar"></i></div>';
				field += '</div>';
				break;
			default :
				field +='<input type="text" '+ (obj.required ? 'required':'') +' class="form-control input-sm" id="' + obj.name + '" name="' + obj.name + '" placeholder="'+ (obj.placeholder ? obj.placeholder : '') +'">';
		}
		field +='</div></div>';
		return field;
	};

	/**
	 * Preview Image
	 * @param Object
	 * @return void
	 */
	this.Preview = function( input ) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#preview').attr('src', e.target.result);
			};
			reader.readAsDataURL(input.files[0]);
		}
	}

	/**
	 * Popup Alert
	 * @param String
	 * @param String
	 * @return void
	 */
	this.Notify = function( status, message ) {
		switch (status) {
			case 'success' :
				toastr.success(message, 'Sukses' );
				break;
			case 'info' :
				toastr.info(message, 'Informasi' );
				break;
			case 'warning' :
				toastr.warning(message, 'Peringatan' );
				break;
			case 'error' :
				toastr.error(message, 'Terjadi kesalahan' );
				break;
			default :
				toastr.error('Tipe kesalahan tidak diketahui.');
				break;
		}
	};

	/**
	 * Alert Message
	 * @param String
	 * @return String
	 */
	this.Message = function( v ) {
		var message;
		switch ( v ) {
			case 'created':
				message = 'Data berhasil disimpan.';
				break;
			case 'not_created':
				message = 'Data gagal tersimpan.';
				break;
			case 'updated':
				message = 'Data berhasil diperbaharui.';
				break;
			case 'not_updated':
				message = 'Data gagal diperbaharui.';
				break;
			case 'uploaded':
				message = 'File berhasil diunggah.';
				break;
			case 'not_uploaded':
				message = 'File gagal diunggal.';
				break;
			case 'email_send':
				message = 'Email berhasil dikirim';
				break;
			case 'email_not_send':
				message = 'Email tidak berhasil dikirim';
				break;
			default:
				message = v;
				break;
		}
		return message;
	};

}).call(FormBuilder.prototype);
