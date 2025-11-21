El primero cambio que realizamos es quitar la interfaz con la red NAT de la máquina de MariaDB

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

