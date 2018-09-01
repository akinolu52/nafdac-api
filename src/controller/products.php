<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/authenticate.php';
header('Content-Type: application/json');

// all products
function all() {
    $db = connect();
    $input = getInput();
    extract($input);
    try {
        $products = $db->run('SELECT * FROM products');
        $db = null;
        if($userData){
            echo json_encode($products);
        } else {
            echo json_encode('false');
        }
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// create a nnew product
function create() {
    $user = getUser($_SERVER['HTTP_X_TOKEN'])[0];
    if($user === false) {
        echo json_encode(false);
        die;
    }
    $db = connect();
    $input = getInput();
    extract($input);
    try {
        $db->insert('products', [
            'user_id' => $user['id'],
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $db = null;
        echo json_encode(true);
       
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// user all products
function index() {
    $user = getUser($_SERVER['HTTP_X_TOKEN'])[0];
    if($user === false) {
        echo json_encode(false);
        die;
    }
    $db = connect();
    try {
        $stmt = $db->run("SELECT * FROM products WHERE id= ?", $user['id']);
        
        $db = null;
        if($stmt){
            echo json_encode($stmt);
        } else {
            echo json_encode(false);
        }
    }
    catch(PDOException $e) {
       echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// single product
function single() {
    $db = connect();
    $input = getInput();
    extract($input);
    try {
        $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
        $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{3,20}$~i', $password);

        if (strlen(trim($password))>0 && strlen(trim($email))>0 && $email_check>0 && $password_check>0) {
            $stmt = $db->run("SELECT id FROM users WHERE email= ?", $email);
            
            $mainCount = count($stmt);
            if($mainCount == 0) {

                $db->insert('users', [
                    'email' => $email,
                    'password' => $password,
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $userData->email = $email;
                $userData->name = $name;

                $stmt = $db->run("SELECT email,name,id,role FROM users WHERE email= ?", $email);
                $userData->token = apiToken($stmt->id);
                // save the token
                $db->update('users', [
                    'token' => $userData->token
                ], [
                    'id' => $userData->id
                ]);
            }
            $db = null;
            if($userData){
                echo $userData = json_encode($userData);
            } else {
                echo '{"error":{"text":"Error"}}';
            }
        }
        else{
            echo '{"error":{"text":"Enter valid data"}}';
        }
    }
    catch(PDOException $e) {
       echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}