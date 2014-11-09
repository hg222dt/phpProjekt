<?php

class UserDAL {

	private $dbConnection;

	public function __construct() {

        $this->dbConnection = mysqli_connect("localhost", "root", "root", "quizzgamez");

        if(!$this->dbConnection) {

            die('Connectionfailure5: ' . mysql_error());
        }
    }

    public function doesUsernameExist() {

    }

    public function addUser() {

    }


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



    public function addMember($postedRegCred) {

        $username = $postedRegCred->username;
        $password = $postedRegCred->password;
        $userRole = $postedRegCred->userRole;

        $query = "SELECT * FROM `users` WHERE `Username` = '$username'";

        $sqlQuery = mysqli_query($this->dbConnection, $query);

        if (mysqli_num_rows($sqlQuery) > 0) {

            throw new Exception("Användarnamnet är upptaget");

        } else {

            $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO Users
                                                            (Username, Password, Role)
                                                            VALUES ('$username', '$password', $userRole)");

            $this->dbConnection->close();

        }
    }

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