
// Config datepicker \\
$.datepicker.regional['it'] = {
    closeText: 'Chiudi',
    prevText: '&#x3c;Prec',
    nextText: 'Succ&#x3e;',
    currentText: 'Oggi',
    defaultDate: null,
    monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
    monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'],
    dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
    dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
    dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'],
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false
};


function showModal(selector)
{
    if(typeof selector != 'undefined')
    {
        selector.reveal({
             animation: 'fadeAndPop',
             animationspeed: 300,
             closeonbackgroundclick: true,
             dismissmodalclass: 'close-reveal-modal'
        });
    }
}

function formValidation(idForm)
{
   var ckError = false;
 	 var msgError = "";
 	 var firstElem = '';
   function searchElementAddError(elem){
      if($(elem).parent().hasClass('input') || $(elem).parent().hasClass('form-group')){
          $(elem).parent().addClass('has-error');
          return;
      }else{
          searchElementAddError($(elem).parent());
      }
   }
 	//Controllo i campi obbligatori
 		$('#'+idForm+' input:visible, #'+idForm+' select:visible, #'+idForm+' textarea:visible').each(function(){
      $(this).closest('.input').removeClass('has-error');
 			if($(this).val() == "" || $(this).val() == null ){
        if($(this).hasClass('required')){
            ckError = true;
     				msgError = "Si prega di compilare tutti i campi obbligatori";
            searchElementAddError(this);
     				if(firstElem == ""){
     					firstElem = this;
     				}
        }
 			}else{
          if($(this).attr('name') == 'cf' || $(this).attr('name') == 'fiscalcode' || $(this).hasClass('check-cf')){
              var msgCf = '';
              if(isNaN($(this).val()) ){
                msgCf = ControllaCF($(this).val());
              }else{
                msgCf = ControllaPIVA($(this).val(), 'Il codice fiscale di una persona giuridica');
              }
              if(msgCf != "OK"){

         				ckError = true;
                if(firstElem == ""){
                  firstElem = this;
                  msgError = msgCf;
                }
         				searchElementAddError(this);
         			}
          }else if($(this).attr('name') == 'email' || $(this).attr('type') == 'email' || $(this).hasClass('check-email')){
            if(!validateEmail($(this).val())){
              ckError = true;
              if(firstElem == ""){
                 firstElem = this;
                 msgError = "Si prega di inserire una mail valida";
              }
              searchElementAddError(this);
            }
          }else if($(this).attr('name') == 'piva'){
              var msgIva = ControllaPIVA($(this).val());
              if(msgIva != "OK"){
         				ckError = true;
                if(firstElem == ""){
                  firstElem = this;
                  msgError = msgIva;
                }
         				searchElementAddError(this);
         			}
          }else if($(this).hasClass('not-zero')){
              if($(this).val() == 0){
                ckError = true;
                msgError = "Si prega di compilare tutti i campi obbligatori";
                searchElementAddError(this);
                if(firstElem == ""){
                  firstElem = this;
                }
              }
          }
      }

 		});

 	 if(ckError == true){
     if($(firstElem).attr('data-tab') != undefined && $(firstElem).attr('data-tab') != null){
       $($(firstElem).attr('data-tab')).click();
     }
 		 alert(msgError);
 		 $(firstElem).focus();
     return false;
 	 }

   return true;
}

function showHideLoadingSpinner(action)
{
  //$('#template-spinner').show();
  if(action !== undefined){
    switch (action) {
      case 'show':
        $('#template-spinner').show();
        break;
      case 'hide':
        $('#template-spinner').hide();
        break;

    }
  }else{
    if($('#template-spinner').is(':hidden')){
        $('#template-spinner').show();
    }else{
        $('#template-spinner').hide();
    }
  }
}

