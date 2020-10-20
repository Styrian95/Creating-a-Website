<?php

class Database {
    // Private variable that can only be called inside the class and it's child objects
    private $mysqli;
    // The constructor gets called when the class gets created
    public function __construct($host, $dbname, $user, $password) {
        // Opens the database connection with the variables from the config.php
        $this->mysqli = new mysqli($host, $user, $password, $dbname);
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
        }
    }
    // Database-Getter
    public function getDatabase() {
        return $this->mysqli;
    }
    
    public function getUser($email) {
        // Prepare db-statements
        $sql = "SELECT * FROM t_user WHERE email =?";
        $stmt= $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $sqlResult = $stmt->get_result()->fetch_assoc(); 
        if (is_countable($sqlResult) && count($sqlResult) > 0) {
            // Creates an new user
            $user = new User();
            $user->userId = $sqlResult['user_id'];
            $user->courseId = $sqlResult['course_id'];
            $user->email = $sqlResult['email'];
            $user->password = $sqlResult['password'];
            $user->firstname = $sqlResult['firstname'];
            $user->lastname = $sqlResult['lastname'];
            $user->postalCode = $sqlResult['postal_code'];
            $user->city = $sqlResult['city'];
            $user->street = $sqlResult['street'];
        } else{
            return false;
        }
        // Returns the created user
        return $user;
    }


   public function getAllInternalUsers() {

        $sql = "SELECT * FROM t_internal_user;";
        $stmt= $this->mysqli->prepare($sql);
        $stmt->execute();
        $sqlResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $internalUsers = array();

        foreach ($sqlResult as $SqlInternalUser) {
            $internalUser = new InternalUser();
            $internalUser->internalUserId = $SqlInternalUser['internal_user_id'];
            $internalUser->isAdministrator = $SqlInternalUser['is_administrator'];
            $internalUser->email = $SqlInternalUser['email'];
            $internalUser->password = $SqlInternalUser['password'];
            $internalUser->firstname = $SqlInternalUser['firstname'];
            $internalUser->lastname = $SqlInternalUser['lastname'];
            $internalUser->postalCode = $SqlInternalUser['postal_code'];
            $internalUser->city = $SqlInternalUser['city'];
            $internalUser->street = $SqlInternalUser['street'];
            array_push($internalUsers, $internalUser);
        }

        // Returns the course
        return $internalUsers;

    }

  
?>
