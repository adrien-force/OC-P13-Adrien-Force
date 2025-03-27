# GreenGoodies Site and API

<i>Project No. 13 of the PHP Symfony Application Developer training @OpenClassrooms  
<br> <a href="https://github.com/adrien-force/OC-P13-Adrien-Force/commits?author=adrien-force"><img src="https://img.shields.io/badge/Author_:-Adrien_FORCE-orange"></a></i>

Other languages: [Français](./README.md)

## 🎯 Table of Contents
- [Project Description](#-description)
- [Project Installation](#-installation)
- [Prerequisites](#-prerequisites)
- [Usage](#-usage)

## 📄 Description
<br>  

This project involves continuing the development of a website and API for a fictional company named GreenGoodies.  
The purpose of this website is to provide a solution allowing customers to browse the company’s products, add them to their cart, and place orders.

During this project, I implemented a user authentication system to secure access to the various site features.  
The project includes a shopping cart management system, enabling users to view, modify, or remove products from their cart.  
Orders placed by users are stored in a database and can be viewed by the users; of course, these orders are purely fictional 😉.  
<br> <br>

## 🔧 Prerequisites

- Symfony ^7.0
- Symfony CLI
- Composer
- PHP >= 8.0, < 8.4
- Docker

## 🛠️ Installation

1. Clone the project to your machine
```bash  
git clone https://github.com/adrien-force/OC-P13-Adrien-Force.git  
```  

2. Install the project
```bash  
make install  
```  

Note: If everything is fine, the database container ports should be valid. Otherwise, you may need to update them in the `.env.local` file by replacing them with the ports displayed by `docker ps`.

## 🔥️ Usage

The project is a website developed using PHP, HTML, and pure CSS, without any front-end libraries.

To start using it, go to the local URL: http://greengoodies.local

You can sign up or log in with an existing user account.  
Among the different accounts created with fixtures,  
<br> there is an administrator account:
- Email: admin@greengoodies.com  
  <br> and a basic user account:
- Email: user@greengoodies.com

All users created with fixtures have the password "password".  
<br> <br>

An API documentation is available at: http://greengoodies.local/api/doc  
<br>  