function setTableSorterTempPager(table,pageAndSize)
{
    var pagerTemp = localStorage.getItem("tablesorter-pager-temp");
    if(pagerTemp != undefined && pagerTemp != null){
      pagerTemp = JSON.parse(pagerTemp);
    }else{
      pagerTemp = {};
    }
    pagerTemp[table] = pageAndSize;
    pagerTemp = JSON.stringify(pagerTemp);
    localStorage.setItem("tablesorter-pager-temp",pagerTemp);
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

function calculateStringOperation(string)
{

      str = string.replace(/,/g, ".");
      str = str.match(/[\d\(\)\+\-\*\/\.]/g);
      var res = Number(0).toFixed(2).toString().replace(/\./g, ",");

      if(str != null){
          str = str.join('');
      }else{
          str = 0;
      }
      try {
          res = eval(str).toFixed(2).toString().replace(/\./g, ",");
      } catch(ex1) {

      }

      return res;
}

/**
 * Utilizzato nei tablesorter, calcola la posizione in tempo reale del dropdown,
 * in modo da impedire comportamenti non desiderati. L'elemento di richiamo del dropdown
 * deve avere la classe .dropdown-tableSorter.
 * @return {void}
 */
function calculateTableDropdownPosition()
{
    function setDropdownCalculate(obj)
    {
       var menu = $(obj).parent().find('.dropdown-menu');
       var calculatePosition = function(){
         var offset = $(obj).offset();
         var position = {};

         position.top = offset.top - $(document).scrollTop() + $(obj).height() + 5;
         position.left = offset.left - $(document).scrollLeft() - 110;


         $(menu).css({'position':'fixed', 'top':position.top, 'left':position.left});
       };

        calculatePosition();
        $(window).unbind('scroll');
        $('.table-content').unbind('scroll');

        $(window).scroll(calculatePosition);
        $(".table-content").on('scroll', calculatePosition);
    }
    $('.dropdown-tableSorter').off('click');
    $('.dropdown-tableSorter').on('click', function(){
         setDropdownCalculate(this);
    });
}



/**
 * Aggiunge un helper ai input comune, provincia e cap di un form. Si aspetta l'id
 * del form, e delle opzioni: {prefix:'',all:true } . 'prefix' aggiunge un prefisso
 * ai nomi dell'input in modo multidimensionale, 'all' aggiunge all alle chiamate.
 *
 * @param  {string} idForm id del form
 * @param  {object} opt    oggetto opt
 * @return {void}
 */
function luoghiAutocomplete(idForm, opt)
{
    var provincia = '[name="provincia"]';
    var comune = '[name="comune"]';
    var cap = '[name="cap"]';
    var all = '';

    if(opt !== undefined){
        if(opt.prefix != null){
            provincia = '[name="'+opt.prefix+'[provincia]"]';
            comune = '[name="'+opt.prefix+'[comune]"]';
            cap = '[name="'+opt.prefix+'[cap]"]';
        }else if(opt.names != null){
            provincia = '[name="'+opt.names.provincia+'"]';
            comune = '[name="'+opt.names.comune+'"]';
            cap = '[name="'+opt.names.cap+'"]';
        }
        if(opt.all){
            all = true;
        }
    }
    provincia = $('#'+idForm).find(provincia)[0];
    comune = $('#'+idForm).find(comune)[0];
    cap = $('#'+idForm).find(cap)[0];
    $(comune).select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona il comune',
        dropdownParent: $(cap).parent(),
        closeOnSelect: true,
        val: '',
        tags:true,
        allowClear: true,
    });
    $(cap).select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona il cap',
        dropdownParent: $(cap).parent(),
        closeOnSelect: true,
        val: '',
        tags:true,
        allowClear: true,
    });
    $(comune).attr('disabled',true);
    $(cap).attr('disabled',true);
    $(provincia).append('<option value=""></option>');
    $.ajax({
        url : pathServer + "ws/getProvince/"+all,
        type: "GET",
        dataType: "json",
        success : function (data,stato) {
          //console.log($(this).parent());return;
          $(provincia).select2({
              language: 'it',
              width: '100%',
              placeholder: 'Seleziona una provincia',
              dropdownParent: $(provincia).parent(),
              closeOnSelect: true,
              data: data.data,
              val: '',
              tags:true,
              allowClear: true,
          });

          $(provincia).val('').trigger('change');
          //$(provincia).val('').trigger('change');
        },
        error : function (data){

        }
    });
    $(provincia).change(function(){
      if($(this).val() != null && $(this).val() != ''){
          $(comune).attr('disabled',false);
      }else{
          $(comune).attr('disabled',true);
      }
      if(!window.fillingModal){
        $.ajax({
            url : pathServer + "ws/getLuoghi/"+all,
            type: "POST",
            data: { q:$(this).val() },
            dataType: "json",
            success : function (data,stato) {
              //console.log($(this).parent());return;
              $(comune).select2('destroy').empty().select2({
                  language: 'it',
                  width: '100%',
                  placeholder: 'Seleziona il comune',
                  dropdownParent: $(comune).parent(),
                  closeOnSelect: true,
                  val: '',
                  tags:true,
                  data: data.data,
                  allowClear: true,
              });
              $(comune).val('').trigger('change');
            },
            error : function (data){

            }
        });
      }
    });
     $(comune).change(function(){
       if($(this).val() != null && $(this).val() != ''){
           $(cap).attr('disabled',false);
       }else{
           $(cap).attr('disabled',true);
       }
       if(!window.fillingModal){
           $.ajax({
               url : pathServer + "ws/getCap/"+all,
               type: "POST",
               data: { q:$(this).val() },
               dataType: "json",
               success : function (data,stato) {
                 //console.log($(this).parent());return;
                 $(cap).select2('destroy').empty().select2({
                     language: 'it',
                     width: '100%',
                     placeholder: 'Seleziona il cap',
                     dropdownParent: $(cap).parent(),
                     closeOnSelect: true,
                     val: '',
                     tags:true,
                     data: data.data,
                     allowClear: true,
                 });
                 var val = '';
                 if(data.data.length == 1){
                    val = data.data[0].id;
                 }
                 $(cap).val(val).trigger('change');
                 //$(provincia).val('').trigger('change');
               },
               error : function (data){

               }
           });
         }
     });
     $('#'+idForm).on('reset', function(){
          var tmp = window.fillingModal;
          window.fillingModal = true;
          $(provincia).val('').trigger('change');
          $(comune).empty().val('').trigger('change');
          $(cap).empty().val('').trigger('change');
          window.fillingModal = tmp;
     });

}

