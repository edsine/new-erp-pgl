<!-- Title Field -->
<div class="form-group col-sm-12 mb-3">
    {!! Form::label('title', 'Name:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12 mb-3">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'required']) !!}
</div>


<!-- Document Url Field -->
<div class="form-group col-sm-6 mb-5">
    {!! Form::label('file', 'Upload any file:') !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('file', ['class' => ' form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Select Folder:') !!}
    {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
</div>

<!-- Meta Tag Field -->
<div class="form-group row">
    {!! Form::label('meta_tags', 'Meta Tag(s):', ['class' => 'col-form-label']) !!}
    <div class="col-sm-9">
        <div id="meta_tags_container">
            @foreach($single_metas as $single_meta)
            <input type="text" name="meta_tags[]" value="{{ $single_meta->name }}" class="form-control" placeholder="Enter Meta Tag">
            @endforeach
        </div>
    </div>
    <div class="col-sm-3">
        <button type="button" id="add_meta_tag" class="btn btn-primary"><i class="fa fa-plus"></i> Add Meta Tag</button>
    </div>
</div>

<div class="col-sm-12 row mb-3">
    <!-- Roles and users Field -->

<div class="form-group col-sm-6">
{!! Form::label('roles', 'Role(s):') !!}
{!! Form::select('roles[]', $roles, $single_role->pluck('role_id')->toArray(), ['class' => 'form-control', 'id' => 'roleSelect', 'multiple' => 'multiple']) !!}
</div>

<div class="form-group col-sm-6">
{!! Form::label('users', 'User(s):') !!}
{!! Form::select('users[]', $users, $single_doc->pluck('user_id')->toArray(), ['class' => 'form-control', 'id' => 'userSelect', 'multiple' => 'multiple']) !!}
</div>


</div>

<script>
    document.getElementById('add_meta_tag').addEventListener('click', function() {
        var container = document.getElementById('meta_tags_container');
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'meta_tags[]';
        input.className = 'form-control mt-2';
        input.placeholder = 'Enter Meta Tag';
        container.appendChild(input);
    });
</script>