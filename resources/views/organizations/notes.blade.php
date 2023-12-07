{{ Form::model($organization, array('route' => array('organization.notes.store', $organization->id), 'method' => 'POST', 'id' => 'create-notes' ,'style' => 'z-index: 9999999 !important;')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group py-1">
            <label for="">Title</label>
            <input type="text" class="form form-control" name="title" value="" required>
        </div>

        <div class="col-12 form-group py-1">
            <label for="">Description</label>
            <textarea name="description" class="form form-control" cols="10" rows="10"></textarea>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="Create" class="btn  btn-primary create-notes">
</div>
{{Form::close()}}