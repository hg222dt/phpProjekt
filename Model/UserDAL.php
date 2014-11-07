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














    public function setMemberCredentialsInDB($firstname, $lastname, $personalnumber) {

        $sqlQuery = mysqli_query($this->dbConnection, "SELECT SocialSecurityNO
                                                        FROM register
                                                        WHERE SocialSecurityNO = '$personalnumber'");

        if (mysqli_num_rows($sqlQuery) > 0) {

            return false;

        } else {

            $sqlInsert = mysqli_query($this->dbConnection, "INSERT INTO register
                                                            (firstName, lastName, socialSecurityNO)
                                                            VALUES ('$firstname', '$lastname', '$personalnumber')");

            $this->dbConnection->close();

           if($sqlInsert) {

                return true;

            } else {

                return false;
            } 
        }
    }

    public function getMemberByPersonalNumberFromDB($personalnumber) {

        $result = mysqli_query($this->dbConnection, "SELECT *
                                                      FROM register
                                                      WHERE socialSecurityNumber = '$personalnumber'");

        $this->dbConnection->close();

        if(mysqli_num_rows($result) == 1) {
 
            return true;

        } else {

            return false;

        }
    }

    public function getMemberCredentialsFromDB($firstName, $lastName, $personalnumber) {

        $sqlQuery = mysqli_query($this->dbConnection, "SELECT memberID, firstName
                                                      , lastName, socialSecurityNumber
                                                      FROM register
                                                      WHERE socialSecurityNumber = '$personalnumber'");

        $this->dbConnection->close();

        if(mysqli_num_rows($sqlQuery) == 1) {
 
            return true;

        } else {

            return false;

        }

    }
}