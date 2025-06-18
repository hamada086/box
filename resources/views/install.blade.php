<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تثبيت Box CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center">مرحبا بك في تثبيت Box CRM</h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>ملاحظة:</strong> سيتم إنشاء ملف .env تلقائياً
                        </div>
                        
                        <form id="install-form" method="POST" action="{{ url('install/process') }}">
                            @csrf
                            
                            <!-- خطوات التثبيت ستضاف هنا -->
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">بدء التثبيت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>