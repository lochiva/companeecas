<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$role = $this->request->session()->read('Auth.User.role');
?>

<!-- VUE -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.2/axios.js"></script>

<!-- SELECT VUE -->
<link rel="stylesheet" href="https://unpkg.com/vue-select@3.1.0/dist/vue-select.css">
<script src="https://unpkg.com/vue-select@3.1.0"></script>

<!-- FAB -->
<script src="https://unpkg.com/vue-material"></script>

<!-- OBSERVE VISIBILITY -->
<script src="https://unpkg.com/intersection-observer@0.5.0"></script>
<script src="https://unpkg.com/vue-observe-visibility@0.4.2"></script>

<!-- DATEPICKER -->
<script src="https://unpkg.com/vuejs-datepicker"></script>
<script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/it.js"></script>

<script>
    var role = "<?= $role ?>";
</script>

<?php $this->assign('title', 'Interviste') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->css('Surveys.vue-interviews'); ?>
<?= $this->Html->script( 'Surveys.surveys', ['block']); ?>
<?= $this->Html->script( 'Surveys.vue-interviews', ['block' => 'script-vue']); ?>

<div id='app-interviews'>

    <section class="content-header">
        <h1 v-if="interviewData.idInterview"><?=__c('Modifica intervista')?></h1>
        <h1 v-else><?=__c('Nuova intervista')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
            <li v-if="role =='admin'"><a href="<?=Router::url('/surveys/surveys/index');?>"> <?=__c('Questionari')?></a></li>
            <li v-if="role =='admin'"><a :href="'<?=Router::url('/surveys/surveys/interviews/');?>'+idSurvey"> <?=__c('Interviste')?></a></li>
            <li v-if="role !='admin'"><a href="<?=Router::url('/surveys/surveys/managingEntities');?>"><?=__c('Aziende')?></a></li>
            <li v-if="role !='admin'"><a :href="'<?=Router::url('/surveys/surveys/structures/');?>'+interviewData.idGestore"><?=__c('Sedi')?></a></li>
            <li v-if="role !='admin'"><a :href="'<?=Router::url('/surveys/surveys/interviews/0/');?>'+interviewData.idGestore+'/'+interviewData.idStructure"><?=__c('Interviste')?></a></li>
            <li v-if="interviewData.idInterview" class="active"><?=__c('Modifica intervista')?></li>
            <li v-else class="active"><?=__c('Nuova intervista')?></li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-answers" class="box box-surveys">
                    <div class="box-header">
                        <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" style="margin-left:10px"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body body-answers"> 
                        <div v-if="role == 'admin' && interviewData.idInterview == 0" class="div-selects">
                            <div class="col-sm-4">
                                <label>Azienda</label>
                                <v-select :options="partners" id="selectPartner" @input="setSelectedPartner" :value="selectedPartner" :clearable="false" placeholder="Seleziona un'azienda">
                                    <div slot="no-options">Nessuna azienda trovata.</div>
                                </v-select>
                            </div>
                            <div class="col-sm-4">
                                <label>Sede</label>
                                <v-select :disabled="!selectedPartner" :options="structures" id="selectStructure" @input="setSelectedStructure" :clearable="false" :value="selectedStructure" placeholder="Seleziona una sede">
                                    <div slot="no-options">Nessuna sede trovata.</div>
                                </v-select>
                            </div>
                        </div>

                        <div v-show="role == 'admin' && interviewData.idInterview == 0 && (!selectedPartner || !selectedStructure)" class="warning-disabled-interview">
                            <span>ATTENZIONE: La compilazione sarà abilitata solo dopo aver selezionato le informazioni inerenti la sede da intervistare.</span>
                        </div>

                        <h2 v-html="interviewData.title"></h2>
                        <h4 v-html="interviewData.subtitle"></h4>
                        <p v-html="interviewData.description"></p>     

                        <div id="interview-answers">
                            <script type="text/x-template" id="item-template">
                                <div class="box box-item" v-bind:style="{ borderTopColor: item.color }">
                                    <div class="box-header" @click="toggle">
                                        <span class="open-icon"><i v-if="isOpen" class="fa fa-chevron-down"></i><i v-else class="fa fa-chevron-right"></i></span>
                                        <h3 class="box-surveys-title" v-html="label+' '+item.title"></h3>
                                    </div>
                                    <div v-show="isOpen" class="box-body">
                                        <h4 v-html="item.subtitle"></h4>

                                        <!-- DOMANDE/ELEMENTI -->
                                        <div class="questions-div">
                                            <div v-for="question in item.questions">
                                            
                                                <!-- TESTO LIBERO -->
                                                <div v-if="question.type == 'free_text' || question.type == 'standard_text'" v-html="question.value" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible"></div>

                                                <!-- IMMAGINE -->
                                                <div v-if="question.type == 'image' && question.path != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <img :src="'<?=Router::url('/surveys/ws/viewImage/');?>'+question.path" class="element-image" >
                                                    <p v-html="question.caption"></p>
                                                </div>

                                                <!-- RISPOSTA BREVE -->
                                                <div v-if="question.type == 'short_answer' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="text" class="form-control" v-model="question.answer" />
                                                </div>

                                                <!-- RISPOSTA APERTA -->
                                                <div v-if="question.type == 'free_answer' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <textarea :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" class="textarea-answer" v-model="question.answer"></textarea>
                                                </div>

                                                <!-- DATA -->
                                                <div v-if="question.type == 'date' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <datepicker :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="question.answer"></datepicker>
                                                </div>

                                                <!-- NUMERO -->
                                                <div v-if="question.type == 'number' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="number" class="form-control number-integer" v-model="question.answer" />
                                                </div>

                                                <!-- RADIO SI/NO -->
                                                <div v-if="question.type == 'yes_no' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="radio" value="yes" v-model="question.answer" @change="updateConditionedQuestions(question)" :checked="question.answer == 'yes'" /> Sì 
                                                    <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="radio" class="radio-no" value="no" v-model="question.answer" @change="updateConditionedQuestions(question)" :checked="question.answer == 'no'" /> No
                                                </div>

                                                <!-- SCELTA SINGOLA -->
                                                <div v-if="question.type == 'single_choice' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <table v-if="question.view_mode == 'list'" class="table-question-choice">
                                                        <tr v-for="(option, index) in question.options">
                                                            <td class="td-question-check">
                                                                <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="radio" :value="index" class="question-check-option" v-model="question.answer.check" @change="emptyExtensionSingle(question.answer.extensions); updateConditionedQuestions(question);" />
                                                                <label class="question-choice-label" v-html="option.text"></label>
                                                            </td>
                                                            <td>
                                                                <input v-if="option.extended" :disabled="status == 2 || !(question.answer.check === index && option.extended) || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="text" class="form-control input-extended-answer" v-model="question.answer.extensions[index]" />
                                                            </td>
                                                        </tr>  
                                                    </table>
                                                    <select v-if="question.view_mode == 'select'" :disabled="status == 2" class="form-control answer-select" v-model="question.answer.check" @change="emptyExtensionSingle(question.answer.extensions); updateConditionedQuestions(question);">
                                                        <option value=""></option>
                                                        <option v-for="(option, index) in question.options" :value="index" v-html="option.text"></option>
                                                    </select>
                                                    <input v-if="question.options[question.answer.check] != undefined && question.options[question.answer.check].extended" :disabled="status == 2" type="text" class="form-control answer-select-extended" v-model="question.answer.extensions[index]" />
                                                </div>

                                                <!-- SCELTA MULTIPLA -->
                                                <div v-if="question.type == 'multiple_choice' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <table class="table-question-choice" :class="{'multiple-choice-scroll': question.scroll}">
                                                        <tr v-for="(option, index) in question.options">
                                                            <td class="td-question-check">
                                                                <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="checkbox" class="question-check-option" v-model="question.answer[index].check" @change="emptyExtensionMultiple(question.answer[index])" />
                                                                <label v-html="option.text"></label>
                                                            </td>
                                                            <td>
                                                                <input v-if="option.extended" :disabled="status == 2 || !question.answer[index].check || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="text" class="form-control input-extended-answer" v-model="question.answer[index].extended"/>    
                                                            </td>
                                                        </tr>
                                                        <tr v-if="question.other">
                                                            <td class="td-question-check">
                                                                <input :disabled="status == 2 || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="checkbox" class="question-check-option" v-model="question.other_answer_check" @change="emptyAnswer(question)" :checked="question.other_answer_check" />
                                                                <label>Altro</label>
                                                            </td>
                                                            <td>
                                                                <input :disabled="status == 2 || !question.other_answer_check || (role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" type="text" class="form-control" v-model="question.other_answer" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <!-- TABELLA -->
                                                <div v-if="question.type == 'table' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                    <div class="question-text">   
                                                        <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                        &nbsp;
                                                        <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                        <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                    </div>
                                                    <table class="table table-bordered table-question-table">
                                                        <thead>
                                                            <tr>
                                                                <th v-for="header in question.headers" v-html="header"></th>
                                                                <th v-show="status != 2 && !(role == 'admin' && idInterview == 0 && (!selectedPartner || !selectedStructure))" class="question-table-actions-col">
                                                                    <button type="button" class="btn btn-info btn-xs" @click="addRowTable({'headers': question.headers, 'answer': question.answer})" title="Aggiungi riga">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(answer, index) in question.answer"> 
                                                                <td v-for="(a, i) in answer" >
                                                                    <input v-if="status != 2" type="text" class="form-control" v-model="answer[i]" />
                                                                    <span v-else v-html="a"></span>
                                                                </td>
                                                                <td v-show="status != 2" class="text-center">
                                                                    <button type="button" class="btn btn-danger btn-xs" @click="removeRowTable({'answer': question.answer, 'index': index})" title="Rimuovi riga">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody> 
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-show="isOpen" class="children">
                                        <tree-item
                                            v-for="(child, index) in item.items"
                                            :key="index"
                                            :index="index"
                                            :item="child"
                                            :label="label+'.'+(index+1)"
                                            :role="role"
                                            :selected-partner="selectedPartner"
                                            :selected-structure="selectedStructure"
                                            :id-interview="idInterview"
                                            :status="status"
                                        ></tree-item>
                                    </div>
                                </div>
                            </script>
                            <tree-item
                                v-for="(child, index) in interviewData.items"
                                :key="index"
                                :index="index"
                                :item="child"
                                :label="index+1"
                                :role="role"
                                :selected-partner="selectedPartner"
                                :selected-structure="selectedStructure"
                                :id-interview="interviewData.idInterview"
                                :status="interviewData.status"
                            ></tree-item>
                        </div>
                    </div>
                </div>

                <div 
                    class="box-footer" 
                    v-observe-visibility="{callback: checkInViewport}">
                    <button v-if="interviewData.idInterview" type="button" class="btn btn-default interview-pdf" :data-id="interviewData.idInterview" ><i class="fa fa-file-pdf-o" title="Scarica ispezione in PDF"></i> PDF ispezione</button>
                    <button v-if="interviewData.idInterview && interviewData.status !== 2" type="button" class="btn btn-warning" @click="setInterviewSigned()" >Firmata</button>
                    <button class="btn btn-primary pull-right save-interview-exit button-margin" @click="saveInterview(true)">Salva ed esci</button>
                    <button class="btn btn-success pull-right save-interview-stay button-margin" @click="saveInterview()">Salva</button>
                    <a v-if="role == 'admin'" :href="'<?=Router::url('/surveys/surveys/interviews/');?>'+idSurvey" class="btn btn-default pull-right">Annulla</a>
                    <a v-if="role != 'admin'" :href="'<?=Router::url('/surveys/surveys/interviews/0/');?>'+interviewData.idGestore+'/'+interviewData.idStructure" class="btn btn-default pull-right">Annulla</a>
                </div>
            </div>
        </div>
    </section>  

    <md-speed-dial v-show="!footerInViewport" class="fab-position md-fixed" md-direction="top" md-event="click">
        <md-speed-dial-target class="md-fab fab-main">
            <md-icon class="md-morph-initial">add</md-icon>
            <md-icon class="md-morph-final">close</md-icon>
        </md-speed-dial-target>

        <md-speed-dial-content>
            <md-button v-show="interviewData.idInterview" class="md-fab fab-light interview-pdf" :data-id="interviewData.idInterview" title="Scarica ispezione in PDF">
                <md-icon>picture_as_pdf</md-icon>
            </md-button>

            <md-button v-show="interviewData.idInterview && interviewData.status !== 2" class="md-fab fab-warning" @click="setInterviewSigned()" title="Firmata">
                <md-icon>lock</md-icon>
            </md-button>

            <a v-if="role == 'admin'" :href="'<?=Router::url('/surveys/surveys/interviews/');?>'+idSurvey">
                <md-button class="md-fab fab-default" title="Annulla">
                    <md-icon>arrow_back</md-icon>
                </md-button>
            </a>
            <a v-if="role != 'admin'" :href="'<?=Router::url('/surveys/surveys/interviews/0/');?>'+interviewData.idGestore+'/'+interviewData.idStructure">
                <md-button class="md-fab fab-default" title="Annulla">
                    <md-icon>arrow_back</md-icon>
                </md-button>
            </a>

            <md-button class="md-fab fab-success save-interview-stay" @click="saveInterview()" title="Salva">
                <md-icon>save</md-icon>
            </md-button>

            <md-button class="md-fab fab-primary save-interview-exit" @click="saveInterview(true)" title="Salva ed esci">
                <md-icon><i class="glyphicon glyphicon-floppy-remove fab-icon"></i></md-icon>
            </md-button>
        </md-speed-dial-content>
    </md-speed-dial>

</div>

<?= $this->element('Surveys.modal_tooltip_question'); ?>
