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
    if (!($stmt = $link->prepare("SELECT email FROM Users WHERE email=?"))) {
        echo "Prepare failed: (" . $link->errno . ") " . $link->error;
    }

    if (!$stmt->bind_param("s", $email)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    } else {
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if ($result)
            return true;
        else
            return false;
    }
}

function insert_user($fname, $lname, $gender, $email, $password, $link)
{
    if (!($stmt = $link->prepare("INSERT INTO Users(fname, lname, gender, email, password) VALUES (?, ?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $link->errno . ") " . $link->error;
    }

    if (!$stmt->bind_param("sssss", $fname, $lname, $gender, $email, $password)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        return false;
    } else {
        return true;
    }

}

function test_input($data, $link)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $link->real_escape_string($data);
    return $data;
}
