/**
 * @codename    Javascript Grid Builder
 * @author      Anton Sofyan | https://facebook.com/antonsofyan
 * @copyright   (c) 2015-2019
 */

//Make sure jQuery has been loaded before grid_builder.js
if (typeof jQuery === 'undefined') {
	throw new Error('GridBuilder\'s JavaScript requires jQuery')
}

$(document).keydown(function(event) {
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if (keycode === 27) {
		setTimeout(function() {
			$('.keyword').focus().val('');
			$('.search-info').empty();
			window[ _grid ].options.keyword = '';
			window[ _grid ].options.page_number = 0; // Reset to 0
			window[ _grid ].Reload();
		}, 200);
	}
});

"use strict";

/**
 * GRID BUILDER CLASS DEFINITION
 */
function GridBuilder(name, options) {
	var _this = this;
	window[ name ] = _this;
	if (!options.controller) throw new Error('GridBuilder requires "controller" object key on the 2nd parameter');
	if (!options.fields) throw new Error('GridBuilder requires "fields" object on the 2nd parameter');
	_this.options = $.extend( {
		name: name,
		controller: null,
		extra_params: {},
		pagination_action: 'pagination',
		delete_action: 'delete',
		restore_action: 'restore',
		per_page: 10,
		per_page_options: [10, 20, 50, 100, 0],
		page_number: 0,
		total_page: 0,
		total_rows: 0,
		keyword: '',
		fields: [],
		rows: [],
		can_reload: true,
		can_add: true,
		can_delete: true,
		can_restore: true,
		can_search: true,
		to_excel: true,
		sort_field: '',
		sort_type: 'ASC',
		resize_column: 3,
		extra_buttons: ''
	}, options );
	$( document ).ready( function() {
		_this._init();
	} );
};

