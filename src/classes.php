<?php
    include("connection.php");

    /*
        Encapsulates a customer. Takes care of the corresponding queries, inserts, updates, and deletes

        Author: Arturo Mora-Rioja
        Date: 6/4/2018
    */
    class Customer extends DB {

        // Returns a bidimensional array with the data of all customers
        function aAllCustomers(){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $aResults = array();

                $cSQL = "SELECT customer.nCustomerID, customer.cCVR, customer.cName, customer.cTown, country.cName";
                $cSQL .= " FROM customer INNER JOIN country ON customer.cCountryID = country.cCountryID";

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }

        // Returns an array with the data of the customer whose PK receives as a parameter
        function aCustomer($pnCustomerID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "SELECT cCVR, cName, cAddress, cPostCode, cTown, cPhone, cEmail, cCountryID";
                $cSQL .= " FROM customer";
                $cSQL .= " WHERE nCustomerID = " . $pnCustomerID;

                $aQuery = $cnDB->query($cSQL);
                $aResults = $aQuery->fetch_row();                
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }

        // Inserts a new customer
        function bInsert($pcCVR, $pcName, $pcAddress, $pcPostCode, $pcTown, $pcPhone, $pcEmail, $pcCountryID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "INSERT INTO customer";
                $cSQL .= " (cCVR, cName, cAddress, cPostCode, cTown, cPhone, cEmail, cCountryID) VALUES (";
                $cSQL .= "'" . $cnDB->real_escape_string($pcCVR) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcName) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcAddress) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcPostCode) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcTown) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcPhone) . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcEmail) . "', ";
                $cSQL .= "'" . $pcCountryID . "')";

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($bOk);
            }
        }
        
        // Updates all values of the customer whose PK receives as first parameter
        function bUpdate($pnCustomerID, $pcCVR, $pcName, $pcAddress, $pcPostCode, $pcTown, $pcPhone, $pcEmail, $pcCountryID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "UPDATE customer";
                $cSQL .= " SET cCVR = '" . $cnDB->real_escape_string($pcCVR) . "'";
                $cSQL .= ", cName = '" . $cnDB->real_escape_string($pcName) . "'";
                $cSQL .= ", cAddress = '" . $cnDB->real_escape_string($pcAddress) . "'";
                $cSQL .= ", cPostCode = '" . $cnDB->real_escape_string($pcPostCode) . "'";
                $cSQL .= ", cTown = '" . $cnDB->real_escape_string($pcTown) . "'";
                $cSQL .= ", cPhone = '" . $cnDB->real_escape_string($pcPhone) . "'";
                $cSQL .= ", cEmail = '" . $cnDB->real_escape_string($pcEmail) . "'";
                $cSQL .= ", cCountryID = '" . $pcCountryID . "'";
                $cSQL .= " WHERE nCustomerID = " . $pnCustomerID;

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($bOk);
            }
        }

        // Deletes the customer whose PK receives as a parameter. Return values:
        // 0 Error / 1 Success / 2 Referential integrity problem
        function nDelete($pnCustomerID){
            define("RET_ERROR", 0);
            define("RET_OK", 1);
            define("RET_REFERENTIAL_INTEGRITY", 2);

            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                // If the customer has projects, it will not be deleted
                $cSQL = "SELECT nProjectID FROM project";
                $cSQL .= " WHERE nCustomerID = " . $pnCustomerID;

                $aQuery = $cnDB->query($cSQL);
                if($aQuery->num_rows > 0)
                    $nResult = RET_REFERENTIAL_INTEGRITY;                
                else {
                    $cSQL = "DELETE FROM customer";
                    $cSQL .= " WHERE nCustomerID = " . $pnCustomerID;

                    if($cnDB->query($cSQL))
                        $nResult = RET_OK;
                    else
                        $nResult = RET_ERROR;                
                }
                $this->bDisconnect($cnDB);
                return($nResult);
            }
        }
    }

    /*
        Encapsulates the countries. Allows their querying

        Author: Arturo Mora-Rioja
        Date: 7/4/2018
    */
    class Country extends DB {
        // Returns a bidimensional array with the data of all countries
        function aAllCountries(){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $aResults = array();

                $cSQL = "SELECT cCountryID, cName";
                $cSQL .= " FROM country";

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }        
    }

    /*
        Encapsulates the projects

        Author: Arturo Mora-Rioja
        Date: 7/4/2018
    */
    class Project extends DB {
        // Returns a bidimensional array with the data of all customers
        function aAllProjects(){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $aResults = array();

                $cSQL = "SELECT project.nProjectID, customer.cName, project.cName, project.dStart, project.dEnd";
                $cSQL .= " FROM project INNER JOIN customer ON project.nCustomerID = customer.nCustomerID";

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }      

        // Returns an array with the projects of the customer whose PK receives as a parameter
        function aProjectsByCustomer($pnCustomerID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $aResults = array();

                $cSQL = "SELECT nProjectID, cName";
                $cSQL .= " FROM project";
                $cSQL .= " WHERE nCustomerID = " . $pnCustomerID;

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }      

        // Returns an array with the data of the project whose PK receives as a parameter
        function aProject($pnProjectID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "SELECT nCustomerID, cName, dStart, dEnd, tComments";
                $cSQL .= " FROM project";
                $cSQL .= " WHERE nProjectID = " . $pnProjectID;

                $aQuery = $cnDB->query($cSQL);
                $aResults = $aQuery->fetch_row();                
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }

        // Inserts a new project
        function bInsert($pnCustomerID, $pcName, $pdStart, $pdEnd, $pcComments){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "INSERT INTO project";
                $cSQL .= " (nCustomerID, cName, dStart, dEnd, tComments) VALUES (";
                $cSQL .= $pnCustomerID . ", ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcName) . "', ";
                $cSQL .= "'" . $pdStart . "', ";
                $cSQL .= ($pdEnd == "" ? "null" : "'" . $pdEnd . "'") . ", ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcComments) . "')";

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($bOk);
            }
        }
        
        // Updates all values of the project whose PK receives as first parameter
        function bUpdate($pnProjectID, $pnCustomerID, $pcName, $pdStart, $pdEnd, $pcComments){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "UPDATE project";
                $cSQL .= " SET nCustomerID = " . $pnCustomerID;
                $cSQL .= ", cName = '" . $cnDB->real_escape_string($pcName) . "'";
                $cSQL .= ", dStart = '" . $pdStart . "'";
                $cSQL .= ", dEnd = " . ($pdEnd == "" ? "null" : "'" . $pdEnd . "'");
                $cSQL .= ", tComments = '" . $cnDB->real_escape_string($pcComments) . "'";
                $cSQL .= " WHERE nProjectID = " . $pnProjectID;

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($Ok);
            }
        }

        // Deletes the project whose PK receives as a parameter. Return values:
        // 0 Error / 1 Success / 2 Referential integrity problem
        function nDelete($pnProjectID){
            define("RET_ERROR", 0);
            define("RET_OK", 1);
            define("RET_REFERENTIAL_INTEGRITY", 2);

            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                // If the project has registered times, it will not be deleted
                $cSQL = "SELECT nRegTimeID FROM reg_time";
                $cSQL .= " WHERE nProjectID = " . $pnProjectID;

                $aQuery = $cnDB->query($cSQL);
                if($aQuery->num_rows > 0)
                    $nResult = RET_REFERENTIAL_INTEGRITY;                
                else {
                    $cSQL = "DELETE FROM project";
                    $cSQL .= " WHERE nProjectID = " . $pnProjectID;

                    if($cnDB->query($cSQL))
                        $nResult = RET_OK;
                    else
                        $nResult = RET_ERROR;                
                }
                $this->bDisconnect($cnDB);
                return($nResult);
            }
        }        
    }

    /*
        Encapsulates the registered times

        Author: Arturo Mora-Rioja
        Date: 7/4/2018
    */
    class RegTime extends DB {
        // Returns a bidimensional array with the data of all registered times of the customer or project
        // whose PK it receives as first parameter. 
        // The second parameter specifies whether the first parameter refers to a customer (true) or project (false)
        function aRegTimes($pnValueID, $pbIsCustomer){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $aResults = array();

                $cSQL = "SELECT reg_time.nRegTimeID, project.cName, reg_time.dRegistration, reg_time.dTime, reg_time.tComments";
                $cSQL .= " FROM reg_time INNER JOIN project";
                $cSQL .= "  ON reg_time.nProjectID = project.nProjectID";
                if($pbIsCustomer == "true")
                    $cSQL .= " WHERE project.nCustomerID = " . $pnValueID;
                else
                    $cSQL .= " WHERE reg_time.nProjectID = " . $pnValueID;

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }
        
        // Returns an array with the data of the registered time whose PK receives as a parameter
        function aRegTime($pnRegTimeID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "SELECT project.nCustomerID, reg_time.nProjectID, reg_time.dRegistration";
                $cSQL .= ", reg_time.dTime, reg_time.tComments";
                $cSQL .= " FROM reg_time INNER JOIN project";
                $cSQL .= "  ON reg_time.nProjectID = project.nProjectID";
                $cSQL .= " WHERE reg_time.nRegTimeID = " . $pnRegTimeID;

                $aQuery = $cnDB->query($cSQL);
                $aResults = $aQuery->fetch_row();                
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }

        // Inserts a new registered time
        function bInsert($pnProjectID, $pdRegistration, $pdRegTime, $pcComments){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "INSERT INTO reg_time";
                $cSQL .= " (nProjectID, dRegistration, dTime, tComments) VALUES (";
                $cSQL .= $pnProjectID . ", ";
                $cSQL .= "'" . $pdRegistration . "', ";
                $cSQL .= "'" . $pdRegTime . "', ";
                $cSQL .= "'" . $cnDB->real_escape_string($pcComments) . "')";

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($bOk);
            }
        }

        // Updates all values of the registered time whose PK receives as first parameter
        function bUpdate($pnRegTimeID, $pnProjectID, $pdRegDate, $pdTime, $pcComments){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "UPDATE reg_time";
                $cSQL .= " SET nProjectID = " . $pnProjectID;
                $cSQL .= ", dRegistration = '" . $pdRegDate . "'";
                $cSQL .= ", dTime = '" . $pdTime . "'";
                $cSQL .= ", tComments = '" . $cnDB->real_escape_string($pcComments) . "'";
                $cSQL .= " WHERE nRegTimeID = " . $pnRegTimeID;

                $bOk = $cnDB->query($cSQL);                
                $this->bDisconnect($cnDB);

                return($cSQL);
            }
        }

        // Deletes the registered time whose PK receives as a parameter. Return values:
        // 0 Error / 1 Success 
        function nDelete($pnRegTimeID){
            define("RET_ERROR", 0);
            define("RET_OK", 1);

            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "DELETE FROM reg_time";
                $cSQL .= " WHERE nRegTimeID = " . $pnRegTimeID;

                if($cnDB->query($cSQL))
                    $nResult = RET_OK;
                else
                    $nResult = RET_ERROR;                

                $this->bDisconnect($cnDB);
                return($nResult);
            }
        }        
    }      
?>