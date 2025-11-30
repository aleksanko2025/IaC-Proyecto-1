En este cambio he modificado en el Ansible el rol principal del Servidor Apache y MariaDB

Para empezar he creado nuevas variables para la creación de nuevo usuario y BD para Wordpress

```bash
wordpress_db_host: "mariadb.example.org"
wordpress_db_user: "wordp_user"
wordpress_db_password: "wordp_pass"
wordpress_db_name: "wordpress_db"
```
También variables para un nuevo virtualhost

```bash
  - name: wordpress
    datos:
      nameserver: www.wordpkosenko.org 
      documentroot: /var/www/wordpress
      errorlog: error_example
      accesslog: acceses_example
``` 

Creamos en el rol de MariaDB la nueva BD y usuario

```bash
- name: Crear base de datos WordPress
  community.mysql.mysql_db:
    name: "{{ wordpress_db_name }}"
    state: present
    login_unix_socket: /var/run/mysqld/mysqld.sock

- name: Crear usuario WordPress
  community.mysql.mysql_user:
    name: "{{ wordpress_db_user }}"
    password: "{{ wordpress_db_password }}"
    host: "%"
    priv: "{{ wordpress_db_name }}.*:ALL"
    state: present
    login_unix_socket: /var/run/mysqld/mysqld.sock
```
Descargamos Wordpress en nuesto Servidor Apache, añadimos en el rol apache:

```bash
- name: Descargar WordPress
  unarchive:
    src: https://wordpress.org/latest.tar.gz
    dest: /var/www/
    remote_src: yes
```

Si hacemos una instalación previa de Wordpress y recilamos los ficheros wp-config.php y con mysqldump hacemos un fichero del contenido de la BD de Wordpress. Podemos tener un CMS desplegado e instalado.

Movemos el fichero wp-config.php --> roles/apache2/files

Movemos el fichero wordpress_db_backup.sql --> roles/mariadb/files

Una vez insertados ahi, vamos a crear nuevas tareas para que esos ficheros se adjunten correctamente a nuestro proyecto.

En MariaDB

```bash
- name: Copiar archivo SQL desde el rol
  ansible.builtin.copy:
    src: wordpress_db_backup.sql
    dest: /tmp/wordpress_db_backup.sql
    mode: '0644'

- name: Comprobar si la tabla 'wp_posts' existe
  community.mysql.mysql_query:
    login_unix_socket: /var/run/mysqld/mysqld.sock
    query: "SHOW TABLES FROM wordpress_db LIKE 'wp_posts';"
  register: table_wp

- name: Importar base de datos WordPress
  ansible.builtin.shell: mysql -u "{{ wordpress_db_user }}" -p"{{ wordpress_db_password }}" wordpress_db < /tmp/wordpress_db_backup.sql
  become: true
  when: table_wp.query_result[0] | length == 0
```

En Apache2

```bash
- name: Copiar fichero Wordpress
  ansible.builtin.copy:
    src: wp-config.php
    dest: /var/www/wordpress
    mode: '0755'
    directory_mode: '0755'
```

Y ya tendriamos nuestro Wordpress operativo 