function fillLuoghiAutocomplete(data,opt){

      if(opt !== undefined){
          if(opt.names != null){
              var form = '';
              if(opt.idForm != null){
                form = '#'+opt.idForm+' ';
              }
              opt.names.provincia = form+'[name="'+opt.names.provincia+'"]';
              opt.names.comune = form+'[name="'+opt.names.comune+'"]';
              opt.names.cap = form+'[name="'+opt.names.cap+'"]';
          }else{
            console.error('fillLuoghiAutocomplete no names error');
            return;
          }
      }else{
        console.error('fillLuoghiAutocomplete no opt error');
        return;
      }
      $.each(data, function(index,value){

          if($(opt.names[index]+" option[value='"+value+"']").length === 0){
             $(opt.names[index]).append('<option value="'+value+'">'+value+'</option>');

          }
          $(opt.names[index]).val(value).trigger('change');
      });
}

/**
 * Riempie gli input di un form dall'oggetto dato. Seleziona il form secondo l'idForm
 * inserito. In caso di una data si aspetta che l'iunput sia un datepicker. Nel caso
 * che riceve un boolean, lo trasforma in intero. E' in grado di riempire fino a 2 dimensioni
 * in caso di form multidimensionali.
 *
 * @param  {object} data   oggetto dei dati
 * @param  {string} idForm id del form
 * @return {void}
 */
function fillFormGeneral(data, idForm)
{
  function fillInput(data,index, idForm){
    if(moment(data, moment.ISO_8601, true).isValid() ){
        $(idForm+' [name="'+index+'"]').val(moment(data).format('DD/MM/YYYY'));
        $(idForm+' [name="'+index+'"]').datepicker('update');
    }else if($(' [name="'+index+'"]').attr('type') == 'checkbox'){
        if(data == true || data == 1){
            $(idForm+' [name="'+index+'"]').attr("checked",true);
        }
    }else if(typeof(data) == 'boolean'){
        $(idForm+' [name="'+index+'"]').val(+data);
    }else{
        $(idForm+' [name="'+index+'"]').val(data);
    }
  }
  if(idForm === undefined){
    idForm = '';
  }else{
    idForm = '#'+idForm;
  }

  $.each(data , function(index,data){
        if(Array.isArray(data) || typeof data === 'object' ){
          $.each(data, function(i,val){
            i = index+'['+i+']';
            fillInput(val,i,idForm);
          });
        }else{

          fillInput(data,index,idForm);
        }


  });
}

function addSelectPlaceholder()
{

  function checkSelectPlaceholder(){
    var val = $(this).val();
    if(val == '' || val == null || val == 0){
       $(this).addClass('select-placeholder');
    }else{
       $(this).removeClass('select-placeholder');
    }
  }
  $('select.add-placeholder').addClass('select-placeholder');
  $('select.add-placeholder').on({
      mousedown: function(){
        $(this).removeClass('select-placeholder');
      },
      mouseup: checkSelectPlaceholder,
      focusout: checkSelectPlaceholder,
      change: checkSelectPlaceholder
  });

}

function alertErrorMessage(msg,element)
{
   if(element === undefined){
     element = 'body';
   }
   var now = Date.now();
   $(element).prepend('<div id="error-message'+now+'" class="message error alert alert-danger">'+msg+'</div>');
   $(document).ready(function(){

     function hideError(){
       setTimeout(function(){
         if($('#error-message'+now).is(":hover")){
           return hideError();
         }
         $('#error-message'+now).hide("slow");
       }, 4000);
     }

     hideError();
   });
}

