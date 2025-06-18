@extends('layout')

@section('title', 'دردشة العملاء')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>اتصال واتساب</h5>
            </div>
            <div class="card-body text-center">
                <div id="qr-container" class="mb-3">
                    <video id="qr-preview" width="250" height="250" class="img-fluid"></video>
                    <p id="qr-status" class="mt-2">جاري التهيئة...</p>
                </div>
                <button id="refresh-qr" class="btn btn-secondary">تحديث QR</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>المحادثات</h5>
            </div>
            <div class="card-body">
                <div class="chat-container" style="height: 500px; overflow-y: auto;">
                    <!-- سيتم ملء المحادثات عبر JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/qr-scan.js') }}"></script>
@endpush
@endsection