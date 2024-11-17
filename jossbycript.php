<?php
/**
 * Plugin Name: Bcrypt Password Enforcer
 * Description: Implementa bcrypt para contraseñas en WordPress y migra automáticamente contraseñas antiguas.
 * Version: 2.1
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
 * Verifica la contraseña y migra automáticamente a bcrypt si es válida.
 *
 * @param string $password Contraseña en texto plano.
 * @param string $hash Hash almacenado en la base de datos.
 * @param int    $user_id ID del usuario actual.
 * @return bool True si la contraseña es válida, false en caso contrario.
 */
function wp_check_password($password, $hash, $user_id = null) {
    // Si el hash es bcrypt, validar directamente.
    if (password_verify($password, $hash)) {
        return true;
    }

    // Si el hash es PHpass, validar y migrar.
    if (is_string($hash) && substr($hash, 0, 3) === '$P$' && wp_check_password_phpass($password, $hash)) {
        if ($user_id) {
            // Migrar el hash a bcrypt.
            $new_hash = wp_hash_password($password);

            // Actualizar el hash en la base de datos.
            global $wpdb;
            $wpdb->update(
                $wpdb->users,
                ['user_pass' => $new_hash],
                ['ID' => $user_id],
                ['%s'],
                ['%d']
            );
        }
        return true;
    }

    return false;
}

/**
 * Enlazar la función de validación al filtro `check_password`.
 */
add_filter('check_password', function ($check, $password, $hash, $user_id) {
    return wp_check_password($password, $hash, $user_id);
}, 10, 4);