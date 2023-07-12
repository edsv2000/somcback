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
  ID_formula INT PRIMARY KEY,
  Descripcion VARCHAR(255),
  Fecha_creacion DATE,
  ID_usuario INT,
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Ingredientes"
CREATE TABLE Ingredientes (
  ID_ingrediente INT PRIMARY KEY,
  Nombre_ingrediente VARCHAR(255),
  Descripcion VARCHAR(255),
  Unidad_medida VARCHAR(255),
  Proveedor VARCHAR(255)
);

-- Tabla "Ingredientes_Formulas"
CREATE TABLE Ingredientes_Formulas (
  ID_ingrediente_formula INT PRIMARY KEY,
  ID_formula INT,
  ID_ingrediente INT,
  Cantidad DECIMAL(10, 2),
  FOREIGN KEY (ID_formula) REFERENCES Formulas(ID_formula),
  FOREIGN KEY (ID_ingrediente) REFERENCES Ingredientes(ID_ingrediente)
);

-- Tabla "Inventario"
CREATE TABLE Inventario (
  ID_producto INT PRIMARY KEY,
  Nombre_producto VARCHAR(255),
  Cantidad_disponible INT,
  Precio_unitario DECIMAL(10, 2),
  Fecha_creacion DATE,
  ID_usuario INT,
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Pedidos"
CREATE TABLE Pedidos (
  ID_pedido INT PRIMARY KEY,
  ID_cliente INT,
  ID_producto INT,
  Cantidad INT,
  Fecha_pedido DATE,
  Estado VARCHAR(255),
  ID_usuario INT,
  FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
  FOREIGN KEY (ID_producto) REFERENCES Inventario(ID_producto),
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Proveedores"
CREATE TABLE Proveedores (
  ID_proveedor INT PRIMARY KEY,
  Nombre_proveedor VARCHAR(255),
  Direccion VARCHAR(255),
  Numero_telefono VARCHAR(255),
  Correo_electronico VARCHAR(255)
);

-- Tabla "Clientes"
CREATE TABLE Clientes (
  ID_cliente INT PRIMARY KEY,
  Nombre_cliente VARCHAR(255),
  Direccion VARCHAR(255),
  Numero_telefono VARCHAR(255),
  Correo_electronico VARCHAR(255)
);

-- Tabla "Produccion"
CREATE TABLE Produccion (
  ID_produccion INT PRIMARY KEY,
  ID_formula INT,
  Cantidad_producida INT,
  Fecha_produccion DATE,
  ID_usuario INT,
  FOREIGN KEY (ID_formula) REFERENCES Formulas(ID_formula),
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);

-- Tabla "Ordenes_compra"
CREATE TABLE Ordenes_compra (
  ID_orden_compra INT PRIMARY KEY,
  ID_proveedor INT,
  Fecha_orden DATE,
  Estado VARCHAR(255),
  ID_usuario INT,
  FOREIGN KEY (ID_proveedor) REFERENCES Proveedores(ID_proveedor),
  FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);
