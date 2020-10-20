<?php

require_once(__DIR__.'/../classes/user.php');
require_once(__DIR__.'/../classes/internalUser.php');
require_once(__DIR__.'/../classes/course.php');
require_once(__DIR__.'/../classes/document.php');

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
        // Creates the user from the returned row if one is returned
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






    // Gets an user by the email
    public function getInternalUser($email) {
        // Prepare db-statements
        $statement = "SELECT * FROM t_internal_user WHERE email = '$email';";
        // Executes the query
        $result = mysqli_query($this->mysqli, $statement);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // Creates the user from the returned row if one is returned
        if (is_countable($sqlResult) && count($sqlResult) > 0) {
            // Creates an new user
            $user = new User();
            $user->internalUserId = $sqlResult['internal_user_id'];
            $user->isAdministrator = $sqlResult['is_administrator'];
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

    public function getCourse($courseId) {

        $statement = "SELECT * FROM t_course WHERE course_id = '$courseId';";
        // Executes the query
        $result = mysqli_query($this->mysqli, $statement);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // Creates the course from the returned row if one is returned
        if (count($sqlResult) > 0) {
            // Creates an new course
            $course = new Course();
            $course->courseId = $sqlResult['course_id'];
            $course->name = $sqlResult['name'];
            $course->internalUserId = $sqlResult['internal_user_id'];
        }
        // Returns the course
        return $course;

    }
    
    public function getAllCourses() {

        $statement = "SELECT * FROM t_course;";
        // Executes the query
        $result = mysqli_query($this->mysqli, $statement);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Creates the course from the returned row if one is returned
        $courses = array();

        foreach ($sqlResult as $SqlCourse) {
            $course = new Course();
            $course->courseId = $SqlCourse['course_id'];
            $course->name = $SqlCourse['name'];
            $course->internalUserId = $SqlCourse['internal_user_id'];
            array_push($courses, $course);
        }

        // Returns the course
        return $courses;

    }

/*
    public function getAllCourses() {

        $sql = "SELECT * FROM t_course";
        $stmt= $this->mysqli->prepare($sql);
        $stmt->execute();

        $sqlResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $courses = array();

        foreach ($sqlResult as $SqlCourse) {
            $course = new Course();
            $course->courseId = $SqlCourse['course_id'];
            $course->name = $SqlCourse['name'];
            $course->internalUserId = $SqlCourse['internal_user_id'];
            array_push($courses, $course);
        }

        // Returns the course
        return $courses;


    }

*/




/*
    public function getAllInternalUsers() {

        $statement = "SELECT * FROM t_internal_user;";
        // Executes the query
        $result = mysqli_query($this->mysqli, $statement);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Creates the course from the returned row if one is returned
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

    }*/

    public function getAllInternalUsers() {

        $sql = "SELECT * FROM t_internal_user;";
        $stmt= $this->mysqli->prepare($sql);
        $stmt->execute();
        $sqlResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        // Creates the course from the returned row if one is returned
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

    public function getAllUsers() {

        $statement = "SELECT * FROM t_user u INNER JOIN t_course c ON u.course_id = c.course_id;";
        // Executes the query
        $result = mysqli_query($this->mysqli, $statement);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Creates the course from the returned row if one is returned
        $users = array();

        foreach ($sqlResult as $SqlUser) {
            $user = new User();
            $user->userId = $SqlUser['user_id'];
            $user->courseId = $SqlUser['course_id'];
            $user->courseName = $SqlUser['name'];
            $user->email = $SqlUser['email'];
            $user->password = $SqlUser['password'];
            $user->firstname = $SqlUser['firstname'];
            $user->lastname = $SqlUser['lastname'];
            $user->postalCode = $SqlUser['postal_code'];
            $user->city = $SqlUser['city'];
            $user->street = $SqlUser['street'];
            array_push($users, $user);
        }

        // Returns the course
        return $users;

    }

    // Gets all documents for a specific courseId
    public function getDocuments($courseId) {
        // Prepare db-statements
        $sql = "SELECT * FROM t_document WHERE course_id = '$courseId'";
        // Executes the query
        $result = mysqli_query($this->mysqli, $sql);
        // Creates an associative array from the result
        $sqlResult = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // creates an empty array
        $documents = array();
        // Creates and pushes a document for each returned row
        foreach ($sqlResult as & $SqlDocument) {
            // Creates a new document
            $document = new Document();
            $document->documentId = $SqlDocument['document_id'];
            $document->courseId = $SqlDocument['course_id'];
            $document->path = $SqlDocument['path'];
            $document->displayName = $SqlDocument['display_name'];
            // Pushes the newly created document into the array
            array_push($documents, $document);
        }
        // Returns an array of document objects
        return $documents;
    }

    public function createUser($newUser){

        $hashedPassword = password_hash($newUser->password, PASSWORD_DEFAULT);

        $statement = "INSERT INTO t_user (email, password, firstname, lastname, postal_code, city, street, course_id) VALUES ('$newUser->email', '$hashedPassword','$newUser->firstname','$newUser->lastname','$newUser->postalCode','$newUser->city','$newUser->street','$newUser->courseId')";

        return $this->mysqli->query($statement);
    }

    public function createInternalUser($newInternalUser){

        $hashedPassword = password_hash($newInternalUser->password, PASSWORD_DEFAULT);

        $statement = "INSERT INTO t_internal_user (email, password, firstname, lastname, postal_code, city, street, is_administrator) VALUES ('$newInternalUser->email', '$hashedPassword','$newInternalUser->firstname','$newInternalUser->lastname','$newInternalUser->postalCode','$newInternalUser->city','$newInternalUser->street','$newInternalUser->isAdministrator')";

        return $this->mysqli->query($statement);
    }





    // Determines if a user login attempt succeeds or fails
    public function login($email, $password) {
        // Checks if the user is in the database
        $user = $this->getUser($email);
        // Cecks if a user got returned
        if ($user) {
            // Checks if the provides password matches
            if (password_verify($password, $user->password)) {
                // Returns the user
                return $user;
            } else {
                // Password didn't match
                return false;
            }
        } else {
            // email is not in the database
            return false;
        }
    }

        // Determines if a internal user login attempt succeeds or fails
    public function internalLogin($email, $password) {
        // Checks if the user is in the database
        $user = $this->getInternalUser($email);
        // Cecks if a user got returned
        if ($user) {
            // Checks if the provides password matches
            if (password_verify($password, $user->password)) {
                // Returns the user
                return $user;
            } else {
                // Password didn't match
                return false;
            }
        } else {
            // email is not in the database
            return false;
        }
    }



}

?>