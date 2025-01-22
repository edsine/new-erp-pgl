@if(session()->has('success'))
<div class="alert alert-success" style="color: green; font-weight:bold">
{{ session()->get('success') }}
</div>
@endif
@if(session()->has('error'))
<div class="alert alert-error" style="color: red; font-weight:bold">
{{ session()->get('error') }}
</div>
@endif