(function() {

	/**
	 * Initialize
	 */
	this._init = function() {
		var _this = this, O = _this.options;
		// render buttons
		_this.BuildButtons();

		// Render Header Table
		_this.HeaderTable();

		// Render Footer Table
		_this.FooterTable();

		// Get Data From Server
		_this.GetData();

		// Resize column width
		_this.ResizeColumn();

		// check all rows
		$('.check-all').click(function() {
			$('input:checkbox').not(this).prop('checked', this.checked);
		});

		// add atr on form search
		$('.keyword').attr('onkeypress', O.name + '.Search(event)');

		// Form search autofocus
		$('.keyword').focus();
	};

	/**
	 * Reload Grid
	 */
	this.Reload = function() {
		var _this = this;
		_this.GetData();
	};

	/**
	 * Render Grid Action button
	 */
	this.BuildButtons = function() {
		var _this = this, O = _this.options, btn = '';
		if (O.extra_buttons) btn += O.extra_buttons;
		if (O.can_add)
			btn += '<button title="Add New" onclick="' + O.name + '_FORM.OnShow()" class="btn btn-default btn-flat rounded-0 add" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i></button>';
		if (O.can_delete)
			btn += '<button title="Delete" onclick="' + O.name + '.Delete()" class="btn btn-default btn-flat rounded-0 delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>';
		if (O.can_restore)
			btn += '<button title="Restore" onclick="' + O.name + '.Restore()" class="btn btn-default btn-flat rounded-0 restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fa fa-mail-reply-all"></i></button>';
		if (O.to_excel)
			btn += '<button title="Save as Excel" onclick="' + O.name + '.ExportExcel()" class="btn btn-default btn-flat rounded-0" data-toggle="tooltip" data-placement="top" title="Save as Excel"><i class="fa fa-file-excel-o"></i></button>';
		if (!O.can_search) $('input.keyword').hide();
		if (O.can_reload)
			btn += '<button title="Reload" onclick="' + O.name + '.OnReload()" class="btn btn-default btn-flat rounded-0 reload" data-toggle="tooltip" data-placement="top" title="Reload"><i class="fa fa-refresh"></i></button>';
		$('.grid-button').html(btn);
	};

	/**
	 * Render Header
	 */
	this.HeaderTable = function() {
		var _this = this, O = _this.options;
		var fields = O.fields;
		var thead = _this.TH('NO');
		if (fields.length) {
			for (var z in fields) {
				var exclude_excel = fields[ z ].exclude_excel === true ? true : false;
				var sorting = fields[ z ].sorting === false ? false : true;
				var header = fields[ z ].header;
				thead += _this.TH( header, (fields[ z ].sort_field || fields[ z ].renderer), exclude_excel, sorting );
			}
		}
		$('.thead').html(_this.TR(thead));
	};

	/**
	 * Ajax Get Data
	 */
	this.GetData = function() {
		var _this = this, O = _this.options;
		try {
			var values = {
				page_number: O.page_number,
				per_page: O.per_page,
				keyword: O.keyword
			};
			if (Object.keys(O.extra_params).length > 0) {
				for (var z in O.extra_params) values[ z ] = O.extra_params[ z ];
			}
			_H.Loading( true );
			$.post( _BASE_URL + O.controller + '/' + O.pagination_action, values, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				O.total_page = res.total_page;
				O.total_rows = res.total_rows;
				O.rows = res.rows;
				_this.RenderTable(O.rows);
			}).fail( function( xhr, textStatus, errorThrown ) {
				_H.Loading( false );
				xhr.textStatus = textStatus;
				xhr.errorThrown = errorThrown;
				if( !errorThrown ) errorThrown = 'Unable to load resource, network connection or server is down?';
				_this.Notify('error', textStatus + ' ' + errorThrown + '<br/>' + xhr.responseText );
			} );
		} catch( e ) {
			_this.Notify('error', e );
		}
	}

	/**
	 * Render Table
	 * @param Array
	*/
	this.RenderTable = function(rows) {
		var _this = this, O = _this.options;
		if (O.total_rows > 0) {
			if (O.total_rows <= O.per_page) $(".next").prop('disabled', true);
			var tbody = '';
			$.each(rows, function(key, value) {
				var no = (O.page_number * O.per_page) + (key + 1);
				var str = '';
				str += _this.TD(no+'.');
				for (var z in O.fields) {
					var exclude_excel = false;
					if (O.fields[ z ].exclude_excel) {
						exclude_excel = true;
					}
					var cell = _this.TransformCell(value, O.fields[ z ]);
					str += _this.TD(cell, exclude_excel);
				}
				tbody += _this.TRid(value.id, value.is_deleted, str);
			});
			$('.tbody').html(tbody);
		} else {
			$('.tbody').empty();
			_this.Notify('info', _this.Message('empty'));
		}
		_this.PaginationInfo();
		_this.PaginationButton(O.total_page);
		if ($('.keyword').val() !== '') _this.SearchInfo();
	};

	/**
	 * Render Footer
	 */
	this.FooterTable = function() {
		var _this = this, O = _this.options;
		var select_options = '';
		for ( var z in O.per_page_options ) {
			select_options += '<option value="' + O.per_page_options[ z ] + '">' + (O.per_page_options[ z ] == 0 ? 'All' : O.per_page_options[ z ]) + '</option>';
		}
		$('.per-page').append( select_options );
	};

	/**
	 * Set Pagination Button
	 */
	this.PaginationButton = function() {
		var _this = this, O = _this.options;
		$('.next').attr('onclick', O.name + '.NextPage()');
		$('.previous').attr('onclick', O.name + '.PrevPage()');
		$('.first').attr('onclick', O.name + '.FirstPage()');
		$('.last').attr('onclick', O.name + '.LastPage()');
		$('.per-page').attr('onchange', O.name + '.SetPerPage()');
		$(".previous, .first").prop('disabled', O.page_number == 0);
		$(".next, .last").prop('disabled', O.total_page == 0 || (O.page_number == (O.total_page - 1)));
	};

	/**
	 * render Pagination Info
	 */
	this.PaginationInfo = function() {
		var _this = this, O = _this.options;
		var page_info = 'Page ' + ((O.total_rows == 0) ? 0 : (O.page_number + 1));
		page_info += ' of ' + O.total_page.to_money();
		page_info += ' &sdot; Total : ' + O.total_rows.to_money() + ' Rows.';
		$('.page-info').html(page_info);
	};

	/**
	 * render Search Info
	 */
	this.SearchInfo = function() {
		var _this = this, O = _this.options;
		var suffik = O.total_rows > 1 ? 's' : '';
		var search_info = ' Your search for <strong>"' + O.keyword + '"</strong>';
		search_info += ' returned ' + O.total_rows.to_money() + ' result' + suffik;
		search_info += '. <b style="color:red;">Press escape to clear</b>';
		$('.search-info').html(search_info);
	};

	/**
	 * Resize column width
	 */
	this.ResizeColumn = function() {
		var _this = this, O = _this.options;
		for (var i = 1; i <= O.resize_column; i++) {
			$('tr th:nth-child(' + i + ')').attr({
				'width':'30px'
			});
		}
	}

	/**
	 * Transform Cell Function
	 * @param String
	 * @param String
	 * @return String
	 */
	this.TransformCell = function(value, field) {
		var renderer = field.renderer, str = '';
		switch( typeof renderer ) {
			case 'string': // access directly
			str = value[ renderer ];
			break;
			case 'function':
			str = renderer( value ) || ' ';
			break;
			default: // not a string or function, print the renderer
			var err = 'invalid renderer, renderer must be a string or function';
			console.error( err, renderer );
		}
		return str;
	}

	/**
	 * Delete Function
	 */
	this.Delete = function() {
		var _this = this, O = _this.options;
		var checked = 0;
		var el = $("input.checkbox:checked");
		el.each(function() {
			checked++;
		});

		if (checked > 0) {
			eModal.confirm('Apakah anda yakin data akan dihapus?', 'Konfirmasi').then(function() {
				var url = _BASE_URL + O.controller + '/' + O.delete_action,
				id = [],
				i = 0,
				is_deleted = 0;
				el.each(function() {
					var value = $(this).val();
					if ( ! $('#tr_' + value).hasClass('delete')) {
						id[i] = value;
						i++;
						is_deleted++;
					}
				});

				if (is_deleted > 0) {
					var values = {};
					values["id"] = id.join(',');
					_H.Loading( true );
					$.post(url, values, function( response ) {
						_H.Loading( false );
						var res = _H.StrToObject( response );
						_this.Notify(res.status, _this.Message(res.message));
						$("input[type='checkbox']:checked").prop('checked', false);
						if (res.action == 'delete_permanently') {
							_this.Reload();
						} else {
							$.each(res.id, function(key, value) {
								if ( ! $('#tr_' + value).hasClass('delete')) {
									$('#tr_' + value).addClass('delete');
								}
							});
						}
					}).fail( function( xhr, textStatus, errorThrown ) {
						_H.Loading( false );
						xhr.textStatus = textStatus;
						xhr.errorThrown = errorThrown;
						if( !errorThrown ) errorThrown = 'Unable to load resource, network connection or server is down?';
						_this.Notify('error', textStatus + ' ' + errorThrown + '<br/>' + xhr.responseText );
					});
				} else {
					_this.Notify('warning', _this.Message('not_deleted'));
				}
			});
		} else {
			_this.Notify('info', _this.Message('not_selected'));
		}
	};

	/**
	 * Restore function
	 */
	this.Restore = function() {
		var _this = this, O = _this.options;
		var checked = 0;
		var el = $("input.checkbox:checked");
		el.each(function() {
			checked++;
		});

		if (checked > 0) {
			eModal.confirm('Apakah anda yakin data akan dikembalikan?', 'Konfirmasi').then(function() {
				var url = _BASE_URL + O.controller + '/' + O.restore_action,
				id = [],
				i = 0,
				is_restored = 0;
				el.each(function() {
					var value = $(this).val();
					if ($('#tr_' + value).hasClass('delete')) {
						id[i] = value;
						i++;
						is_restored++;
					}
				});

				if (is_restored > 0) {
					var values = {};
					values["id"] = id.join(',');
					_H.Loading( true );
					$.post(url, values, function( response ) {
						_H.Loading( false );
						var res = _H.StrToObject( response );
						_this.Notify(res.status, _this.Message(res.message));
						$("input[type='checkbox']:checked").prop('checked', false);
						$.each(res.id, function(key, value) {
							if ($('#tr_'+value).hasClass('delete')) {
								$('#tr_' + value).removeClass('delete');
							}
						});
					}).fail( function( xhr, textStatus, errorThrown ) {
						_H.Loading( false );
						xhr.textStatus = textStatus;
						xhr.errorThrown = errorThrown;
						if( !errorThrown ) errorThrown = 'Unable to load resource, network connection or server is down?';
						_this.Notify('error', textStatus + ' ' + errorThrown + '<br/>' + xhr.responseText );
					});
				} else {
					_this.Notify('warning', _this.Message('not_restored'));
				}
			});
		} else {
			_this.Notify('info', _this.Message('not_selected'));
		}
	};

	/**
	 * Next Page
	 */
	this.NextPage = function() {
		var _this = this, O = _this.options;
		_this.CursorFocused();
		O.page_number++;
		_this.Reload();
	};

	/**
	 * Previous Page
	 */
	this.PrevPage = function() {
		var _this = this, O = _this.options;
		_this.CursorFocused();
		O.page_number--;
		_this.Reload();
	};

	/**
	 * First Page
	 */
	this.FirstPage = function() {
		var _this = this, O = _this.options;
		_this.CursorFocused();
		O.page_number = 0;
		_this.Reload();
	};

	/**
	 * Last Page
	 */
	this.LastPage = function() {
		var _this = this, O = _this.options;
		_this.CursorFocused();
		O.page_number = O.total_page - 1;
		_this.Reload();
	};

	/**
	 * Set per-page
	 */
	this.SetPerPage = function() {
		var _this = this, O = _this.options;
		_this.CursorFocused();
		O.page_number = 0;
		O.per_page = $('.per-page option:selected').val();
		_this.Reload();
	};


	/**
	 * On Reload clicked
	 */
	this.OnReload = function() {
		var _this = this;
		_this.CursorFocused();
		// set page number jadi 0 jika ingin hasil reload diset ke page 1
		// _this.options.page_number = 0;
		_this.Reload();
	}

	/**
	 * Search
	 */
	this.Search = function(event) {
		var _this = this;
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode === 13) {
			_this.options.keyword = $('.keyword').val();
			_this.options.page_number = 0; // Reset to 0
			_this.Reload();
		}
	};

	/**
	 * Set cursor Focus
	 */
	this.CursorFocused = function() {
		$('input.keyword').focus();
	}

	/**
	 * Table Row / TR
	 * @param String
	 * @return String
	 */
	this.TR = function( str ) {
		return '<tr>' + str + '</tr>';
	};

	/**
	 * Table Row with id / TR
	 * @param String
	 * @param String
	 * @param String
	 * @return String
	 */
	this.TRid = function( id, is_deleted, str ) {
		if (is_deleted == 'true') {
			return '<tr id="tr_' + id + '" class="delete highlight">' + str + '</tr>';
		}
		return '<tr id="tr_' + id + '">' + str + '</tr>';
	};

	/**
	 * Table Header / TH
	 * @param String
	 * @param String
	 * @param String
	 * @param String
	 * @return String
	 */
	this.TH = function( str, field, exclude_excel, sorting ) {
		var _this = this, onClick = '', joinClass = '', arrayClass = [];
		if (typeof(field) == 'string') {
			arrayClass.push('field_' + field);
		}
		if ( exclude_excel ) arrayClass.push('exclude_excel');
		if (sorting) {
			arrayClass.push('sorting');
			arrayClass.push('sort_both');
		}
		if ( arrayClass.length ) joinClass = ('class="' + arrayClass.join(' ') + '"');
		if (sorting) {
			var field = "'" + field + "'";
			var _grid = _this.options.name + '.Sorting(' + field + ')';
			onClick = 'onclick="' + _grid + '"';
		}
		return '<th ' + joinClass + ' ' + onClick + ' data-sort="ASC">' + str + '</th>';
	};

	/**
	 * Sorting
	 * @param String
	 * @return void
	 */
	this.Sorting = function( field ) {
		var _this = this, O = _this.options;
		var el = $('th.field_' + field);
		var sort_type = el.attr('data-sort');
		// Remove All Class ASC DESC
		$( $('.table').find('th.sorting') ).removeClass('sort_asc sort_desc').addClass('sort_both');
		// Set Class
		if (sort_type == 'ASC') {
			$(el).attr('data-sort', 'DESC');
			$(el).removeClass('sort_both sort_desc').addClass('sort_asc');
		} else {
			$(el).attr('data-sort', 'ASC');
			$(el).removeClass('sort_both sort_asc').addClass('sort_desc');
		}
		// Sorting Rows
		O.rows.sort(_this.SortTable(field, sort_type));
		_this.RenderTable(O.rows);
	}

	/**
	 * Sort Data Table
	 * @param String
	 * @param String
	 * @return Array
	 */
	this.SortTable = function(key, order) {
		return function(a, b) {
			if ( ! a.hasOwnProperty( key ) || ! b.hasOwnProperty( key )) return 0;
			var x = (typeof a[ key ] === 'string') ? a[ key ].toUpperCase() : a[ key ];
			var y = (typeof b[ key ] === 'string') ? b[ key ].toUpperCase() : b[ key ];
			var comparison = 0;
			if (x > y) {
				comparison = 1;
			} else if (x < y) {
				comparison = -1;
			}
			return ((order == 'DESC') ? (comparison * -1) : comparison);
		};
	}

	/**
	 * Table Data / TD
	 * @param String
	 * @param String
	 * @return String
	 */
	this.TD = function( str, exclude_excel ) {
		return '<td ' + (exclude_excel ? 'class="exclude_excel"' : '') + '>' + str + '</td>';
	};

	/**
	 * Notify
	 * @param String
	 * @param String
	 * @return Void
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
	this.Message = function( status ) {
		var message;
		switch ( status ) {
			case 'not_selected':
				message = 'Tidak ada item yang terpilih!';
				break;
			case 'restored':
				message = 'Data berhasil dikembalikan!';
				break;
			case 'not_restored':
				message = 'Terjadi kesalahan. Data tidak berhasil dikembalikan!';
				break;
			case 'deleted':
				message = 'Data berhasil dihapus!';
				break;
			case 'not_deleted':
				message = 'Terjadi kesalahan. Data tidak berhasil dihapus!';
				break;
			case 'empty':
				message = 'Data tidak ditemukan!';
				break;
			default:
				message = status;
				break;
		}
		return message;
	};

	/**
	 * Table Export
	 * @param String
	 * @return Blob
	 */
	this.ExportExcel = function( type ) {
		var self = this;
		var type = type || 'xlsx';
		var elem_id = 'table-renderer';
		var div = '<div id="' + elem_id +'" style="display: none;"></div>';
		$( div ).appendTo( document.body );
		var table = $( '.data-table-renderer' ).html();
		$( '#' + elem_id ).html( table );
		var file_name = $('.table-header').text() + '-' + new Date().toISOString().replace(/[\-\:\.]/g, "") + '.' + type;
		self.ConvertHTML( elem_id, type, file_name );
		$( '#' + elem_id ).remove();
	},

	/**
	 * Convert HTML
	 * @param String
	 * @param String
	 * @param String
	 * @return application/octet-stream
	 */
	this.ConvertHTML = function(id, type, fn) {
		var self = this;
		var wb = XLSX.utils.table_to_book(document.getElementById(id), {sheet:'Sheet1'});
		var wbout = XLSX.write(wb, {bookType:type, bookSST:true, type: 'binary'});
		var fname = fn || 'test.' + type;
		try {
			saveAs(new Blob([self.StringToArrayBuffered(wbout)],{type:"application/octet-stream"}), fname);
		} catch(e) {
			console.log( e, wbout );
		}
		return wbout;
	},

	/**
	 * String To Array Buffered
	 * @param String
	 * @return ArrayBuffer
	*/
	this.StringToArrayBuffered = function( s ) {
		if(typeof ArrayBuffer !== 'undefined') {
			var buf = new ArrayBuffer(s.length);
			var view = new Uint8Array(buf);
			for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
			return buf;
		} else {
			var buf = new Array(s.length);
			for (var i=0; i!=s.length; ++i) buf[i] = s.charCodeAt(i) & 0xFF;
			return buf;
		}
	}

}).call( GridBuilder.prototype );
