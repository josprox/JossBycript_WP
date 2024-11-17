# Bcrypt Password Enforcer for WordPress

## Descripción

**Bcrypt Password Enforcer** es un plugin para WordPress diseñado para mejorar la seguridad de las contraseñas de los usuarios mediante el uso del algoritmo de cifrado bcrypt. Este plugin reemplaza el mecanismo de cifrado predeterminado de WordPress (PHpass) con bcrypt y migra automáticamente las contraseñas antiguas al nuevo formato durante el inicio de sesión.

## Características

- **Compatibilidad con contraseñas existentes**: Las contraseñas almacenadas con el cifrado PHpass se validan correctamente.
- **Migración automática**: Cuando un usuario inicia sesión con una contraseña en formato PHpass, el hash de la contraseña se actualiza automáticamente a bcrypt.
- **Mayor seguridad**: bcrypt es más resistente a ataques de fuerza bruta comparado con el cifrado PHpass.
- **Fácil integración**: Funciona de manera transparente con el sistema de autenticación de WordPress.

## Requisitos

- WordPress 5.0 o superior.
- PHP 7.4 o superior.

## Instalación

1. Descarga el plugin o clona este repositorio:
   ```bash
   git clone https://github.com/tu_usuario/bcrypt-password-enforcer.git

2. Copia la carpeta del plugin en el directorio de plugins de WordPress:

    wp-content/plugins/

Activa el plugin desde el panel de administración de WordPress en la sección Plugins.

## Uso

* Migración de contraseñas antiguas: Cuando un usuario inicia sesión con una contraseña almacenada en el formato antiguo (PHpass), el plugin valida la contraseña y actualiza automáticamente su hash en la base de datos al formato bcrypt.
* Nuevos usuarios: Las contraseñas de los nuevos usuarios se almacenan automáticamente con bcrypt.
* Cambio de contraseña:Si un usuario cambia su contraseña (por ejemplo, mediante la función de "Olvidé mi contraseña"), el nuevo hash se generará utilizando bcrypt.

## Contribuciones
¡Las contribuciones son bienvenidas! Si tienes ideas o mejoras para este proyecto:
1. Haz un fork del repositorio.
2. Crea una rama para tu feature o fix:
   ```bash
    Copiar código
    git checkout -b feature-nueva

Realiza los cambios y envía un pull request.
## Licencia
Este proyecto está licenciado bajo la Licencia MIT.

Créditos
Autor: José Luis
Inspirado por la necesidad de mejorar la seguridad en WordPress.