![Cabecera PD Web WordPress QuickStart](_resources/heading.png "Cabecera PD Web WordPress QuickStart")

* Nombre del Servicio: pd-web-wp-portal-sectorial-quickstart 
* Fecha de publicación: PDTE 🚧
* URL:
  * PRO → PDTE 🚧
  * PRE → PDTE 🚧
  * DES → PDTE 🚧
* Responsable/s Funcional: 
  * Angel Casaseca de la Higuera. ADA 🚧
* Responsable/s Técnico:
  * Francisco Javier Anguas
  * Daniel Barrionuevo

## 1.1 Descripción Funcional

Producto digital (PD) basado en Wordpress que sirva como plantilla para la adopción del Sistema de Diseño de Servicios Digitales de cara a la ciudadanía para el desarrollo de Productos Digitales de tipo Portal Específico Sectorial.

# 2. Especificaciones Técnicas 👷

## 2.1 Descripción Técnica

Es un producto digital de tipo Wordpress, el cúal gestiona el contenido de manera dinámica y no realiza peticiones a ningún servicio backend. Este tipo de servicios están clonados a raíz de un proyecto base alojado en el siguiente repositorio: https://gitlab.juntadeandalucia.es/pd-web-templates/pd-web-wordpress-quickstart.git.</p>

## 2.2. Datos Técnicos

* Plantilla Base : https://gitlab.juntadeandalucia.es/pd-web-templates/pd-web-wordpress-quickstart.git.
* Estrategia de despliegue.  El despliegue del servicio está realizado manualmente. Se genera un build a partir del repositorio ubicado en GitLab, construyendo los POD's necesarios para despliegue, en este caso un POD para la base de datos y otro para el Apache WordPress. Con el fin de automatizar al máximo la preparación del entorno base para el comienzo de los desarrollos, se ha creado una plantilla templateWrodPressJDA.yaml, la cual realiza el despliegue de todos los elementos necesarios para el inicio del producto digital Wordpress agilizando con ello el inicio de los desarrollos.
Durante este proceso de despliegue, se crean todos los elementos necesarios (imagen, build, Deployment, service, route, …) para el funcionamiento del nuevo producto digital y así poder continuar con la gestión del contenido acorde a las necesidades de cada proyecto sin necesidad de mayor configuración.<br><br>
  * Enlace de OpenShift en desarrollo:https://openshift-des.sandetel.junta-andalucia.es:8443/console/project/des-pd-web-wp-portal-sectorial-quickstart/overview 🚧
  * Enlace de OpenShift en producción:https://paas.junta-andalucia.es:8443/console/project/pd-web-wp-portal-sectorial-quickstart/overview 🚧

* Monitorización: **12x5 - Soporte en horario de oficina** 🚧
* Routes
  * PRO → PDTE 🚧
  * PRE → PDTE 🚧
  * DES → PDTE 🚧
* Backup: Se solicita copia de seguridad funcional sobre los siguientes directorios:

    Wordpress: data-->/var/www/html/ read-write (Persistent Volume Container)

            tar -czvf contenidoWordpress.tar.gz /var/www/html/
            

    Base Datos: data-->/var/lib/mysql/data read-write (Persistent Volume Container)

            mysqldump -u (user) -p -h (host) --add-drop-database --no-create-db --databases wordpress > dumpCopiaSeguridad.sql

* Autoescalado: 
    Desarrollo : No
    Producción : Si
    - Min Pods: 1
    - Max Pods: 3:
    - CPU request: 70%
* Pruebas particulares de éste sitio 🚧

## 3.3. Pruebas de Carga

* Describir lo evaluado
* Adjuntar las pruebas JMeter personalizadas
