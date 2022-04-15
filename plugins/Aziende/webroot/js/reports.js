$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};
$(document).ready(function(){

	$('.datepicker').datepicker({language: 'it'});

    //Generazione report ospiti emergenza ucraina
    $('#exportGuestsEmergenzaUcraina').click(function() {
        $('#template-spinner').show();

        var url = pathServer+'aziende/reports/reportGuestsEmergenzaUcraina';

        var date = $('#date').val();
        url += '?date=' + encodeURI(date);
        
        window.location = url;

        checkCookieForLoader('downloadStarted', '1');
    });

});

function checkCookieForLoader(name, value) {
    var cookie = getCookie(name);

    if (cookie == value) {
        $('#template-spinner').hide();
        document.cookie = 'downloadStarted=0;path=/';
    } else {
        setTimeout(function () { checkCookieForLoader(name, value); }, 300);
    }
}