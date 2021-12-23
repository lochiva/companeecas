angular.module("Clienti", [])
    .controller('cliente', function($http, $scope, $timeout) {
        var vm = this;
        vm.editing = false;
        vm.forms = [];

        vm.loadAzienda = loadAzienda;
        vm.addSede = addSede;
        vm.addContatto = addContatto;
        vm.resetModel = resetModel;
        vm.checkSubmit = checkSubmit;
        //vm.saveAzienda = saveAzienda;
        vm.clienteModel = aziendaModel;
        vm.sedeModel = sedeModel;
        vm.contattoModel = contattoModel;


        vm.cliente = new vm.clienteModel().azienda;

        $timeout(function(){
          $("[name='gruppi']").select2({
                language: 'it',
                width: '100%'
          });
        }, 0);

        function aziendaModel(){
            this.azienda = {
                denominazione:'', nome:'',cognome:'',cod_paese:'IT',piva:'',cf:'',cod_eori:'',
                telefono:'', pec:'', email_info:'', email_contabilita:'',email_solleciti:'',
                sito_web:'', fax:'', cliente:false, fornitore:false, interno:false, sedi:[],contatti:[],gruppi:[]
            };
        }

        function sedeModel(){
            this.sede = {
                id_azienda:(!vm.cliente.id ? '' : vm.cliente.id), id_tipo:'', indirizzo:'', num_civico:'', cap:'', comune:'',
                provincia:'', nazione:'', telefono:'', email:'', cellulare:'', fax:'', skype:'',id: 'sede-'+vm.cliente.sedi.length,
            };
        }

        function contattoModel(){
            this.contatto = {
                id_azienda:(!vm.cliente.id ? '' : vm.cliente.id), id_sede:'', id_ruolo:'', nome:'', cognome:'', telefono:'',
                cellulare:'', email:'', fax:'', skype:'', indirizzo:'', num_civico:'', cap:'', id: 'contatto-'+vm.cliente.contatti.length,
                comune:'', provincia:'', nazione:'', cf:'',skills:[]
            };
        }

        function checkSubmit() {

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
                $(tab.parentTab).trigger("click");
                $(tab.childTab).trigger("click");
                alert(msg);
                first.focus();
            }else{
              //  vm.saveAzienda();
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
                vm.cliente = response.data.data;
                $timeout(function(){
                    $("[name='skills']").select2({
                          language: 'it',
                          width: '100%'
                    });
                    $("[name='gruppi']").trigger('change');
                }, 0);

            }, function myError(response) {
                $scope.myWelcome = response.statusText;
            });
        }

        function saveAzienda(){
            $http.post(pathServer + 'aziende/Ws/saveAziendaJson/', vm.cliente).then(function mySucces(response) {
                if(response.data.response == 'OK'){
                    afterSaveModalAziende();

                }else{
                    alert(response.data.msg);
                }
            }, function myError(response) {
                alert(response);
            });
        }

        function addSede() {
            var nuovaSede = new vm.sedeModel().sede;
            vm.editing = true;
            vm.cliente.sedi.push(nuovaSede);
            $timeout(function(){
                $('#click_subtab_sede_'+nuovaSede.id).trigger("click");
            }, 0);

        }

        function addContatto() {
            var nuovoContatto = new vm.contattoModel().contatto;
            vm.editing = true;
            vm.cliente.contatti.push(nuovoContatto);
            $timeout(function(){
                $('#click_subtab_contatto_'+nuovoContatto.id).trigger("click");
                $("[name='skills']").select2({
                      language: 'it',
                      width: '100%'
                });
            }, 0);

        }

        function resetModel(){
            vm.cliente = new vm.clienteModel().azienda;
            vm.editing = false;
            vm.forms = [];
            vm.forms.push($scope.angularForm);
            $('input, select').parentsUntil('div.form-group, div.input').parent().removeClass('has-error');
            $('#click_tab_1').trigger("click");
            $scope.$apply();
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

    });
