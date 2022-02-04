angular.module("Aziende", ['ui.select', 'ngSanitize'])
    .controller('aziende', function($http, $scope, $timeout) {
        var vm = this;
        vm.editing = false;
        vm.forms = [];

        vm.loadAzienda = loadAzienda;
        vm.addSede = addSede;
        vm.addContatto = addContatto;
        vm.resetModel = resetModel;
        vm.checkSubmit = checkSubmit;
        vm.saveAzienda = saveAzienda;
        vm.aziendaModel = aziendaModel;
        vm.sedeModel = sedeModel;
        vm.contattoModel = contattoModel;


        vm.azienda = new vm.aziendaModel().azienda;

        $timeout(function(){
          $("[name='gruppi']").select2({
                language: 'it',
                width: '100%'
          });
        }, 0);

        //select user contatto
        $scope.getUsers = function($select) {
            return $http.get(pathServer+'ws/autocompleteUser', {
                params: {
                    q: $select.search,
                }
            }).then(
                function mySucces(response) {
                    $scope.users = response.data.data;

                }, function myError(response) {
                    $scope.myWelcome = response.statusText;
                }
            );
        }
        

        function aziendaModel(){
            this.azienda = {
                denominazione:'', codice_provincia:'', nome:'',cognome:'',cod_paese:'IT',piva:'',cf:'',cod_eori:'',
                telefono:'', pec:'', pec_commissione:'', email_info:'', email_contabilita:'',email_solleciti:'', pa_codice:'',
                sito_web:'', fax:'', cliente:false, fornitore:false, interno:false, logo:'', logo_to_save:'',
                sedi:[],contatti:[],gruppi:[]
            };
        }

        function sedeModel(){
            this.sede = {
                id_azienda:(!vm.azienda.id ? '' : vm.azienda.id), id_tipo:'', indirizzo:'', num_civico:'', cap:'', comune:'', comune_des:'',
                provincia:'', nazione:'', telefono:'', email:'', cellulare:'', fax:'', skype:'', n_posti:0, tipologie_ospiti:[],
                id: 'sede-'+vm.azienda.sedi.length,
            };
        }

        function contattoModel(){
            this.contatto = {
                id_azienda:(!vm.azienda.id ? '' : vm.azienda.id), id_sede:'', id_ruolo:'', nome:'', cognome:'', id_user:'', telefono:'',
                cellulare:'', email:'', fax:'', skype:'', indirizzo:'', num_civico:'', cap:'', id: 'contatto-'+vm.azienda.contatti.length,
                comune:'', comune_des:'', provincia:'', nazione:'', cf:'',skills:[]
            };
        }

        function checkSubmit() {

            $('#saveModalAziende').prop('disabled', true);

            var first = null;
            var msg = '';
            var tab = { parentTab:'#tab_1', childTab:''};

            $('input, select').change(function(){
              $(this).parentsUntil('div.form-group, div.input').parent().removeClass('has-error');
            });
            if(vm.forms.length === 0){
                vm.forms.push($scope.angularForm);
            }
            $.each(vm.forms, function(l, form){
                $.each(form.$error, function(index, value) {

                    $.each(value, function(i, error) {

                        elem = error.$$element;
                        if (!first) {
                            first = elem;
                            if(form.parentTab !== undefined && form.childTab !== undefined){
                                tab.parentTab = form.parentTab.$$attr.value;
                                tab.childTab = form.childTab.$$attr.value;
                            }
                            if(error.errorMsg != undefined && error.errorMsg != ''){
                                msg = error.errorMsg;
                            }else{
                                msg = 'Il campo ' +error.$name+ ' risulta vuoto o errato.';
                            }

                        }

                        $(elem).parentsUntil('div.form-group, div.input').parent().addClass('has-error');

                    });
                });
            });

            if (first) { 
                $('#saveModalAziende').prop('disabled', false);
                $(tab.parentTab).trigger("click");
                $(tab.childTab).trigger("click");
                alert(msg);
                first.focus();
            }else{
                vm.saveAzienda();
            }
        }

        function loadAzienda(id,tabs) {

            var params = {};
            vm.resetModel();

            $http.get(pathServer + 'aziende/Ws/loadAzienda/' + id, params).then(function mySucces(response) {
                if(tabs !== undefined){
                  if(tabs.childTab !== undefined){
                      vm.editing = true;
                  }
                  $timeout(function(){
                    $(tabs.parentTab).trigger("click");
                    $(tabs.childTab).trigger("click");
                  }, 0);
                }

                vm.azienda = response.data.data.azienda;
				if(response.data.data.sede != null){
					vm.sede = response.data.data.sede;
				}
				if(response.data.data.cliente != null){
					vm.cliente = response.data.data.cliente;
				}
				if(response.data.data.fornitore != null){
					vm.fornitore = response.data.data.fornitore;
				}
				vm.tipo = [];
				if(vm.azienda.id_cliente_fattureincloud != 0 && vm.azienda.id_fornitore_fattureincloud == 0){
					vm.tipo = vm.cliente;
				}
				if(vm.azienda.id_fornitore_fattureincloud != 0 && vm.azienda.id_cliente_fattureincloud == 0){
					vm.tipo = vm.fornitore;
				}

                $timeout(function(){
                    $('[name="tipologie_ospiti"]').select2({
                        language: 'it',
                        width: '100%'
                    });

                    $("[name='skills']").select2({
                          language: 'it',
                          width: '100%'
                    });
                    $("[name='gruppi']").trigger('change');

                    $('.select-provincia').each(function(index){
                        var select_provincia = $(this);
                        select_provincia.select2({
                            language: 'it',
                            width: '100%',
                            placeholder: 'Seleziona una provincia',
                            closeOnSelect: true,
                            dropdownParent: select_provincia.parent(),
                        });
                        if(vm.azienda.sedi[index].provincia != ''){
                            select_provincia.val(vm.azienda.sedi[index].provincia).trigger('change'); 
                        }
                    });

                    $('.select-provincia-contatto').each(function(index){
                        var select_provincia_cont = $(this);
                        select_provincia_cont.select2({
                            language: 'it',
                            width: '100%',
                            placeholder: 'Seleziona una provincia',
                            closeOnSelect: true,
                            dropdownParent: select_provincia_cont.parent(),
                        });
                        if(vm.azienda.contatti[index].provincia != ''){
                            select_provincia_cont.val(vm.azienda.contatti[index].provincia).trigger('change'); 
                        }
                    });
                }, 0);

            }, function myError(response) {
                $scope.myWelcome = response.statusText;
            });
        }

        function saveAzienda(){ 
            var form_data = new FormData();
            for ( var key in vm.azienda ) {
                if(key == 'gruppi' || key == 'contatti' || key == 'sedi'){
                    form_data.append(key, JSON.stringify(vm.azienda[key]));
                }else{
                    form_data.append(key, vm.azienda[key]);
                }
            }
            $http.post(pathServer + 'aziende/Ws/saveAziendaJson/', form_data, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function mySucces(response) {
                if(response.data.response == 'OK'){
                    afterSaveModalAziende();
                    
                }else{
                    alert(response.data.msg);
                    $('#saveModalAziende').prop('disabled', false);
                }
            }, function myError(response) {
                alert(response);
                $('#saveModalAziende').prop('disabled', false);
            });
        }

        function addSede() {
            var nuovaSede = new vm.sedeModel().sede;
            vm.editing = true;
            vm.azienda.sedi.push(nuovaSede);
            $timeout(function(){
                $('#click_subtab_sede_'+nuovaSede.id).trigger("click");
                $('[name="tipologie_ospiti"]').select2({
                    language: 'it',
                    width: '100%'
                });
                var select_provincia = $('#subtab_sede_'+(vm.azienda.sedi.length - 1)).find('.select-provincia');
                select_provincia.select2({
                    language: 'it',
                    width: '100%',
                    placeholder: 'Seleziona una provincia',
                    closeOnSelect: true,
                    dropdownParent: select_provincia.parent(),
                });
            }, 0);

        }

        function addContatto() {
            var nuovoContatto = new vm.contattoModel().contatto;
            vm.editing = true;
            vm.azienda.contatti.push(nuovoContatto);
            $timeout(function(){
                $('#click_subtab_contatto_'+nuovoContatto.id).trigger("click");
                $("[name='skills']").select2({
                      language: 'it',
                      width: '100%'
                });
                var select_provincia_cont = $('#subtab_contatto'+(vm.azienda.contatti.length - 1)).find('.select-provincia-contatto');
                select_provincia_cont.select2({
                    language: 'it',
                    width: '100%',
                    placeholder: 'Seleziona una provincia',
                    closeOnSelect: true,
                    dropdownParent: select_provincia_cont.parent(),
                });
                
            }, 0);

        }

        function resetModel(){
            vm.azienda = new vm.aziendaModel().azienda;
			vm.sede = new vm.sedeModel().sede;
			vm.cliente = [];
			vm.fornitore = [];
            vm.editing = false;
			vm.tipo = [];
            vm.forms = [];
            vm.forms.push($scope.angularForm);
            $('input, select').parentsUntil('div.form-group, div.input').parent().removeClass('has-error');
            $('#click_tab_1').trigger("click");
            $scope.$apply();
            document.getElementById("inputLogo").value = "";
        }

    }).directive('piva', function() {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {
                ctrl.$validators.piva = function(modelValue, viewValue) {
                    if (ctrl.$isEmpty(modelValue)) {
                        // consider empty models to be valid
                        return true;
                    }
                    msg = ControllaPIVA(viewValue);
                    if (msg == 'OK') {
                        // it is valid
                        return true;
                    }
                    ctrl.errorMsg = msg;
                    // it is invalid
                    return false;
                };
            }
        };
    }).directive('cf', function() {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {
                ctrl.$validators.piva = function(modelValue, viewValue) {
                    if (ctrl.$isEmpty(modelValue)) {
                        // consider empty models to be valid
                        return true;
                    }
                    if(isNaN(viewValue) ){
              				msgCf = ControllaCF(viewValue);
              			}else{
              				msgCf = ControllaPIVA(viewValue, 'Il codice fiscale di una persona giuridica');
              			}

                    if (msgCf == 'OK') {
                        // it is valid
                        return true;
                    }
                    ctrl.errorMsg = msgCf;
                    // it is invalid
                    return false;
                };
            }
        };
    }).directive('convertToNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(val) {
                    return parseInt(val, 10);
                });
                ngModel.$formatters.push(function(val) {
                    return '' + val;
                });
            }
        };
    }).directive('repeatPushForm', function() {

        return function(scope, element) {

            if(scope.angularForm !== undefined){
                scope.$parent.vm.forms.push(scope.angularForm);
            }

        };

    }).directive("fileUpload", function() {
        return {
            require: "ngModel",
            link: function (scope, element, attributes, ngModel) {
                element.bind("change", function (changeEvent) {
                    var file = element[0].files[0];
                    var valid_types = ['image/jpeg', 'image/png'];
                    if(valid_types.includes(file.type)){
                        ngModel.$setViewValue(file);
                    }else{
                        document.getElementById("inputLogo").value = "";
                        alert("Il logo deve essere un'immagine valida.");
                    }
                });
            }
        }
    });
