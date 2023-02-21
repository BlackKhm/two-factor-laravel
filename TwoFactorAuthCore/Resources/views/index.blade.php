@extends(backpack_view('layouts.top_left'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box-box bg-white p-3 border-top border-primary mb-3">
                @include('fsc-2fa::indexes.html')
            </div>
         </div>
    </div>
@endsection

@push('after_styles')
    @stack('crud_fields_styles')
@endpush
@push('after_scripts')
    @stack('crud_fields_scripts')
    @include('fsc-2fa::indexes.script')
@endpush
                    