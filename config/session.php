<?php
/**
 * Configuración segura de sesiones
 * Este archivo debe ser incluido antes de cualquier session_start()
 */

// Configuración de cookies de sesión seguras
ini_set('session.cookie_httponly', '1');  // Previene acceso a cookies vía JavaScript (XSS)
ini_set('session.use_only_cookies', '1'); // Solo usar cookies, no permitir ID en URL
ini_set('session.cookie_samesite', 'Strict'); // Protección CSRF adicional

// Solo habilitar cookie_secure si el sitio usa HTTPS
// Descomenta la siguiente línea cuando tu sitio esté en HTTPS
// ini_set('session.cookie_secure', '1');

// Modo estricto: rechaza IDs de sesión no inicializados por el servidor
ini_set('session.use_strict_mode', '1');

// Usar hash fuerte para IDs de sesión
ini_set('session.sid_length', '48');
ini_set('session.sid_bits_per_character', '6');

// Configuración de garbage collection (limpieza de sesiones)
ini_set('session.gc_maxlifetime', '1800'); // 30 minutos
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '100');

// Nombre de sesión personalizado (más difícil de identificar el stack tecnológico)
ini_set('session.name', 'RENLO_SID');

// Prevenir que el navegador cache las páginas con sesión
session_cache_limiter('nocache');
session_cache_expire(30); // minutos
