<?php
/**
 * Utilidades para manejo seguro de archivos
 */
class FileHelper {

    /**
     * Sanitiza un nombre de archivo para prevenir path traversal y caracteres peligrosos
     * @param string $filename Nombre de archivo a sanitizar
     * @param string $extension Extensión permitida (opcional)
     * @return string Nombre de archivo sanitizado
     */
    public static function sanitizeFilename($filename, $extension = '') {
        // Remover cualquier ruta (path traversal)
        $filename = basename($filename);

        // Remover caracteres peligrosos, mantener solo alfanuméricos, guiones, espacios y puntos
        $filename = preg_replace('/[^a-zA-Z0-9\s\-_\.]/', '_', $filename);

        // Remover múltiples puntos consecutivos (prevenir ../ attacks)
        $filename = preg_replace('/\.{2,}/', '_', $filename);

        // Remover espacios al inicio y final
        $filename = trim($filename);

        // Limitar longitud del nombre
        if (strlen($filename) > 200) {
            $filename = substr($filename, 0, 200);
        }

        // Si se especificó una extensión, asegurar que el archivo la tenga
        if ($extension) {
            $extension = ltrim($extension, '.');
            $filename = pathinfo($filename, PATHINFO_FILENAME) . '.' . $extension;
        }

        return $filename;
    }

    /**
     * Valida que un path no contenga componentes de path traversal
     * @param string $path Path a validar
     * @return bool True si es seguro, False si contiene path traversal
     */
    public static function isPathSafe($path) {
        // Normalizar el path
        $realPath = realpath($path);

        // Si realpath retorna false, el archivo no existe o el path es inválido
        if ($realPath === false) {
            // Verificar manualmente si contiene patrones peligrosos
            if (preg_match('/\.\./', $path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica que un archivo esté dentro de un directorio base permitido
     * @param string $filePath Ruta completa del archivo
     * @param string $baseDir Directorio base permitido
     * @return bool True si el archivo está dentro del directorio base
     */
    public static function isWithinDirectory($filePath, $baseDir) {
        $realFilePath = realpath($filePath);
        $realBaseDir = realpath($baseDir);

        // Si alguno no existe, verificar el path sin resolver
        if ($realFilePath === false || $realBaseDir === false) {
            // Normalizar paths
            $filePath = str_replace('\\', '/', $filePath);
            $baseDir = str_replace('\\', '/', $baseDir);

            return strpos($filePath, $baseDir) === 0;
        }

        return strpos($realFilePath, $realBaseDir) === 0;
    }
}
