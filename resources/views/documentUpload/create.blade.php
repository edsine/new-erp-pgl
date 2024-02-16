{{Form::open(array('url'=>'document-upload','method'=>'post', 'enctype' => "multipart/form-data"))}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('name',__('Title'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('role',__('Role'),['class'=>'form-label'])}}
                {{Form::select('role',$roles,null,array('class'=>'form-control select', ))}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('user_id',__('Users'),['class'=>'form-label'])}}
                {{Form::select('user_id',$users,null,array('class'=>'form-control select', ))}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('department_id',__('Departments'),['class'=>'form-label'])}}
                {{Form::select('department_id',$departments,null,array('class'=>'form-control select', ))}}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'form-label'])}}
                {{ Form::textarea('description',null, array('class' => 'form-control' ,'rows'=> 3)) }}
            </div>
        </div>

        <div class="col-md-6 form-group">
            {{Form::label('document',__('Document'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="document" class="form-label">
                    <input type="file" class="form-control" name="document" id="document" data-filename="document_create" required>
                    <img id="image" class="mt-3" style="width:25%;"/>
                </label>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">

    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}


<script>
    document.getElementById('document').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>