function alertWarningMessage(msg,element)
{
   if(element === undefined){
     element = 'body';
   }
   var now = Date.now();
   $(element).prepend('<div id="warning-message'+now+'" class="message warning alert alert-warning">'+msg+'</div>');
   $(document).ready(function(){

     function hideError(){
       setTimeout(function(){
         if($('#warning-message'+now).is(":hover")){
           return hideError();
         }
         $('#warning-message'+now).hide("slow");
       }, 4000);
     }

     hideError();
   });
}

function alertSuccessMessage(msg,element)
{
   if(element === undefined){
     element = 'body';
   }
   var now = Date.now();
   $(element).prepend('<div id="success-message'+now+'" class="message success alert alert-success">'+msg+'</div>');
   $(document).ready(function(){

     function hideError(){
       setTimeout(function(){
         if($('#success-message'+now).is(":hover")){
           return hideError();
         }
         $('#success-message'+now).hide("slow");
       }, 4000);
     }

     hideError();
   });
}

function checkCookieForLoader(name, value) {
  var cookie = getCookie(name);

  if (cookie == value) {
      $('#template-spinner').hide();
      document.cookie = 'downloadStarted=0;path=/';
  } else {
      setTimeout(function () { checkCookieForLoader(name, value); }, 300);
  }
}

function multipleFormValidation(forms)
{
  var errors = [];

   function searchElementAddError(elem){
      if($(elem).parent().hasClass('input') || $(elem).parent().hasClass('form-group')){
          $(elem).parent().addClass('has-error');
          return;
      }else{
          searchElementAddError($(elem).parent());
      }
   }

   for (let f of forms) {

    ckError = false;
 	  f.msgError = "";
 	  f.firstElem = '';
    let idForm = f.form;
    $(f.el).trigger("click");

     	//Controllo i campi obbligatori
 		$('#'+idForm+' input:visible, #'+idForm+' select:visible, #'+idForm+' textarea:visible').each(function(){
      $(this).closest('.input').removeClass('has-error');
 			if($(this).val() == "" || $(this).val() == null ){
        if($(this).hasClass('required')){
          if(!$(this).prop('readonly')){
            ckError = true;
            f.msgError = "Si prega di compilare tutti i campi obbligatori";
           searchElementAddError(this);
            if(f.firstElem == ""){
              f.firstElem = this;
            }
          }
        }
 			}else{
          if($(this).attr('name') == 'cf' || $(this).attr('name') == 'fiscalcode' || $(this).hasClass('check-cf')){
              var msgCf = '';
              if(isNaN($(this).val()) ){
                msgCf = ControllaCF($(this).val());
              }else{
                msgCf = ControllaPIVA($(this).val(), 'Il codice fiscale di una persona giuridica');
              }
              if(msgCf != "OK"){

         				ckError = true;
                if(f.firstElem == ""){
                  f.firstElem = this;
                  f.msgError = msgCf;
                }
         				searchElementAddError(this);
         			}
          }else if($(this).attr('name') == 'email' || $(this).attr('type') == 'email' || $(this).hasClass('check-email')){
            if(!validateEmail($(this).val())){
              ckError = true;
              if(f.firstElem == ""){
                 f.firstElem = this;
                 f.msgError = "Si prega di inserire una mail valida";
              }
              searchElementAddError(this);
            }
          }else if($(this).attr('name') == 'piva'){
              var msgIva = ControllaPIVA($(this).val());
              if(msgIva != "OK"){
         				ckError = true;
                if(f.firstElem == ""){
                  f.firstElem = this;
                  f.msgError = msgIva;
                }
         				searchElementAddError(this);
         			}
          }else if($(this).hasClass('not-zero')){
              if($(this).val() == 0){
                ckError = true;
                f.msgError = "Si prega di compilare tutti i campi obbligatori";
                searchElementAddError(this);
                if(f.firstElem == ""){
                  f.firstElem = this;
                }
              }
          }
      }

 		});
    if(ckError) {
      errors.push(f);
    }

   }

    if (errors.length) {
      if(errors.length > 1) {
        $(errors[0].el).trigger("click");
        alert('Si prega di controllare i campi in rosso');
        $(errors[0].firstElem).focus();
        return false;
      } else {
        $(errors[0].el).trigger("click");

          alert(errors[0].msgError);
          $(errors[0].firstElem).focus();
          return false;
      }
    } else {
      return true;
    }

}