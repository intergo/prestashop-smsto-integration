# SMSto - PrestaShop Integration Module

The SMSto SMS Integration is an integration with the PrestaShop e-commerce platform https://www.prestashop.com/. This Integration enables PrestaShop store admins to configure automated SMS notifications to the administrator and customers for important order status updates, and also allows sending bulk SMS messages to customers. The Integration is free, but a SMSto account is required to send messages. Signup with our service is free as well, and you pay only for the SMS messages. The Integration offers great flexibility, in sending individual SMS or bulk SMS messages to various groups.

Compatibility with PrestaShop v1.7

## Setup development environment

Reference to PrestaShop developer documentation here https://devdocs.prestashop.com/1.7/basics/introduction/

To create a working docker environment follow below steps. Note that you need to already have a running docker environment that includes MariaDB and Traefik.

* Create a folder called **prestashop**
* Create a folder called **prestashop/html**
* Create a folder called **prestashop/docker**
* Download PrestaShop v1.7 from here https://www.prestashop.com/en/download 
* Extract downloaded file in folder **prestashop/html**
* Navigate to folder **prestashop/html/modules** and clone this repository in folder smsto: `git clone https://github.com/intergo/prestastop-smsto-integration.git smsto`
* Copy all files from folder **prestashop/html/modules/smsto/docker** in folder **prestashop/docker**
* Edit file **prestashop/docker/.env** as per your docker environment.
* Navigate to **prestashop/docker** and run `docker-compose up -d --build`

That's it!!!
