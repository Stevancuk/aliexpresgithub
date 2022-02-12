<?php
session_start();

require 'config/db.php';
//require_once 'emailController.php';

$errors = array();
$username = "";
$email = "";

//if user clicks on the sign up button
if(isset($_POST['signup-btn'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    //valifation
    if(empty($username)){
        $errors['username']="Username required.";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email']="Email address is invalid.";
    }
    if(empty($email)){
        $errors['email']="Email required.";
    }
    if(empty($password)){
        $errors['password']="Password required.";
    }
    if($password !== $passwordConf){
        $errors['password']= "The two passwords do not match.";
    }

    //unique email validation
    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    if($userCount > 0){
        $errors['email']="Email already exists.";
    }

    if(count($errors) === 0){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));
        $verified = false;

        $sql = "INSERT INTO users (username, email, verified, token, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssbss', $username, $email, $verified, $token, $password);
        if($stmt->execute()){
            //login user
            $user_id=$conn->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = $verified;

            //verification email link
            //sendVerificationEmail($email, $token);

            //set flash message
            $_SESSION['message'] = "You are now logged in!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }
        else{
            $errors['db_error']="Database error: failed to register.";
        }
    }
}


//if user clicks on the login button
if(isset($_POST['login-btn'])){
    $username = $_POST['username'];
    //$email = $_POST['email'];
    $password = $_POST['password'];

    //valifation
    if(empty($username)){
        $errors['username']="Username required.";
    }
    /*if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email']="Email address is invalid.";
    }
    if(empty($email)){
        $errors['email']="Email required.";
    }*/
    if(empty($password)){
        $errors['password']="Password required.";
    }

    if(count($errors)===0){
        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        //email or username - name=username
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        //$stmt->close();
    
        if(password_verify($password, $user['password'])){
            //login success
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = $user['verified'];
            $_SESSION['message'] = "You are now logged in!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }   
        else{
            $errors['login_fail'] = "Wrong credentials.";
        }
    }
}

//logout user
if(isset($_GET['logout'])){
    session_destroy();
    //session_unset();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['verified']);
    header('location: login.php');
    exit();
}


//verify user by token
function verifyUser($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE user SET verified=1 WHERE token='$token'";

        if(mysqli_query($conn, $update_query)){
            //log user in
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $$user['username'];
            $_SESSION['email'] = $$user['email'];
            $_SESSION['verified'] = 1;
            $_SESSION['message'] = "Your email address was succesfully verified!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }
    }
    else{
        echo 'User not found!';
    }
}
?>