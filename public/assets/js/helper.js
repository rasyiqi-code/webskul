/* Toastr Configurations */
toastr.options = {
   "closeButton": true,
   "debug": false,
   "newestOnTop": false,
   "progressBar": true,
   "positionClass": "toast-top-right",
   "preventDuplicates": false,
   "showDuration": "300",
   "hideDuration": "1000",
   "timeOut": "5000",
   "extendedTimeOut": "1000",
   "showEasing": "swing",
   "hideEasing": "linear",
   "showMethod": "fadeIn",
   "hideMethod": "fadeOut"
};

// convert to money format
// example : 1250 -> 1.250
Number.prototype.to_money = function(n, x) {
   var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
   return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
};

// Helper function
var _H = _H || {};

/**
 * convert json string to object, useful for getting JSON AJAX response
 * @param String
 * @return Object
 */
_H.StrToObject = function( v ) {
   if( 'object'== typeof v ) return v;
   if( 'string'!= typeof v ) {
      console.log( v );
      throw('cannot parse non-json-string')
   }
   if( v == '' ) return v;
   return eval( '(' + v + ')' ); // JSON.parse
};

/**
 * Notify handler
 * @param String
 * @param String
 */
_H.Notify = function(type, message) {
   switch (type) {
      case 'success' :
         toastr.success(message, 'Sukses' );
         break;
      case 'info' :
         toastr.info(message, 'Info' );
         break;
      case 'warning' :
         toastr.warning(message, 'Peringatan' );
         break;
      case 'error' :
         toastr.error(message, 'Terjadi Kesalahan' );
         break;
      default :
         toastr.error(message, 'Terjadi Kesalahan' );
         break;
   }
};

/**
 * Name of Month
 * @param String
 * @return Object | String
 */
_H.Month = function( v ) {
   var month = {
      '01':'Januari',
      '02':'Februari',
      '03':'Maret',
      '04':'April',
      '05':'Mei',
      '06':'Juni',
      '07':'Juli',
      '08':'Agustus',
      '09':'September',
      '10':'Oktober',
      '11':'Nopember',
      '12':'Desember'
   };
   return (typeof v == 'undefined') ? month : month[ v ];
};

/**
 * Check validate date format
 * @param String
 * @return Boolean
 */
_H.IsValidDate = function( v ) {
   // First check for the pattern
   var regex_date = /^\d{4}\-\d{1,2}\-\d{1,2}$/;
   if ( ! regex_date.test(v)) return false;

   // Parse the date parts to integers
   var parts = v.split("-");
   var day = parseInt(parts[2], 10);
   var month = parseInt(parts[1], 10);
   var year = parseInt(parts[0], 10);

   // Check the ranges of month and year
   if ( year < 1000 || year > 3000 || month == 0 || month > 12 ) return false;

   var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
   // Adjust for leap years
   if ( year % 400 == 0 || (year % 100 != 0 && year % 4 == 0 )) {
      monthLength[1] = 29;
   }

   // Check the range of the day
   return day > 0 && day <= monthLength[month - 1];
}

/**
 * Get day name from YYYY-MM-DD
 * @param String
 * @return String
 */
_H.DayName = function( v ) {
   if ( ! _H.IsValidDate( v ) ) console.error( '' + v + ' is not valid date');
   var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
   return days[ new Date( v ).getDay() ];
}

/**
 * Indonesian Date Formated
 * @param String
 * @return String
 */
_H.ToIndonesianDate = function( v ) {
   if (v === undefined || v === null) return;
   var explode = v.split('-'),
   year  = explode[0],
   month = explode[1],
   day   = explode[2],
   bulan = _H.Month(month);
   return day + ' ' + bulan + ' ' + year;
};

/**
 * Convert to Byte Format
 * @param String
 * @param String
 * @return String
 */
_H.FormatBytes = function(bytes, decimal) {
   if(bytes == 0) return '0 Byte';
   var k = 1000;
   var dm = decimal + 1 || 3;
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
   var i = Math.floor(Math.log(bytes) / Math.log(k));
   return (bytes / Math.pow(k, i)).toPrecision(dm) + ' ' + sizes[i];
};

/**
 * Handler alert status
 * @param String
 */
