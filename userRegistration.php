<?php
require_once "db_connection.php";
// define variables and set to empty values
$fname = $lname = $gender = $email = $password = "";

if (file_get_contents('php://input')) {
    $data = json_decode(file_get_contents('php://input'), true);
    $fname = test_input($data["fname"], $link);
    $lname = test_input($data["lname"], $link);
    $gender = test_input($data["gender"], $link);
    $email = test_input($data["email"], $link);
    if (email_exists($email, $link)) {
        echo "User already exists!";
    } else {
        $password = password_hash(test_input($data["pwd"], $link), PASSWORD_DEFAULT);
        if (insert_user($fname, $lname, $gender, $email, $password, $link))
            echo "successfully inserted user";
        else echo "Sorry! something went wrong!";
    }
}

function email_exists($email, $link)
{
    $query = "SELECT * FROM Users WHERE email=" . $email;
    if ($result = $link->query($query)) {
        if ($result->num_rows > 0)
            return true;
    }
    return false;
}

function insert_user($fname, $lname, $gender, $email, $password, $link)
{
    $query = "INSERT INTO Users(fname, lname, gender, email, password)
VALUES ('$fname','$lname','$gender','$email','$password')";

    if ($link->query($query)) {
        $link->close();
        return true;
    }
    else {
        echo $link->error;
        $link->close();
    }
    return false;
}

function test_input($data, $link)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $link->real_escape_string($data);
    return $data;
}
