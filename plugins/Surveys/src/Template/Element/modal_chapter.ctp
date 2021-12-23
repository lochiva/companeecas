<div class="modal fade" id="modalChapter" tabindex="-1" role="dialog" aria-labelledby="modalChapterLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title title-inline">Capitolo</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formChapter" class="form-horizontal">
            <input hidden id="chapterId" name="id" value="">
            <div class="form-group">
                <div class="col-md-9 input">
                    <label class="col-sm-2 control-label required no-padding-left" for="chapterName">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" id="chapterName" maxlength="255" name="name" value="" class="form-control required" />
                    </div>
                </div>            
                <div class="col-md-3 input">
                    <label class="col-sm-6 control-label required no-padding-left" for="chapterOrdering">Ordine</label>
                    <div class="col-sm-6 no-padding-right">
                        <input type="text" id="chapterOrdering" name="ordering" value="" class="form-control number-integer" />
                    </div>
                </div>
            </div>  
            <div class="form-group"> 
                <div class="col-sm-12">
                    <textarea id="chapterContent" name="content" class="editor-html"></textarea>
                </div>
            </div>      
        </form>
        <ul class="list-placeholders">
        <?php foreach($placeholders as $placeholder){ ?>
            <li><?= $placeholder['label'] ?> - <?= $placeholder['description'] ?></li>
        <?php } ?>
        </ul>
        <form id="tinymce_upload_form" enctype="multipart/form-data" class="form-editor-image-upload">    
            <input hidden name="survey" value="0"/>  
            <input hidden name="file" type="file" id="tinymce_upload" class=""/>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary" id="saveChapter">Salva</button>
      </div>
    </div>
  </div>
</div>