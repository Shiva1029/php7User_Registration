<?php

$link = new mysqli("localhost","collegestash4_user","wYO9VOplaa6NIZ0I", "collegestash4");

if ($link->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}