CREATE DATABASE intersys CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS intersys;
USE intersys;

CREATE TABLE usuario(
id int(100) auto_increment not null, 
nombre varchar(150) not null,  
apellido varchar(60) not null,
documento varchar(10),
email varchar (150) not null, 
password varchar (150) not null, 
fecha date not null,
CONSTRAINT pk_usuarios PRIMARY KEY (id),
CONSTRAINT uq_email UNIQUE (email)
)ENGINE=InnoDB;

CREATE TABLE plan (
id int(100) auto_increment not null,  
nombre varchar(150) not null,
costo float (20,2) not null, 
CONSTRAINT pk_plan PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE accespoint (
id int (100) auto_increment not null, 
ssid varchar (150) not null,
frecuencia varchar (50) not null,
ip_ap varchar (15) not null,
numcliente int (10) not null,
localidad varchar (100) not null,
CONSTRAINT pk_accespoint PRIMARY KEY (id)  
)ENGINE=InnoDB;

CREATE TABLE cuotas (
id int (100) auto_increment not null, 
numero int (100), 
fecha_emision date,
CONSTRAINT pk_cuotas PRIMARY KEY (id)
)ENGINE=InnoDB;


CREATE TABLE cliente(
id int(100) auto_increment not null, 
id_plan int(100) not null, 
id_point int (100) not null,
nombre varchar(150) not null, 
apellido varchar(50) not null, 
direccion varchar(255) not null,
telefono varchar(20),
ip varchar(15),
fecha_alta date not null,
CONSTRAINT pk_cliente PRIMARY KEY(id),
CONSTRAINT fk_cliente_plan FOREIGN KEY(id_plan) REFERENCES plan(id),
CONSTRAINT fk_cliente_accespoint FOREIGN KEY(id_point) REFERENCES accespoint (id)   
)ENGINE=InnoDB; 

CREATE TABLE pagos(
id int (100) auto_increment not null, 
id_cliente int (100) not null,
id_cuota int (100) not null,
num_cuotas int (100) not null,
fecha_emision date,
costo float not null,
abonado float,
estado boolean not null,
fecha_pago date, 
CONSTRAINT pk_pagos PRIMARY KEY (id),
CONSTRAINT fk_pagos_cliente FOREIGN KEY (id_cliente) REFERENCES cliente (id),
CONSTRAINT fk_pagos_cuotas FOREIGN KEY (id_cuota) REFERENCES cuotas (id) 
)ENGINE=InnoDB;