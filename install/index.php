<?php
define('INSTALL_LOCK', dirname(__DIR__) . '/storage/framework/install.lock');

// التحقق من وجود قفل التثبيت
if (file_exists(INSTALL_LOCK)) {
    header('Location: /');
    exit;
}

// فحص المتطلبات
$requirements = [
    'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
    'Laravel Requirements' => extension_loaded('openssl')
        && extension_loaded('pdo')
        && extension_loaded('mbstring')
        && extension_loaded('tokenizer')
        && extension_loaded('ctype')
        && extension_loaded('json'),
    'Storage Writable' => is_writable(dirname(__DIR__) . '/storage'),
    'Env File Writable' => is_writable(dirname(__DIR__) . '/.env'),
];

// معالجة نموذج التثبيت
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // التحقق من صحة البيانات
        $dbHost = $_POST['db_host'] ?? '127.0.0.1';
        $dbPort = $_POST['db_port'] ?? '3306';
        $dbName = $_POST['db_name'];
        $dbUser = $_POST['db_user'];
        $dbPass = $_POST['db_pass'];
        
        $adminName = $_POST['admin_name'];
        $adminEmail = $_POST['admin_email'];
        $adminPassword = $_POST['admin_password'];
        $companyName = $_POST['company_name'];
        
        // اختبار اتصال قاعدة البيانات
        $dsn = "mysql:host=$dbHost;port=$dbPort;charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, $dbUser, $dbPass);
            $pdo->query("CREATE DATABASE IF NOT EXISTS `$dbName`");
        } catch (PDOException $e) {
            throw new Exception("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
        
        // إنشاء ملف .env
        $envContent = file_get_contents(dirname(__DIR__) . '/.env.example');
        $envContent = str_replace([
            'DB_HOST=127.0.0.1',
            'DB_PORT=3306',
            'DB_DATABASE=laravel',
            'DB_USERNAME=root',
            'DB_PASSWORD=',
            'APP_NAME=Laravel',
        ], [
            "DB_HOST=$dbHost",
            "DB_PORT=$dbPort",
            "DB_DATABASE=$dbName",
            "DB_USERNAME=$dbUser",
            "DB_PASSWORD=$dbPass",
            "APP_NAME=\"$companyName\"",
        ], $envContent);
        
        file_put_contents(dirname(__DIR__) . '/.env', $envContent);
        
        // تشغيل التهيئة
        require dirname(__DIR__) . '/vendor/autoload.php';
        $app = require_once dirname(__DIR__) . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        // تنفيذ أوامر التهيئة
        $kernel->call('migrate', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
        
        // إنشاء مستخدم المدير
        $kernel->call('app:create-admin', [
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => $adminPassword
        ]);
        
        // إنشاء قفل التثبيت
        file_put_contents(INSTALL_LOCK, 'System installed at ' . date('Y-m-d H:i:s'));
        
        // حذف مجلد التثبيت
        function deleteInstallDir() {
            $dir = __DIR__;
            array_map('unlink', glob("$dir/*.*"));
            rmdir($dir);
        }
        register_shutdown_function('deleteInstallDir');
        
        // إعادة التوجيه بعد التثبيت
        header('Location: /login');
        exit;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// واجهة التثبيت
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت Box CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        .requirement-pass { color: #28a745; }
        .requirement-fail { color: #dc3545; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>مرحباً بك في تثبيت Box CRM</h3>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <!-- خطوة 1: فحص المتطلبات -->
                        <div class="mb-4">
                            <h5 class="mb-3">1. فحص متطلبات النظام</h5>
                            <ul class="list-group">
                                <?php foreach ($requirements as $label => $passed): ?>
                                    <li class="list-group-item">
                                        <?= $label ?>
                                        <span class="float-start <?= $passed ? 'requirement-pass' : 'requirement-fail' ?>">
                                            <?= $passed ? '✓' : '✗' ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <!-- خطوة 2: إعداد قاعدة البيانات -->
                        <form method="POST" id="install-form">
                            <h5 class="mb-3">2. إعداد قاعدة البيانات</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="db_host" class="form-label">خادم قاعدة البيانات</label>
                                    <input type="text" class="form-control" id="db_host" name="db_host" value="127.0.0.1" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="db_port" class="form-label">المنفذ</label>
                                    <input type="text" class="form-control" id="db_port" name="db_port" value="3306" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="db_name" class="form-label">اسم قاعدة البيانات</label>
                                    <input type="text" class="form-control" id="db_name" name="db_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="db_user" class="form-label">اسم المستخدم</label>
                                    <input type="text" class="form-control" id="db_user" name="db_user" required>
                                </div>
                                <div class="col-12">
                                    <label for="db_pass" class="form-label">كلمة المرور</label>
                                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                                </div>
                            </div>
                            
                            <!-- خطوة 3: إعداد المدير -->
                            <h5 class="mb-3">3. إنشاء حساب المدير</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="admin_name" class="form-label">الاسم الكامل</label>
                                    <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="admin_email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="admin_password" class="form-label">كلمة المرور</label>
                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="admin_password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
                                </div>
                            </div>
                            
                            <!-- خطوة 4: إعداد الشركة -->
                            <h5 class="mb-3">4. معلومات الشركة</h5>
                            <div class="mb-4">
                                <label for="company_name" class="form-label">اسم الشركة</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">بدء التثبيت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // التحقق من تطابق كلمات المرور
        document.getElementById('install-form').addEventListener('submit', function(e) {
            const pass = document.getElementById('admin_password');
            const passConfirm = document.getElementById('admin_password_confirmation');
            
            if (pass.value !== passConfirm.value) {
                e.preventDefault();
                alert('كلمتا المرور غير متطابقتين');
                passConfirm.focus();
            }
        });
    </script>
</body>
</html>