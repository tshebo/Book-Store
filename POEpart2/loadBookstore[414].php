<?php

session_start();
include('DBConn.php');
 
// User table
$sql = "DROP TABLE IF EXISTS tblUser;
CREATE TABLE IF NOT EXISTS tblUser(
    ID INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(20) NOT NULL,
    UserLastname VARCHAR(20) NOT NULL,
    studentNo VARCHAR(20) NOT NULL,
    password VARCHAR(20) NOT NULL
    IsVerified TINYINT(1) DEFAULT 0

)";
mysqli_query($conn, $sql);

// Admin table
$sql = "DROP TABLE IF EXISTS tblAdmin;
CREATE TABLE IF NOT EXISTS tblAdmin(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($conn, $sql);

// Order table
$sql = "DROP TABLE IF EXISTS tblorder;
CREATE TABLE IF NOT EXISTS tblOrder(
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    Quantity VARCHAR(20) NOT NULL,
    PaymentID VARCHAR(20) NOT NULL,
    DeliveryID VARCHAR(50) NOT NULL
)";
mysqli_query($conn, $sql);

// Book table
$sql = "DROP TABLE IF EXISTS tblBooks;
CREATE TABLE IF NOT EXISTS tblBooks(
    BookID INT AUTO_INCREMENT PRIMARY KEY,
    BookTitle VARCHAR(50) NOT NULL,
    Author VARCHAR(20) NOT NULL,
    PublishingYear VARCHAR(20) NOT NULL
)";
mysqli_query($conn, $sql);
