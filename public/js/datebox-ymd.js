function myformatter(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    //return (d<10?('0'+d):d)+'\/'+(m<10?('0'+m):m)+'\/'+y;
    if(y<1900){
        return '';
    }
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d)
}
function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}
$(function(){
    $.extend($.fn.validatebox.defaults.rules, {
        greaterThan:{
            validator: function(value,param){
                var v1 = '1900-01-01';
                var d1 = myparser(v1);
                var d2 = myparser(value);
                return d2 > d1;
            },
            message: 'Tanggal Salah.'
        },
        validateRek:{
            validator: function(value,param){
                var nama_bank=$("#nama_bank").combobox('getValue');
                if(nama_bank=='Mandiri'){
                    return value.length==13;
                }else if(nama_bank=='BSM'){
                    return value.length==10;
                }
                else if(nama_bank=='BRI'){
                    return value.length==15;
                }
                return true;
            },
            message: 'Format Salah.'
        },
    });
});
