<!-- Title Field -->
<div class="form-group col-sm-12 mb-3">
    {!! Form::label('title', 'Name:') !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12 mb-3">
    {!! Form::label('description', 'Comment:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'required']) !!}
</div>


<!-- Document Url Field -->
<div class="form-group col-sm-6 mb-5">
    {!! Form::label('file', 'Upload any file:') !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('file', ['class' => ' form-control', 'required']) !!}
        </div>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Select File:') !!}
    {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
</div>
<!-- Meta Tag Field -->
<div class="form-group row">
    {!! Form::label('meta_tags', 'Meta Tag(s):', ['class' => 'col-form-label']) !!}
    <div class="col-sm-4">
        <div id="meta_tags_container">
            <input type="text" name="meta_tags[]" class="form-control" placeholder="Enter Meta Tag">
        </div>
    </div>
    <div class="col-sm-3">
        <button type="button" id="add_meta_tag" class="btn btn-primary"><i class="fa fa-plus"></i> Add Meta
            Tag</button>
    </div>
</div>

{{-- <div class="row">
    <div class="form-group col-sm-6">
        {!! Form::label('area_offices', 'Area Office(s):') !!}
        {!! Form::select('branch_id[]', $office->pluck('branch_name', 'id')->prepend('All', ''), null, [
            'class' => 'form-control',

            'multiple' => 'multiple',
            'id' => 'theareaoffice',
        ]) !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('department', 'Select Departments):') !!}
        {!! Form::select('department_id[]', $dept->pluck('name', 'id')->prepend('All', ''), null, [
            'class' => 'form-control',

            'multiple' => 'multiple',
            'id' => 'thedepartment',
        ]) !!}
    </div>
</div> --}}

{{-- <div class="col-sm-12 row mb-3">
    <!-- Roles and users Field -->

    <div class="form-group col-sm-6">
        {!! Form::label('roles', 'Role(s):') !!}
        {!! Form::select('roles[]', $roles, null, [
            'class' => 'form-control',
            'multiple' => 'multiple',
            'id' => 'therole',
        ]) !!}
    </div>

    <div id="userrow" class="form-group col-sm-6">
        {!! Form::label('users', 'User(s):') !!}
        {!! Form::select('users[]', [], null, [
            'class' => 'form-control',

            'multiple' => 'multiple',
            'id' => 'theuser',
        ]) !!}
    </div>


</div> --}}

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $therole = $('#therole');
    $department = $('#thedepartment');
    $office = $('#theareaoffice');
    $usersrow=$('#userrow');
    $department.select2();
    $office.select2();
    $therole.select2();
    $theusers = $('#theuser');
    $theusers.select2();

    // if($office.val()==''&& $department.val()=='' ){

    //     $usersrow.hide();
    // } else{
    //     $usersrow.show();
    // }
    $department.change(function() {
        var deptid = $(this).val();


        // i want to get the branchid now,
        var branchid = $('#theareaoffice').val();

        $.ajax({
            type: "GET",

            url: "/thedocumentuser/",
            dataType: "json",
            data: {
                branchid: branchid,
                deptid: deptid
            },
            success: function(users) {
                console.log(branchid);
                console.log(users);
                $theusers.empty();
                $.each(users, function(index, user) {
                    $theusers.append('<option value="' + user.id + ' ">' + user.name + '</option> ');
                });
                $theusers.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    })
</script>

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
