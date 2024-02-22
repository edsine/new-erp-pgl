{{ Form::open(['url' => 'leads']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'), ['class' => 'form-label']) }}
            {{ Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('user_id', __('User'), ['class' => 'form-label']) }}
            {{ Form::select('user_id', $users, null, ['class' => 'form-control select', 'required' => 'required']) }}
            @if (count($users) == 1)
                <div class="text-muted text-xs">
                    {{ __('Please create new users') }} <a href="{{ route('users.index') }}">{{ __('here') }}</a>.
                </div>
            @endif
        </div>
        <div class="col-6 form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            {{ Form::text('email', null, ['class' => 'form-control']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
            {{ Form::text('phone', null, ['class' => 'form-control']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('lot_description', __('Lot Description'), ['class' => 'form-label']) }}
            {{ Form::text('lot_description', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            {{ Form::text('status', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('amount_bidded', __('Amount Bidded'), ['class' => 'form-label']) }}
            {{ Form::number('amount_bidded', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('date_of_submission', __('Date of Submission'), ['class' => 'form-label']) }}
            {{ Form::date('date_of_submission', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        {{-- <div class="col-6 form-group">
            {{ Form::label('email_address', __('Email Address'), ['class' => 'form-label']) }}
            {{ Form::text('email_address', null, ['class' => 'form-control']) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone_number', __('Phone number'), ['class' => 'form-label']) }}
            {{ Form::text('phone_number', null, ['class' => 'form-control']) }}
        </div> --}}
        <div class="col-6 form-group">
            {{ Form::label('company', __('Company'), ['class' => 'form-label']) }}
            {{ Form::text('company', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>

{{ Form::close() }}
