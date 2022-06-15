# SMSto - PrestaShop Integration Module

SMSto integration enables PrestaShop automated SMS notifications to store administrator and customers for important order status updates, and also allows sending bulk SMS messages to customers. The Integration is free, but a SMSto account is required to send messages. Signup with our service is free as well, and you pay only for the SMS messages.

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

## How to make a new release

Navigate to **prestashop/html/modules** and run `./build.sh`

This will create file **smsto.zip** that is used for distribution. Attach this file when creating a new GitHub release.
