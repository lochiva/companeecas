<div class="modal fade" id="modalBackground" tabindex="-1" role="dialog" aria-labelledby="modalbackgroundLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Aggiungi sfondo</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="backgroundUpload">
                    <div class="form-group">
                        <label for="backgroundImage">Immagine di sfondo</label>
                        <input type="file" name="background_image" id="backgroundImage" /><br />
                        <span style="color:red;">(dimensioni minime dell'immagine: 1440 x 900)</span>
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button disabled="disabled" type="button" class="btn btn-primary add-background" title="Selezionare un'immagine per poter salvare">Salva</button>
            </div>
        </div>
    </div>
</div>