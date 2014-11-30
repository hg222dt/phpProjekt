<?php

require_once("databaseCred.php");

/*
 * Dataaccesslager för allt relaterat till användar-data i databasen
 *
 **/

class UserDAL {

    private $databaseCred;

	private $dbConnection;

	public function __construct() {

        $this->databaseCred = new DatabaseCred();

        $this->dbConnection = mysqli_connect($this->databaseCred->host, $this->databaseCred->username, $this->databaseCred->password, $this->databaseCred->databaseName);

        if(!$this->dbConnection) {

            die('Connectionfailure5: ' . mysql_error());
        }
    }

    //Hämtaranvändare i databasen
    public function getUser($username) {

        if (mysqli_connect_errno())
        {   
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "SELECT * FROM `users` WHERE `Username` = '$username'";
        $result = mysqli_query($this->dbConnection, $query);

        if(mysqli_num_rows($result) == 1) {

            $resultArray = mysqli_fetch_assoc($result);

            return $resultArray;

        } else {
            return false;
        }

        $this->dbConnection->close();
    }

    //Adderar användare till daabasen
    public function addMember($postedRegCred) {

        $username = $postedRegCred->username;
        $password = $postedRegCred->password;
        $userRole = $postedRegCred->userRole;
        $teacherPassword = $postedRegCred->teacherPassword;
        $teacherPasswordEncryoted = md5($teacherPassword);

        $encryptedPassword = md5($password);

        if($userRole == 1) {
            if($teacherPasswordEncryoted == "15b00b58d6355adab79defd050343357") {  

                $query = "SELECT * FROM `users` WHERE `Username` = '$username'";

                $sqlQuery = mysqli_query($this->dbConnection, $query);

                if (mysqli_num_rows($sqlQuery) > 0) {

                    throw new Exception("Användarnamnet är upptaget");

                } else {

                    $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO users
                                                                    (Username, Password, Role)
                                                                    VALUES ('$username', '$encryptedPassword', $userRole)") or die(mysqli_error($this->dbConnection));

                    $this->dbConnection->close();

                }
            } else {
                throw new Exception("Du måste ha ett giltigt lärar-lösenord för att kunna registrera dig som lärare. Du ska ha fått detta på din e-post. Om inte, fråga din admin.");
            }
        } else {
            
            $query = "SELECT * FROM `users` WHERE `Username` = '$username'";

                $sqlQuery = mysqli_query($this->dbConnection, $query);

                if (mysqli_num_rows($sqlQuery) > 0) {

                    throw new Exception("Användarnamnet är upptaget");

                } else {

                    $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO users
                                                                    (Username, Password, Role)
                                                                    VALUES ('$username', '$encryptedPassword', $userRole)") or die(mysqli_error($this->dbConnection));

                    $this->dbConnection->close();

                }
        }
    }

    //Hämtar alla studenters användarnamn
    public function getStudentsNames() {

        $query = "SELECT `User_Id`, `Username` FROM `users` WHERE `Role` = 2";
        $result = mysqli_query($this->dbConnection, $query);

        $this->dbConnection->close();

        $storeArray = array();

        while($row = mysqli_fetch_assoc($result)) {
            $storeArray[$row['User_Id']] = $row['Username'];
        }

        return $storeArray;
    }
}