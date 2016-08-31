CREATE DATABASE pharmacy_db;

DROP TABLE tblsales_product;
DROP TABLE tblProduct;
DROP TABLE tblSales;
DROP TABLE tbluser CASCADE CONSTRAINTS;

CREATE TABLE tblUser(
userId INT PRIMARY KEY,
firstName VARCHAR(50),
lastName VARCHAR(50),
userType VARCHAR(20),
dateCreated DATE,
userName VARCHAR(10),
password VARCHAR(10)
)

CREATE TABLE tblProduct (
productId INT PRIMARY KEY,
productName VARCHAR(100) NOT NULL,
Type VARCHAR(50),
Description VARCHAR(200),
supplierName VARCHAR(50),
cost FLOAT,
price FLOAT,
ExpiryDate DATE,
Qty INT
)

CREATE TABLE tblSales (
invoiceId INT PRIMARY KEY,
status VARCHAR(10),
Description VARCHAR(200),
userId INT,
saleAmount FLOAT,
saleDate DATE,
FOREIGN KEY (userId) REFERENCES tblUser(userId)
)

CREATE TABLE tblSales_product (
invoiceId INT,
productId INT,
Qty INT,
unitPrice FLOAT,
totalPrice FLOAT,
PRIMARY KEY (invoiceId,productId),
FOREIGN KEY (invoiceId) REFERENCES tblSales(invoiceId),
FOREIGN KEY (productId) REFERENCES tblProduct(productId)
)


