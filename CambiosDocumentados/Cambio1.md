El primero cambio que realizamos es quitar la interfaz con la red NAT de la máquina de MariaDB y configurar como salida por defecto hacia la máquina de Servidor Apache

*OpenTofu > cloud-init > server2 > network-config.yaml*

```bash
network:
  version: 2
  ethernets:
    ens3: 
      dhcp4: false
      addresses: ["192.168.201.2/24"]
    ens4: 
      dhcp4: false
      addresses: ["10.0.0.2/24"]
      routes:
        - to: default
          via: 10.0.0.1
      nameservers:
        addresses: [8.8.8.8, 1.1.1.1] 
```

Ahora vamos a modificar el escenario.tf para que al crear la maquina MariaDB no intente configurar la red a una interfaz que ya no existe:

```bash
mariadb = {
      name       = "mariadbiac"
      memory     = 1024
      vcpu       = 1
      base_image = "ubuntu2404-base.qcow2"

      networks = [
        { network_name = "red-conf" },
        { network_name = "red-datos" }
      ]

      user_data      = "${path.module}/cloud-init/server2/user-data.yaml"
      network_config = "${path.module}/cloud-init/server2/network-config.yaml"
    }
``` 

Una vez terminado de modificar nuestro escenario con OpenTofu 

![captura-tofu](../CambiosDocumentados/capturas/Captura%20desde%202025-11-21%2014-32-18.png)


Ahora pasamos a modificar a las instrucciones ansible:

Creamos un nuevo role que será router, cuya configuración presenta módulos de ansible que lo van a configurar como tal:

```bash
- name: Habilitar IP forwarding permanente en Debian
  ansible.builtin.lineinfile:
    path: /etc/sysctl.conf
    regexp: '^net.ipv4.ip_forward='
    line: 'net.ipv4.ip_forward=1'
    create: yes
  when: ansible_distribution == "Debian"

- name: Recargar sysctl en Debian
  ansible.builtin.command: sysctl -p
  when: ansible_distribution == "Debian"

- name: Instalar iptables y iptables-persistent en Debian
  ansible.builtin.apt:
    name:
      - iptables
      - iptables-persistent
    state: present
    update_cache: yes
  environment:
    DEBIAN_FRONTEND: noninteractive
  when: ansible_distribution == "Debian"

- name: Configurar SNAT para MariaDB
  ansible.builtin.iptables:
    table: nat
    chain: POSTROUTING
    out_interface: ens3          
    source: 10.0.0.2/32
    jump: MASQUERADE
    state: present
  when: ansible_distribution == "Debian"

- name: Guardar reglas de iptables en Debian
  ansible.builtin.command: netfilter-persistent save
  when: ansible_distribution == "Debian"
```

Por último en el fichero site.yaml cambiamos el orden de ejecución de roles y ponemos que se configure primero el servidor web como router y posteriormente los demás cambios, para que a la hora de ejecutarse el rol del servidor de base de datos tenga acceso a internet. Quedaría en el siguiente orden:

```bash
- hosts: servidores_web
  become: true
  roles:
   - role: apache2

- hosts: servidores_web
  become: true
  roles:
   - role: router

- hosts: all
  become: true
  roles:
   - role: commons

- hosts: servidores_bd
  become: true
  roles:
   - role: mariadb
```
