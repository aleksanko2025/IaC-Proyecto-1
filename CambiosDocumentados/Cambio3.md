Primero vamos a crear al usuario phpmyadmin desde el servidor de base de datos y le damos permisos para que pueda manejar todo.

```bash
- name: Crear usuario phpmyadmin
  community.mysql.mysql_user:
    name: "{{ phpmyadmin_user }}"
    password: "{{ phpmyadmin_password }}"
    host: "%"
    priv: "*.*:ALL"
    state: present
    login_unix_socket: /var/run/mysqld/mysqld.sock
```

En el servidor web instalamos phpmyadmin

```bash
- name: Instalar phpmyadmin
  apt:
    name:
      - phpmyadmin
    state: present
  environment:
        DEBIAN_FRONTEND: noninteractive
```

Tambén nos tenenmos que asegurar de tener los siguientes paquetes instalados:

      - php-mysql
      - php-mbstring
      - php-gd
      - php-xml
      - php-mbstring
      - php-curl
      - php-intl
      - php-zip
      - php-json

Para tener configurada la conexión con el servidor de base de datos he reciclado el fichero config.inc.php de una instalación anteriror y lo adjunto con ansible en el directorio files.

Pasamos a configurar el fichero de configuración de apache para phpmyadmin:

```bash
- name: Crear archivo de configuración de Apache para phpMyAdmin
  copy:
    dest: /etc/apache2/conf-available/phpmyadmin.conf
    content: |
          Alias /phpmyadmin /usr/share/phpmyadmin

          <Directory /usr/share/phpmyadmin>
              Options SymLinksIfOwnerMatch
              DirectoryIndex index.php

              <IfModule mod_php.c>
                  AddType application/x-httpd-php .php
              </IfModule>

              <FilesMatch "\.php$">
                  SetHandler application/x-httpd-php
              </FilesMatch>

              Require all granted
          </Directory>
```

Por último habilitamos phpmyadmin y reiniciamos apache2

```bash
- name: Habilitar phpmyadmin
  command: a2enconf phpmyadmin

- name: Reiniciar Apache
  service:
      name: apache2
      state: restarted
```
