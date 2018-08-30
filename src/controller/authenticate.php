<?php

require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

function login() {
    $db = connect();
    $input = getInput();
    extract($input);
    try {
        $userData = $db->run('SELECT email,name,id FROM users WHERE email = ? AND password = ?', $email, $password);

        $db = null;
        if($userData){
            $userData{0}['token'] = apiToken($userData{0}['id']);
            echo json_encode($userData{0});
        } else {
            echo '{"error":{"text":"Wrong email and password"}}';
        }
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function register() {
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

                $stmt = $db->run("SELECT id FROM users WHERE email= ?", $email);
                $userData->token = apiToken($stmt->id);
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