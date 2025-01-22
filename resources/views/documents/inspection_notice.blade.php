@extends('layouts.app')

@section('title', 'Sub-Service')


@push('styles')
@endpush


@section('content')

    {{-- <div class="components-preview wide-md mx-auto"> --}}
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Inspection Notice</h3>
                <div class="nk-block-des text-soft">
                    <p>Send Inspection Notice To {{ $employer->contact_firstname.' '.$employer->contact_surname }}</p>
                </div>
            </div><!-- .nk-block-head-content -->
            <!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="nk-block nk-block-lg">
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <form action="{{ route('inspection.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="preview-block">
                        
                        <div class="row gy-4">
                            
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    
                                    <div class="form-control-wrap">
                                        <div class="form-file">
                                            <label class="form-file-label" for="inspection_datetime">Inspection Date & Time</label>
                                            <input type="hidden" name="employer_id" value="{{ $id }}"/>
                                            <input type="datetime-local" class="form-control" name="inspection_datetime" value="" />
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-group">
                                    
                                    <div class="form-control-wrap">
                                        <div class="form-file">
                                            <label class="form-file-label" for="message">Message</label>
                                            <textarea class="form-control" name="message" ></textarea>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                <br/>
                            </div>

                           
                           
                        </div>
                    
                        
                        <div class="row g-4">
                            <div class="col-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block"><em
                                            class="icon ni ni-save me-2"></em> SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div><!-- .card-preview -->
    </div>

@endsection

