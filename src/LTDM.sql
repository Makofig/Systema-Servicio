 IF NOT EXISTS(SELECT * FROM sys.databases WHERE name = 'DataBase')
  BEGIN
    CREATE DATABASE [DataBase]


    END
    GO
       USE [DataBase]
    GO
--You need to check if the table exists
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='TableName' and xtype='U')
BEGIN
    CREATE TABLE TableName (
        Id INT PRIMARY KEY IDENTITY (1, 1),
        Name VARCHAR(100)
    )
END

CREATE DATABASE ltdm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS ltdm;
USE ltdm;

CREATE TABLE usuario(
id int(100) auto_increment not null,
id_plan int(100) not null, 
nombre varchar(150) not null, 
apellido varchar(60) not null,
documento varchar (10) not null,
email varchar (150) not null, 
password varchar (150) not null, 
direccion varchar (255) not null,
telefono int(15),
fecha date not null,
CONSTRAINT pk_usuarios PRIMARY KEY (id),
CONSTRAINT fk_servicio_plan FOREIGN KEY (id_plan) REFERENCES plan(id),
CONSTRAINT uq_email UNIQUE (email)
)ENGINE=InnoDB;

CREATE TABLE servicio(
id int(100) auto_increment not null, 
id_usuario int(100) not null, 
nombre varchar(150) not null, 
estado varchar(50) not null, 
descripcion varchar(255) not null,
CONSTRAINT pk_servicio PRIMARY KEY (id),
CONSTRAINT fk_servicio_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id),   
)ENGINE=InnoDB; 

CREATE TABLE plan (
id int(100) auto_increment not null,  
nombre varchar(150) not null,
ip varchar(15),
estado varchar(50) not null, 
costo float (20,2) not null, 
fecha_alta date not null, 
CONSTRAINT pk_plan PRIMARY KEY (id)
)ENGINE=InnoDB; 

CREATE TABLE producto (
id int(100) auto_increment not null,
nombre varchar(150) not null, 
estado varchar(50) not null,
descripcion varchar(255) not null,
cantidad int(100) not null, 
costo float (20,2) not null, 
fecha_ingreso date not null,
fecha_estimada date not null, 
CONSTRAINT pk_producto PRIMARY KEY (id)
)ENGINE=InnoDB;
