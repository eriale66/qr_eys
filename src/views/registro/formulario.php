<?php
require_once __DIR__ . '/../../utils/CSRF.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffffff 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo {
            max-width: 180px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1f3c88;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 28px;
        }

        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 14px;
            font-weight: 300;
        }

        .mode-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            background: #f5f6fa;
            padding: 5px;
            border-radius: 12px;
        }

        .mode-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: #7f8c8d;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mode-btn.active {
            background: #667eea;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .mode-btn:hover:not(.active) {
            background: #e8e9f3;
        }

        .scan-mode {
            display: none;
        }

        .scan-mode.active {
            display: block;
        }

        form {
            margin-top: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 18px;
            font-size: 18px;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        #video-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
        }

        .camera-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid #667eea;
            border-radius: 12px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
        }

        .camera-info {
            margin-top: 20px;
            padding: 15px;
            background: #f0f4ff;
            border-radius: 10px;
            color: #1f3c88;
            font-size: 14px;
        }

        .camera-info.success {
            background: #d4edda;
            color: #155724;
        }

        .camera-info.error {
            background: #f8d7da;
            color: #721c24;
        }

        .start-camera-btn {
            width: 100%;
            padding: 18px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .start-camera-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .start-camera-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 24px;
            }

            .logo {
                max-width: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="/qr_eys/public/img/logo.jpg" alt="Logo" class="logo">
        </div>

        <h2>Registro de Acceso</h2>
        <p class="subtitle">Seleccione un método para escanear su código QR</p>

        <div class="mode-toggle">
            <button type="button" class="mode-btn active" onclick="switchMode('manual')">
                Escaneo Manual
            </button>
            <button type="button" class="mode-btn" onclick="switchMode('camera')">
                Escaneo por Cámara
            </button>
        </div>

        <!-- Modo Manual -->
        <div id="manual-mode" class="scan-mode active">
            <form method="POST" action="/qr_eys/public/registrar-acceso">
                <?= CSRF::inputField() ?>
                <input type="text" id="manual-input" name="codigo" autofocus autocomplete="off" placeholder="Coloque el cursor aquí y escanee">
            </form>
        </div>

        <!-- Modo Cámara -->
        <div id="camera-mode" class="scan-mode">
            <button type="button" class="start-camera-btn" id="start-camera" onclick="startCamera()">
                Iniciar Cámara
            </button>
            <div id="video-container" style="display: none;">
                <video id="video" playsinline></video>
                <div class="camera-overlay"></div>
            </div>
            <div id="camera-info" class="camera-info" style="display: none;">
                Apunte la cámara hacia el código QR
            </div>
            <form method="POST" action="/qr_eys/public/registrar-acceso" id="camera-form" style="display: none;">
                <?= CSRF::inputField() ?>
                <input type="hidden" name="codigo" id="camera-input">
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        let codeReader = null;
        let currentStream = null;

        function switchMode(mode) {
            // Actualizar botones
            document.querySelectorAll('.mode-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Mostrar/ocultar modos
            document.querySelectorAll('.scan-mode').forEach(section => {
                section.classList.remove('active');
            });

            if (mode === 'manual') {
                document.getElementById('manual-mode').classList.add('active');
                document.getElementById('manual-input').focus();
                stopCamera();
            } else {
                document.getElementById('camera-mode').classList.add('active');
            }
        }

        async function startCamera() {
            try {
                const videoElement = document.getElementById('video');
                const videoContainer = document.getElementById('video-container');
                const startBtn = document.getElementById('start-camera');
                const cameraInfo = document.getElementById('camera-info');

                // Ocultar botón y mostrar video
                startBtn.style.display = 'none';
                videoContainer.style.display = 'block';
                cameraInfo.style.display = 'block';
                cameraInfo.className = 'camera-info';
                cameraInfo.textContent = 'Apunte la cámara hacia el código QR';

                // Inicializar el lector de códigos QR
                codeReader = new ZXing.BrowserQRCodeReader();

                // Obtener dispositivos de video
                const videoInputDevices = await codeReader.listVideoInputDevices();

                if (videoInputDevices.length === 0) {
                    throw new Error('No se encontró ninguna cámara');
                }

                // Preferir cámara trasera en dispositivos móviles
                let selectedDeviceId = videoInputDevices[0].deviceId;
                for (const device of videoInputDevices) {
                    if (device.label.toLowerCase().includes('back') ||
                        device.label.toLowerCase().includes('trasera')) {
                        selectedDeviceId = device.deviceId;
                        break;
                    }
                }

                // Iniciar decodificación
                codeReader.decodeFromVideoDevice(selectedDeviceId, videoElement, (result, err) => {
                    if (result) {
                        // Código QR detectado
                        cameraInfo.className = 'camera-info success';
                        cameraInfo.textContent = '✓ Código QR detectado correctamente';

                        // Establecer el código en el formulario oculto
                        document.getElementById('camera-input').value = result.text;

                        // Enviar el formulario
                        setTimeout(() => {
                            document.getElementById('camera-form').submit();
                        }, 500);
                    }

                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error(err);
                    }
                });

            } catch (error) {
                console.error('Error al iniciar la cámara:', error);
                const cameraInfo = document.getElementById('camera-info');
                cameraInfo.style.display = 'block';
                cameraInfo.className = 'camera-info error';
                cameraInfo.textContent = '✗ Error: ' + error.message;

                document.getElementById('start-camera').style.display = 'block';
                document.getElementById('video-container').style.display = 'none';
            }
        }

        function stopCamera() {
            if (codeReader) {
                codeReader.reset();
                codeReader = null;
            }

            document.getElementById('video-container').style.display = 'none';
            document.getElementById('start-camera').style.display = 'block';
            document.getElementById('camera-info').style.display = 'none';
        }

        // Limpiar recursos al cerrar la página
        window.addEventListener('beforeunload', stopCamera);
    </script>
</body>

</html>