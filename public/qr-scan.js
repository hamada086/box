// QR Scanner for WhatsApp Integration
document.addEventListener('DOMContentLoaded', function() {
    const qrContainer = document.getElementById('qr-container');
    const statusText = document.getElementById('qr-status');
    const wsClient = new WebSocket('ws://localhost:6001'); // Laravel Echo Server

    // Initialize QR Scanner
    function initQRScanner() {
        const scanner = new Instascan.Scanner({
            video: document.getElementById('qr-preview'),
            mirror: false
        });

        scanner.addListener('scan', function(content) {
            statusText.textContent = 'تم مسح الكود بنجاح!';
            connectToWhatsApp(content); // المحتوى هو رمز QR
        });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                statusText.textContent = 'لا يوجد كاميرا متاحة';
            }
        }).catch(function(e) {
            console.error(e);
            statusText.textContent = 'خطأ في الكاميرا: ' + e.message;
        });
    }

    // Connect to WhatsApp Web
    function connectToWhatsApp(qrCode) {
        fetch('/api/whatsapp/connect', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusText.textContent = 'تم الاتصال بواتساب بنجاح';
                wsClient.send(JSON.stringify({
                    event: 'whatsapp.connected',
                    user_id: CURRENT_USER_ID // تعريف هذا المتغير في blade
                }));
            }
        });
    }

    // Initialize when page loads
    if (qrContainer) {
        initQRScanner();
    }

    // Handle WebSocket messages
    wsClient.onmessage = function(e) {
        const data = JSON.parse(e.data);
        if (data.event === 'whatsapp.ready') {
            statusText.textContent = 'جاهز للاستقبال والإرسال';
        }
    };
});