-- Tabla "Usuarios"
CREATE TABLE Usuarios (
  ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
  Nombre_usuario VARCHAR(255),
  Contrasena VARCHAR(255),
  Puesto VARCHAR(255),
  Correo_electronico VARCHAR(255),
  Numero_telefono VARCHAR(255),
  Fecha_creacion DATE,
  Ultimo_inicio_sesion DATETIME
);

-- Tabla "Formulas"
CREATE TABLE Formulas (
  ID_formula INT PRIMARY KEY AUTO_INCREMENT,
  Descripcion VARCHAR(255),
  Fecha_creacion DATE,
  ID_usuario INT,
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Ingredientes"
CREATE TABLE Ingredientes (
  ID_ingrediente INT PRIMARY KEY AUTO_INCREMENT,
  Nombre_ingrediente VARCHAR(255),
  Descripcion VARCHAR(255),
  Unidad_medida VARCHAR(255),
  Proveedor VARCHAR(255),
  
);

-- Tabla "Ingredientes_Formulas"
CREATE TABLE Ingredientes_Formulas (
  ID_ingrediente_formula INT PRIMARY KEY AUTO_INCREMENT,
  ID_formula INT,
  ID_ingrediente INT,
  Cantidad DECIMAL(10, 2),
  FOREIGN KEY (ID_formula) REFERENCES Formulas(ID_formula),
  FOREIGN KEY (ID_ingrediente) REFERENCES Ingredientes(ID_ingrediente)
);


-- Tabla "Pedidos"
CREATE TABLE Pedidos (
  ID_pedido INT PRIMARY KEY AUTO_INCREMENT,
  ID_cliente INT,
  Fecha_pedido DATE,
  Estado VARCHAR(255),
  ID_usuario INT,
  FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Detalles_pedido"
CREATE TABLE Detalles_pedido (
  ID_detalle INT PRIMARY KEY AUTO_INCREMENT,
  ID_pedido INT,
  ID_formula INT,
  Cantidad INT,
  FOREIGN KEY (ID_pedido) REFERENCES Pedidos(ID_pedido),
  FOREIGN KEY (ID_formula) REFERENCES Formulas(ID_formula)
);

-- Tabla "Produccion"
CREATE TABLE `Produccion` (
  `ID_produccion` int NOT NULL,
  `ID_formula` int DEFAULT NULL,
  `Cantidad_producida` int DEFAULT NULL,
  `Fecha_produccion` date DEFAULT NULL,
  `ID_usuario` int DEFAULT NULL,
  `ID_pedido` int DEFAULT NULL,
  PRIMARY KEY (`ID_produccion`),
  UNIQUE KEY `ID_pedido` (`ID_pedido`),
  KEY `ID_formula` (`ID_formula`),
  KEY `ID_usuario` (`ID_usuario`),
  CONSTRAINT `produccion_ibfk_1` FOREIGN KEY (`ID_formula`) REFERENCES `Formulas` (`ID_formula`),
  CONSTRAINT `produccion_ibfk_2` FOREIGN KEY (`ID_usuario`) REFERENCES `Usuarios` (`ID_usuario`),
  CONSTRAINT `producción_ibfk_3` FOREIGN KEY (`ID_pedido`) REFERENCES `Pedidos` (`ID_pedido`) ON DELETE RESTRICT ON UPDATE RESTRICT
) 


-- Tabla "Proveedores"
CREATE TABLE Proveedores (
  ID_proveedor INT PRIMARY KEY AUTO_INCREMENT,
  Nombre_proveedor VARCHAR(255),
  Direccion VARCHAR(255),
  Numero_telefono VARCHAR(255),
  Correo_electronico VARCHAR(255)
);

-- Tabla "Clientes"
CREATE TABLE Clientes (
  ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
  Nombre_cliente VARCHAR(255),
  Direccion VARCHAR(255),
  Numero_telefono VARCHAR(255),
  Correo_electronico VARCHAR(255)
);




-- Tabla "Ordenes_compra"
CREATE TABLE Ordenes_compra (
  ID_orden_compra INT PRIMARY KEY ,
  ID_proveedor INT,
  Fecha_orden DATE,
  Estado VARCHAR(255),
  ID_usuario INT,
  FOREIGN KEY (ID_proveedor) REFERENCES Proveedores(ID_proveedor),
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);
