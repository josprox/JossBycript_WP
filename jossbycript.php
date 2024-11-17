<?php
/**
 * Plugin Name: Bcrypt Password Enforcer
 * Description: Reemplaza completamente el manejo de contraseñas de WordPress para usar bcrypt.
 * Version: 2.0
 * Author: JOSPROX MX - Melchor Estrada José Luis
 */

// Reemplazar funciones predeterminadas de WordPress relacionadas con contraseñas.
require_once ABSPATH . WPINC . '/pluggable.php';

/**
 * Hash de contraseña usando bcrypt.
 *
 * @param string $password Contraseña en texto plano.
 * @return string Hash bcrypt.
 */
function wp_hash_password($password) {
    $options = ['cost' => 10];
    return password_hash($password, PASSWORD_BCRYPT, $options);
}

/**
 * Verifica la contraseña proporcionada contra el hash almacenado.
 *
 * @param string $password Contraseña en texto plano.
 * @param string $hash Hash almacenado en la base de datos.
 * @return bool True si coincide, false en caso contrario.
 */
function wp_check_password($password, $hash) {
    // Si el hash es bcrypt.
    if (password_verify($password, $hash)) {
        return true;
    }

    // Si el hash es PHpass, valida y migra.
    if (is_string($hash) && substr($hash, 0, 3) === '$P$' && wp_check_password_phpass($password, $hash)) {
        return true;
    }

    return false;
}

/**
 * Valida contraseñas usando el método PHpass original.
 *
 * @param string $password Contraseña en texto plano.
 * @param string $hash Hash PHpass almacenado.
 * @return bool True si coincide, false en caso contrario.
 */
function wp_check_password_phpass($password, $hash) {
    global $wp_hasher;

    if (empty($wp_hasher)) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash(8, true);
    }

    return $wp_hasher->CheckPassword($password, $hash);
}

/**
 * Migrar contraseñas al formato bcrypt después de la validación exitosa.
 *
 * @param string $password Contraseña en texto plano.
 * @param string $hash Hash almacenado.
 * @param int    $user_id ID del usuario.
 */
add_filter('check_password', function ($check, $password, $hash, $user_id) {
    if ($check && substr($hash, 0, 3) === '$P$') {
        wp_set_password($password, $user_id); // Migrar a bcrypt.
    }
    return $check;
}, 10, 4);
