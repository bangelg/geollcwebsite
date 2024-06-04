// script.js
document.addEventListener('DOMContentLoaded', function() {
    const html5QrCode = new Html5Qrcode("reader");
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        // Handle on success condition with the decoded text or result.
        window.location.href = decodedText, decodedResult;
    };

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    document.getElementById('start-scan').addEventListener('click', () => {
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            qrCodeSuccessCallback
        ).catch(err => {
            console.log(`Unable to start scanning, error: ${err}`);
        });
    });

    document.getElementById('stop-scan').addEventListener('click', () => {
        html5QrCode.stop().then(ignore => {
            console.log('Scanning stopped.');
        }).catch(err => {
            console.log(`Unable to stop scanning, error: ${err}`);
        });
    });
});