_H.Message = function( v ) {
   var msg = '';
   switch (v) {
      case 'created':
         msg = 'Data Anda telah disimpan !';
         break;
      case 'not_created':
         msg = 'Terjadi kesalahan dalam menyimpan data Anda !';
         break;
      case 'updated':
         msg = 'Data Anda telah diperbaharui !';
         break;
      case 'not_updated':
         msg = 'Data Anda tidak dapat diperbaharui !';
         break;
      case '404':
         msg = 'Halaman tidak ditemukan !';
         break;
      case 'deleted':
         msg = 'Data Anda telah dihapus !';
         break;
      case 'not_deleted':
         msg = 'Terjadi kesalahan dalam menghapus data Anda !';
         break;
      case 'restored':
         msg = 'Data Anda telah dikembalikan !';
         break;
      case 'not_restored':
         msg = 'Terjadi kesalahan dalam mengembalikan data Anda !';
         break;
      case 'not_selected':
         msg = 'Tidak ada item terpilih !';
         break;
      case 'existed':
         msg = 'Data sudah tersedia !';
         break;
      case 'empty':
         msg = 'Data tidak tersedia !';
         break;
      case 'required':
         msg = 'Field harus diisi !';
         break;
      case 'not_numeric':
         msg = 'ID bukan tipe angka';
         break;
      case 'keyword_empty':
         msg = 'Kata kunci pencarian tidak boleh kosong, dan minimal 3 karakter !';
         break;
      case 'no_changed':
         msg = 'Tidak ada data yang berubah !';
         break;
      case 'logged_in':
         msg = 'Log In berhasil. Halaman akan dialihkan dalam 2 detik. Jika tidak dialihkan, silahkan refresh browser Anda!</a>';
         break;
      case 'not_logged_in':
         msg = 'Log In gagal. Nama akun dan/atau kata sandi yang Anda masukan salah.';
         break;
      case 'forbidden':
         msg = 'Akses ditolak!';
         break;
      case 'extracted':
         msg = 'Tema berhasil diextract';
         break;
      case 'not_extracted':
         msg = 'Tema gagal diextract';
         break;
      default :
         msg = v;
         break;
   }
   return msg;
};

/**
* Uppercase first letter of variable
*/
String.prototype.ucwords = function() {
   var str = this.toLowerCase();
   return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
   function(s){
      return s.toUpperCase();
   });
};

/**
 * Show/Hide Loading
 */
_H.Loading = function ( isTrue ) {
   var el = $( 'body' );
   var str = '<div id="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>';
   isTrue ? el.append( str ) : el.find( '#loader-wrapper' ).remove();
};

/**
 * Image preview before uploaded
 * @param Boolean
 */
_H.Preview = function( input ) {
   if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
         $('#preview').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
   }
};

/**
 * Export to Excel
 * @param String
 * @param String
 * @param String
 * @return Blob
 */
_H.ExportToExcel = function( elem_id, file_name, type ) {
   var type = type || 'xlsx';
   var file_name = file_name + '-' + new Date().toISOString().replace(/[\-\:\.]/g, "") + '.' + type;
   _H.ConvertHTML( elem_id, type, file_name );
   $( '#' + elem_id ).remove();
};

/**
 * Convert HTML
 * @param String
 * @param String
 * @param String
 * @return application/octet-stream
 */
_H.ConvertHTML = function(id, type, fn) {
   var wb = XLSX.utils.table_to_book(document.getElementById(id), {sheet:'Sheet1'});
   var wbout = XLSX.write(wb, {bookType:type, bookSST:true, type: 'binary'});
   var fname = fn || 'test.' + type;
   try {
      saveAs(new Blob([_H.StringToArrayBuffered(wbout)],{type:"application/octet-stream"}), fname);
   } catch(e) {
      console.log( e, wbout );
   }
   return wbout;
};

/**
 * String To Array Buffered
 * @param String
 * @return ArrayBuffer
 */
_H.StringToArrayBuffered = function( s ) {
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
};

/**
 * Change Sidebar Collapse
 * @param String
 * @return Void
 */
_H.SidebarCollapse = function() {
    $.post(_BASE_URL + 'dashboard/sidebar_collapse');
}
