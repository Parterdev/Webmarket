create database Micromarket default character set utf8 collate utf8_unicode_ci;
use Micromarket;

create table usuarios (
id_usuario int(11) NOT NULL AUTO_INCREMENT,
nombre varchar(20) NOT NULL,
apellido varchar(20) NOT NULL,
user_name varchar(64) NOT NULL,
user_password_hash varchar(255) NOT NULL,
user_email varchar(64) NOT NULL,
date_added datetime NOT NULL,
PRIMARY KEY(id_usuario)
)DEFAULT CHARSET=utf8;

INSERT INTO usuarios (id_usuario, nombre, apellido, user_name, user_password_hash, user_email, date_added) VALUES
(1, 'Paul', 'Teran', 'admin', '$2y$10$MPVHzZ2ZPOWmtUUGCq3RXu31OTB.jo7M9LZ7PmPQYmgETSNn19ejO', 'litemeilateran@outlook.com', '2018-07-01 16:00:00');

create table categorias (
id_categoria int(11) NOT NULL AUTO_INCREMENT,
nombre_categoria varchar(255) NOT NULL,
descripcion_categoria varchar(255) NOT NULL,
dato_agregado datetime NOT NULL,
PRIMARY KEY(id_categoria)
)DEFAULT CHARSET=utf8;

INSERT INTO categorias (id_categoria, nombre_categoria, descripcion_categoria, dato_agregado) VALUES
(1, 'Carnes y embutidos', 'Productos frescos para el hogar', '2018-07-01 16:15:00'),
(4, 'Lacteos', 'Leche, queso, huevos y más ', '2018-07-01 16:16:37'),
(5, 'Snacks', 'Dulces, antojos y golosinas', '2018-07-01 16:18:39');

create table productos (
id_producto int(11) NOT NULL AUTO_INCREMENT,
codigo_producto varchar(20) NOT NULL,
nombre_producto varchar(255) NOT NULL,
estado_producto tinyint(4) NOT NULL,
dato_agregado datetime NOT NULL,
precio_producto double NOT NULL,
stock int(11) NOT NULL,
id_usuario int(11) NOT NULL,
id_categoria int(11) NOT NULL,
PRIMARY KEY(id_producto)
)DEFAULT CHARSET=utf8;

create table historial (
id_historial int(11) NOT NULL AUTO_INCREMENT,
id_producto int(11) NOT NULL,
id_usuario int(11) NOT NULL,
fecha datetime NOT NULL,
nota varchar(255) NOT NULL,
referencia varchar(100) NOT NULL,
cantidad int(11) NOT NULL,
PRIMARY KEY(id_historial)
)DEFAULT CHARSET=utf8;

create table clientes (
id_cliente int(11) NOT NULL AUTO_INCREMENT,
cedula_cliente varchar(10) NOT NULL,
nombre_cliente varchar(100) NOT NULL,
telefono_cliente char(25) NOT NULL,
email_cliente varchar(100) NOT NULL,
direccion_cliente varchar(100) NOT NULL,
estado_cliente tinyint(4) NOT NULL,
dato_agregado datetime NOT NULL,
PRIMARY KEY(id_cliente)
)DEFAULT CHARSET=utf8;

create table detalle_factura (
id_detalle int(11) NOT NULL AUTO_INCREMENT,
numero_factura int(11) NOT NULL,
id_producto int(11) NOT NULL,
cantidad int(11) NOT NULL,
precio_venta double NOT NULL,
PRIMARY KEY(id_detalle)
)DEFAULT CHARSET=utf8;

create table facturas (
id_factura int(11) NOT NULL AUTO_INCREMENT,
numero_factura int(11) NOT NULL,
fecha_factura datetime NOT NULL,
id_cliente int(11) NOT NULL,
id_vendedor int(11) NOT NULL,
condiciones varchar(100) NOT NULL,
total_venta varchar(50) NOT NULL,
estado_factura tinyint(1) NOT NULL,
PRIMARY KEY(id_factura)
)DEFAULT CHARSET=utf8;

create table transaccion (
id_transaccion int(11) NOT NULL AUTO_INCREMENT,
id_producto int(11) NOT NULL,
cantidad_transaccion int(11) NOT NULL,
precio_transaccion double(8,2) NOT NULL,
id_sesion varchar(200) NOT NULL,
PRIMARY KEY(id_transaccion)
)DEFAULT CHARSET=utf8;

create table empresa (
id_perfil int(11) NOT NULL,
nombre_empresa varchar(150) NOT NULL,
direccion varchar(255) NOT NULL,
ciudad varchar(100) NOT NULL,
codigo_postal varchar(100) NOT NULL,
estado varchar(100) NOT NULL,
telefono varchar(20) NOT NULL,
email varchar(64) NOT NULL,
impuesto int(2) NOT NULL,
moneda varchar(6) NOT NULL,
logo_url varchar(255) NOT NULL,
PRIMARY KEY (id_perfil)
)DEFAULT CHARSET=utf8;

INSERT INTO empresa (id_perfil,nombre_empresa,direccion,ciudad,codigo_postal,estado,telefono,email,impuesto,moneda,logo_url) VALUES
(1, 'Nombre del negocio', 'Avenida de los granados  S7-2392', 'Quito', '2732', 'Pichincha', '+(593) 983275498', 'nombreusuario@hotmail.com', 12, '$', 'img/logo_company.jpg');

create table moneda (
id int(10) unsigned NOT NULL,
name varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
symbol varchar(255) NOT NULL,
precision_moneda varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
thousand_separator varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
decimal_separator varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
code varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (id)
)DEFAULT CHARSET=utf8;

INSERT INTO moneda (id,name,symbol,precision_moneda,thousand_separator,decimal_separator,code) VALUES (1, 'US Dollar', '$', '2', ',', '.', 'USD');

ALTER TABLE historial ADD CONSTRAINT history_product FOREIGN KEY (id_producto) REFERENCES productos (id_producto); 
ALTER TABLE productos ADD CONSTRAINT category_product FOREIGN KEY (id_categoria) REFERENCES categorias (id_categoria); 






