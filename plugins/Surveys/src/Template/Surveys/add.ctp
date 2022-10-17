<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>

<!-- VUE -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.2/axios.js"></script>

<!-- SELECT VUE -->
<link rel="stylesheet" href="https://unpkg.com/vue-select@3.1.0/dist/vue-select.css">
<script src="https://unpkg.com/vue-select@3.1.0"></script>

<!-- DRAG & DROP -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js"></script>

<!-- FAB -->
<script src="https://unpkg.com/vue-material"></script>

<!-- OBSERVE VISIBILITY -->
<script src="https://unpkg.com/intersection-observer@0.5.0"></script>
<script src="https://unpkg.com/vue-observe-visibility@0.4.2"></script>

<!-- TOGGLE SWITCH -->
<?= $this->Html->script('Surveys.ToggleSwitch.umd.min.js', ['block']); ?>

<!-- TEXT EDITOR -->
<?= $this->Html->script('tinymce/tinymce.min.js', ['block']); ?>
<?= $this->Html->script('tinymce/tinymce-vue.min.js', ['block']); ?>

<script>
    var baseImageUrl = '<?=$baseImageUrl?>';
    var placeholders = <?= json_encode($placeholders) ?>;
</script>

<?php $this->assign('title', 'Modelli preventivi') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->css('Surveys.vue-surveys'); ?>
<!--<?= $this->Html->script('Surveys.questions.js', ['block']); ?>-->
<?= $this->Html->script('Surveys.elements.js', ['block']); ?>
<?= $this->Html->script('Surveys.surveys', ['block']); ?>
<?= $this->Html->script('Surveys.vue-surveys', ['block' => 'script-vue']); ?>

