<?php
/**
 * Clase de validación de datos
 */
class Validator {

    /**
     * Valida un email
     * @param string $email Email a validar
     * @return bool True si es válido
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valida un número de teléfono
     * Acepta formatos: 1234567890, 123-456-7890, (123) 456-7890, +1 123 456 7890
     * @param string $phone Teléfono a validar
     * @return bool True si es válido
     */
    public static function validatePhone($phone) {
        // Remover espacios, guiones, paréntesis y signos +
        $cleanPhone = preg_replace('/[\s\-\(\)\+]/', '', $phone);

        // Verificar que tenga entre 7 y 15 dígitos
        return preg_match('/^\d{7,15}$/', $cleanPhone);
    }

    /**
     * Valida que una cadena no esté vacía
     * @param string $value Valor a validar
     * @return bool True si no está vacía
     */
    public static function required($value) {
        return !empty(trim($value));
    }

    /**
     * Valida la longitud mínima de una cadena
     * @param string $value Valor a validar
     * @param int $min Longitud mínima
     * @return bool True si cumple la longitud mínima
     */
    public static function minLength($value, $min) {
        return strlen(trim($value)) >= $min;
    }

    /**
     * Valida la longitud máxima de una cadena
     * @param string $value Valor a validar
     * @param int $max Longitud máxima
     * @return bool True si cumple la longitud máxima
     */
    public static function maxLength($value, $max) {
        return strlen(trim($value)) <= $max;
    }

    /**
     * Sanitiza una cadena removiendo caracteres peligrosos
     * @param string $value Valor a sanitizar
     * @return string Valor sanitizado
     */
    public static function sanitizeString($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida múltiples campos y retorna errores
     * @param array $rules Reglas de validación
     * @param array $data Datos a validar
     * @return array Array de errores (vacío si todo es válido)
     */
    public static function validate($rules, $data) {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';

            foreach ($fieldRules as $rule => $param) {
                switch ($rule) {
                    case 'required':
                        if (!self::required($value)) {
                            $errors[$field][] = "El campo $field es obligatorio";
                        }
                        break;

                    case 'email':
                        if (!empty($value) && !self::validateEmail($value)) {
                            $errors[$field][] = "El campo $field debe ser un email válido";
                        }
                        break;

                    case 'phone':
                        if (!empty($value) && !self::validatePhone($value)) {
                            $errors[$field][] = "El campo $field debe ser un teléfono válido";
                        }
                        break;

                    case 'min':
                        if (!self::minLength($value, $param)) {
                            $errors[$field][] = "El campo $field debe tener al menos $param caracteres";
                        }
                        break;

                    case 'max':
                        if (!self::maxLength($value, $param)) {
                            $errors[$field][] = "El campo $field debe tener máximo $param caracteres";
                        }
                        break;
                }
            }
        }

        return $errors;
    }
}
