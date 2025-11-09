<?php
namespace Erick\QrEys\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configurarSMTP();
    }

    private function configurarSMTP() {
        try {
            // Configuración del servidor SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['MAIL_USERNAME'];
            $this->mailer->Password = $_ENV['MAIL_PASSWORD'];
            $this->mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $this->mailer->Port = $_ENV['MAIL_PORT'];

            // Configuración de remitente
            $this->mailer->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'],
                $_ENV['MAIL_FROM_NAME']
            );

            // Configuración de encoding
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->isHTML(true);

        } catch (Exception $e) {
            error_log("Error al configurar EmailService: " . $e->getMessage());
        }
    }

    /**
     * Envía un email de recuperación de contraseña
     *
     * @param string $destinatario Email del usuario
     * @param string $nombre Nombre del usuario
     * @param string $token Token de recuperación
     * @return bool
     */
    public function enviarEmailRecuperacion($destinatario, $nombre, $token) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = "Recuperación de Contraseña - Sistema Renlo";

            // Crear enlace de recuperación
            $enlaceRecuperacion = "http://" . $_SERVER['HTTP_HOST'] . "/qr_eys/public/restablecer-password?token=" . $token;

            // Crear cuerpo del email en HTML
            $html = $this->getPlantillaRecuperacion($nombre, $enlaceRecuperacion);

            $this->mailer->Body = $html;
            $this->mailer->AltBody = "Hola {$nombre},\n\nHas solicitado restablecer tu contraseña.\n\nHaz clic en el siguiente enlace para continuar:\n{$enlaceRecuperacion}\n\nEste enlace expirará en 1 hora.\n\nSi no solicitaste este cambio, ignora este correo.";

            $this->mailer->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar email de recuperación: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    /**
     * Plantilla HTML para email de recuperación
     */
    private function getPlantillaRecuperacion($nombre, $enlace) {
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                    background-color: #f5f5f5;
                    padding: 20px;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: white;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 40px 30px;
                    text-align: center;
                }
                .header h1 {
                    font-size: 28px;
                    margin-bottom: 10px;
                }
                .content {
                    padding: 40px 30px;
                    color: #333;
                }
                .content h2 {
                    color: #667eea;
                    margin-bottom: 20px;
                    font-size: 22px;
                }
                .content p {
                    line-height: 1.6;
                    margin-bottom: 20px;
                    font-size: 16px;
                }
                .btn-container {
                    text-align: center;
                    margin: 30px 0;
                }
                .btn-reset {
                    display: inline-block;
                    padding: 15px 40px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 50px;
                    font-weight: bold;
                    font-size: 16px;
                    transition: transform 0.2s;
                }
                .btn-reset:hover {
                    transform: translateY(-2px);
                }
                .info-box {
                    background-color: #fff3cd;
                    border-left: 4px solid #ffc107;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .info-box p {
                    margin: 0;
                    color: #856404;
                    font-size: 14px;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 20px 30px;
                    text-align: center;
                    color: #666;
                    font-size: 14px;
                }
                .footer p {
                    margin-bottom: 10px;
                }
                .divider {
                    height: 1px;
                    background-color: #e0e0e0;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <h1>🔐 Recuperación de Contraseña</h1>
                    <p>Sistema de Control de Accesos</p>
                </div>

                <div class='content'>
                    <h2>Hola, {$nombre}</h2>

                    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>

                    <p>Si fuiste tú quien solicitó este cambio, haz clic en el siguiente botón para crear una nueva contraseña:</p>

                    <div class='btn-container'>
                        <a href='{$enlace}' class='btn-reset'>Restablecer Contraseña</a>
                    </div>

                    <div class='info-box'>
                        <p><strong>⏱️ Este enlace expirará en 1 hora</strong></p>
                    </div>

                    <div class='divider'></div>

                    <p style='font-size: 14px; color: #666;'>
                        Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:
                    </p>
                    <p style='font-size: 12px; color: #999; word-break: break-all;'>
                        {$enlace}
                    </p>

                    <div class='divider'></div>

                    <p style='color: #d32f2f; font-size: 14px;'>
                        <strong>⚠️ Si NO solicitaste este cambio:</strong><br>
                        Ignora este correo. Tu contraseña permanecerá sin cambios y tu cuenta está segura.
                    </p>
                </div>

                <div class='footer'>
                    <p><strong>Sistema de Control de Accesos - Renlo</strong></p>
                    <p>Este es un mensaje automático, por favor no responder.</p>
                    <p style='margin-top: 10px; font-size: 12px;'>
                        &copy; " . date('Y') . " Renlo. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