<div id='app-surveys'>

    <section class="content-header">
        <h1>
            <span v-if="surveyData.idSurvey">Modifica modello</span>
            <span v-else>Nuovo modello</span>
            <small v-if="surveyData.idSurvey">v{{surveyData.version}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?=Router::url('/surveys/surveys/index');?>">Modelli preventivi</a></li>
            <li class="active">
                <span v-if="surveyData.idSurvey">Modifica modello</span>
                <span v-else>Nuovo modello</span>
            </li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-surveys" class="box box-surveys">
                    <div class="box-header with-border">
                        <i class="fa fa-list-alt"></i>
                        <h3 class="box-title">Intestazione modello</h3>
                        <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body">
                        <form id="formSurvey" class="form-horizontal">
                            <div id="survey-header">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label class="required" for="surveyConfigurator"><?= __('Configuratore') ?></label>
                                        <select class="form-control" name="configurator" id="surveyConfigurator" v-model="surveyData.id_configurator"
                                            @change="updateSectionsList()" @focus="warningChangeConfigurator($event)">
                                            <option value=""></option>
                                            <option v-for="configurator in configurators" :value="configurator.id">{{configurator.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label class="required" for="surveyTitle"><?= __('Titolo') ?></label>
                                        <input type="text" maxlength="255" class="form-control" name="title" id="surveyTitle" v-model="surveyData.title" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required" for="surveySubtitle"><?= __('Sottotitolo') ?></label>
                                        <input type="text" maxlength="255" class="form-control" name="subtitle" id="surveySubtitle" v-model="surveyData.subtitle" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="surveyDescription"><?= __('Descrizione') ?></label>
                                        <textarea class="form-control" id="surveyDescription" name="description" v-model="surveyData.description"></textarea>
                                    </div>
                                    <!--
                                    <div class="col-md-3">
                                        <label class="required" for="surveyStatus"><?= __('Stato') ?></label>
                                        <select class="form-control" name="status" id="surveyStatus" :value="surveyData.status" @change="changeStatus(surveyData.status, $event)" >
                                            <option value=""></option>
                                            <option v-for="status in statuses" v-bind:value="status.id">{{status.name}}</option>
                                        </select>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="box-chapters" class="box box-surveys">
                    <div class="box-header with-border">
                        <i class="fa fa-list"></i>
                        <h3 class="box-title">Sezioni</h3>
                        <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true" style="margin-left:10px"></i> indietro </a>
                        <button type="button" id="addChapter" class="btn btn-info btn-xs pull-right" @click="addItem(surveyData, true)" title="Aggiungi sezione"><i class="fa fa-plus"></i> Sezione</button>
                    </div>
                    <div class="box-body">
                        <script type="text/x-template" id="template-draggable">
                            <draggable :list="items" ghostClass="ghost" handle=".draggable-area" :move="onMovedItem" forceFallback="true" :group="{name: 'g1'}" :empty-insert-threshold="50" :swap-threshold="0.2">
                                <div v-for="(item, index) in items" class="box box-item survey-section" v-bind:style="{ borderTopColor: item.color }" >
                                    <div class="box-header draggable-area" style="display:flex;" @click="toggle(item)">
                                        <div class="chapter-label">
                                            <span class="open-icon"><i v-if="item.open" class="fa fa-chevron-down"></i><i v-else class="fa fa-chevron-right"></i></span>
                                            <h3 class="box-title" v-html="item.title"></h3>
                                        </div>
                                        <div class="chapter-actions">
                                            <button v-if="!item.primary" type="button" class="btn btn-warning btn-xs" @click.stop="showModalItemVisibility(item, 'section')" title="Configurazione visibilià della sottosezione"><i class="fa fa-eye"></i> Visibilità</button>
                                            <toggle-switch v-if="item.primary" class="layout-switch" :options="sectionToggleOptions" :group="'layoutSwitch'+index" v-model="item.layout" />
                                            <button v-if="item.primary" type="button" class="btn btn-info btn-xs add-subsection" @click.stop="$emit('add-item', item); forceOpen()" title="Aggiungi sottosezione"><i class="fa fa-plus"></i> Sottosezione</button>
                                            <button type="button" class="btn btn-danger btn-xs remove-item" @click.stop="$emit('remove-item', {'item':parentitem, 'index':index})" title="Cancella"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div v-show="item.open" class="box-body">
                                        <div class="form-group section-detail">
                                            <div class="col-md-6">
                                                <label class="required">Titolo</label>
                                                <input type="text" maxlength="255" class="form-control" name="title" v-model="item.title" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required" for="surveySubtitle">Sottotitolo</label>
                                                <input type="text" maxlength="255" class="form-control" name="subtitle" v-model="item.subtitle" />
                                            </div>
                                        </div>

                                        <!-- DOMANDE/ELEMENTI GENERICI -->
                                        <div class="questions-div">
                                            <draggable :list="item.questions" ghostClass="ghost" handle=".draggable-area" forceFallback="true" >
                                                <div v-for="(question, index) in item.questions" class="question-div">
                                                    <div class="action-buttons">
                                                        <a class="btn-add-hover" @click="showModalElements({'item': item, 'index': index, 'label':label, 'type': ''})" title="Aggiungi elemento"><i class="fa fa-plus"></i></a>
                                                        <a v-if="question.preview" class="btn-preview-hover" title="Anteprima domanda" @click="showModalPreviewQuestion(question)"><i class="fa fa-search"></i></a>
                                                        <a v-if="question.type == 'data_sheet'" class="btn-visibility-hover" title="Visibilità elemento" @click="showModalItemVisibility(question, 'element')"><i class="fa fa-eye"></i></a>
                                                        <a class="btn-move-hover draggable-area" title="Sposta elemento"><i class="fa fa-arrows"></i></a>
                                                        <a class="btn-delete-hover" @click="removeQuestion({'questions': item.questions, 'index': index})" title="Elimina elemento"><i class="fa fa-trash"></i></a>
                                                    </div>

                                                    <!-- TESTO FISSO -->
                                                    <div v-if="question.type == 'fixed_text'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <editor v-model="question.value" :init="editor"></editor>
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        -->
                                                        <div class="col-md-12">
                                                            <ul class="list-placeholders">
                                                                <li v-for="placeholder in placeholders"><span v-html="placeholder.label"></span> - <span v-html="placeholder.description"></span></li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <!-- IMMAGINE -->
                                                    <div v-if="question.type == 'image'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div v-if="question.path == ''" class="col-md-12">
                                                            <input type="file" class="form-control" :ref="'image'+index" @change="saveImagePath({'index': index, 'question': question, 'idSurvey': survey.idSurvey})" />
                                                        </div>
                                                        <div v-if="question.path != ''" class="col-md-12">
                                                            <a class="btn btn-default" title="Visualizza immagine" @click="viewImage(question.path)"><i class="fa fa-eye"></i></a>
                                                            <a class="btn btn-danger" title="Elimina immagine" @click="deleteImage(question)"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Didascalia</label>
                                                            <input type="text" class="form-control" v-model="question.caption" />
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        -->
                                                    </div>

                                                    <!-- RISPOSTA EDITOR DI TESTO -->
                                                    <div v-if="question.type == 'answer_text_editor'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Visibile in tabella</label>
                                                            <input type="checkbox" v-model="question.show_in_table" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_table" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per tabella" v-model="question.label_table" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Esportabile in excel</label>
                                                            <input type="checkbox" v-model="question.show_in_export" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_export" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per excel" v-model="question.label_export" />
                                                        </div>
                                                        -->
                                                        <div class="col-md-12">
                                                            <editor v-model="question.answer" :init="editor"></editor>
                                                        </div>
                                                    </div>

                                                    <!-- SCHEDA TECNICA -->
                                                    <div v-if="question.type == 'data_sheet'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <div class="grey-box-survey-element">
                                                                <span v-html="question.data_sheet.label"></span>:
                                                                <span v-if="question.visibility_by_component">
                                                                    <span v-if="question.components.length > 1">
                                                                        presente se attivo uno o più tra i componenti
                                                                    </span>
                                                                    <span v-else>
                                                                        presente se attivo il componente
                                                                    </span>
                                                                    <span v-for="component in question.components" v-html="component.text" class="component-label-element-survey"></span>
                                                                </span>
                                                                <span v-else>sempre presente</span>
                                                            </div>
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>    
                                                        -->                                                    
                                                    </div>

                                                    <!-- MISURE -->
                                                    <div v-if="question.type == 'dimensions'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <div class="grey-box-survey-element">
                                                                Misure
                                                            </div>
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>    
                                                        -->                                                    
                                                    </div>

                                                    <!-- SALTO PAGINA -->
                                                    <div v-if="question.type == 'page_break'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <div class="grey-box-survey-element">
                                                                Salto pagina
                                                            </div>
                                                        </div>
                                                        <!--
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>    
                                                        -->                                                    
                                                    </div>

                                                    <!-- TESTO STANDARD -->
                                                    <div v-if="question.type == 'standard_text'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> (<span v-html="question.name"></span>) <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <editor v-model="question.value" :init="editor"></editor>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>                                                        
                                                    </div>

                                                    <!-- RISPOSTA BREVE, RISPOSTA APERTA, DATA, NUMERO -->
                                                    <div v-if="question.type == 'short_answer' || question.type == 'free_answer' || question.type == 'date' || question.type == 'number'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Visibile in tabella</label>
                                                            <input type="checkbox" v-model="question.show_in_table" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_table" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per tabella" v-model="question.label_table" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Esportabile in excel</label>
                                                            <input type="checkbox" v-model="question.show_in_export" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_export" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per excel" v-model="question.label_export" />
                                                        </div>
                                                    </div>

                                                    <!-- RADIO SI/NO -->
                                                    <div v-if="question.type == 'yes_no'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" @change="changeConditioningQuestion({'question_id': question.id, 'question': question.question})"/>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-show="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Visibile in tabella</label>
                                                            <input type="checkbox" v-model="question.show_in_table" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_table" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per tabella" v-model="question.label_table" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Esportabile in excel</label>
                                                            <input type="checkbox" v-model="question.show_in_export" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_export" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per excel" v-model="question.label_export" />
                                                        </div>
                                                    </div>

                                                    <!-- SCELTA SINGOLA -->
                                                    <div v-if="question.type == 'single_choice'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" @change="changeConditioningQuestion({'question_id': question.id, 'question': question.question})"/>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Modalità visualizzazione</label>
                                                            <input type="radio" class="" value="list" v-model="question.view_mode" /> Lista 
                                                            <input type="radio" class="radio-no" value="select" v-model="question.view_mode" /> Tendina
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Visibile in tabella</label>
                                                            <input type="checkbox" v-model="question.show_in_table" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_table" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per tabella" v-model="question.label_table" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Esportabile in excel</label>
                                                            <input type="checkbox" v-model="question.show_in_export" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_export" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per excel" v-model="question.label_export" />
                                                        </div>
                                                        <div class="col-md-12 div-option">
                                                            <label>Opzioni</label> <button type="button" class="btn btn-info btn-xs btn-add-option" @click="addOptionSingle({'options': question.options, 'extensions': question.answer.extensions})" title="Aggiungi opzione"><i class="fa fa-plus"></i></button>
                                                            <div v-for="(option, index) in question.options" class="div-option">
                                                                <div class="col-md-11 padding0">
                                                                    <input type="text" class="form-control" v-model="question.options[index].text" @change="changeConditioningOptions({'question_id': question.id, 'options': question.options})"/> 
                                                                    <input type="checkbox" v-model="question.options[index].extended" /> Completamento manuale
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-xs btn-delete-option" @click="removeOptionSingle({'options': question.options, 'extensions': question.answer.extensions, 'index': index}); changeConditioningOptions({'question_id': question.id, 'options': question.options});" title="Elimina opzione"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- SCELTA MULTIPLA -->
                                                    <div v-if="question.type == 'multiple_choice'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Visibile in tabella</label>
                                                            <input type="checkbox" v-model="question.show_in_table" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_table" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per tabella" v-model="question.label_table" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Esportabile in excel</label>
                                                            <input type="checkbox" v-model="question.show_in_export" @change="updateShortLabels(question)"/> 
                                                            <input v-if="question.show_in_export" type="text" maxlength="64" class="form-control question-label-table" placeholder="Etichetta per excel" v-model="question.label_export" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Lista con scroll</label>
                                                            <input type="checkbox" v-model="question.scroll" /> 
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Opzione "Altro"</label>
                                                            <input type="checkbox" class="question-check-option" v-model="question.other" checked="question.other" />
                                                        </div>
                                                        <div class="col-md-12 div-option">
                                                            <label>Opzioni</label> <button type="button" class="btn btn-info btn-xs btn-add-option" @click="addOptionMultiple({'options': question.options, 'answer': question.answer})" title="Aggiungi opzione"><i class="fa fa-plus"></i></button>
                                                            <div v-for="(option, index) in question.options" class="div-option">
                                                                <div class="col-md-11 padding0">
                                                                    <input type="text" class="form-control" v-model="question.options[index].text" /> 
                                                                    <input type="checkbox" v-model="question.options[index].extended" /> Completamento manuale
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-xs btn-delete-option" @click="removeOptionMultiple({'options': question.options, 'answer': question.answer, 'index': index})" title="Elimina opzione"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- TABELLA -->
                                                    <div v-if="question.type == 'table'" class="col-md-12 padding0">
                                                        <b><i v-html="question.label"></i></b> <i v-show="question.conditioned" class="fa fa-link"></i>
                                                        <div class="col-md-12">
                                                            <label>Domanda</label>
                                                            <input type="text" class="form-control" v-model="question.question" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Tooltip</label>
                                                            <input type="text" class="form-control" v-model="question.tooltip" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Obbligatoria</label>
                                                            <input type="checkbox" v-model="question.required" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Domanda condizionata</label>
                                                            <input type="checkbox" v-model="question.conditioned" @click="showModalQuestions({'question': question, 'index': index, 'isInput': true}, $event)" /> 
                                                            <a v-if="question.conditioned" class="view-question-condition" @click="showModalQuestions({'question': question, 'index': index, 'isInput': false}, $event)">vedi condizioni</a>
                                                        </div>
                                                        <div class="col-md-12 div-option">
                                                            <label>Intestazioni</label> <button type="button" class="btn btn-info btn-xs btn-add-option" @click="addHeaderTable({'headers': question.headers})" title="Aggiungi intestazione"><i class="fa fa-plus"></i></button>
                                                            <div v-for="(header, index) in question.headers" class="div-option">
                                                                <div class="col-md-11 padding0">
                                                                    <input type="text" class="form-control" v-model="question.headers[index]" /> 
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-xs btn-delete-option" @click="removeHeaderTable({'headers': question.headers, 'index': index})" title="Elimina intestazione"><i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                        </div>
                                        <button type="button" class="btn btn-info btn-xs add-element" title="Aggiungi elemento" @click="showModalElements({'item': item, 'index': '', 'label':label, 'type': ''})"><i class="fa fa-plus"></i> Elemento</button>
                                    </div>
                                    <div v-show="item.open" class="children">
                                        <nested-draggable 
                                            :items="item.items"
                                            :parentitem="item"
                                            :label="label == undefined ? (index+1) : label+'.'+(index+1)"
                                            :elements="elements"
                                            :survey="survey"
                                            :editor="editor"
                                            :placeholders="placeholders"
                                            @add-item="$emit('add-item', $event)"
                                            @remove-item="$emit('remove-item', $event)"
                                        > 
                                        </nested-draggable>
                                    </div>
                                </div>
                            </draggable>
                        </script>

                        <nested-draggable 
                            :items="surveyData.items"
                            :parentitem="surveyData"
                            :elements="elements"
                            :survey="surveyData"
                            :editor="editorInit"
                            :placeholders="placeholders"
                            @add-item="addItem"
                            @remove-item="removeItem"
                        >
                        </nested-draggable >

                        <form id="tinymce_upload_form" enctype="multipart/form-data" class="form-editor-image-upload">      
                            <input hidden name="file" type="file" id="tinymce_upload" class=""/>
                        </form>
                    </div>
                </div>

                <div 
                    class="box-footer text-right" 
                    v-observe-visibility="{callback: checkInViewport}">
                    <a href="<?=Router::url('/surveys/surveys/index');?>" class="btn btn-default">Annulla</a>
                    <button class="btn btn-success save-survey-stay" @click="checkFormSurvey()">Salva</button>
                    <button class="btn btn-primary save-survey-exit" @click="checkFormSurvey(true)">Salva ed esci</button>
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
            <a href="<?=Router::url('/surveys/surveys/index');?>">
                <md-button class="md-fab fab-default" title="Annulla">
                    <md-icon>arrow_back</md-icon>
                </md-button>
            </a>

            <md-button class="md-fab fab-success save-survey-stay" @click="checkFormSurvey()" title="Salva">
                <md-icon><i class="glyphicon glyphicon-floppy-disk fab-icon"></i></md-icon>
            </md-button>

            <md-button class="md-fab fab-primary save-survey-exit" @click="checkFormSurvey(true)" title="Salva ed esci">
                <md-icon><i class="glyphicon glyphicon-floppy-remove fab-icon"></i></md-icon>
            </md-button>
        </md-speed-dial-content>
    </md-speed-dial>

    <?= $this->element('Surveys.modal_elements') ?>
    <?= $this->element('Surveys.modal_question_choice') ?>
    <?= $this->element('Surveys.modal_preview_question') ?>
    <?= $this->element('Surveys.modal_tooltip_question'); ?>
    <?= $this->element('Surveys.modal_data_sheet_options') ?>
    <?= $this->element('Surveys.modal_item_visibility') ?>

</div>