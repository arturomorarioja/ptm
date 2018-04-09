<?php
    include("connection.php");

    /**
     * Encapsulates the customers. 
     * Takes care of the corresponding queries, inserts, updates, and deletes
     *
     * @author Arturo Mora-Rioja
     * @date   6/4/2018
     */
    class Customer extends DB {

        /**
         * Gets the data of all customers
         * 
         * @return bidimensional array [customer row, column values] / false if there was any error 
         */
        function aSelectAll(){
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

        /**
         * Gets the data of a customer
         * 
         * @param  customer id
         * @return array [customer column values] / false if there was any error
         */
        function aSelect($pnCustomerID){
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

        /**
         * Inserts a new customer
         * 
         * @param  customer CVR
         * @param  customer name
         * @param  customer address
         * @param  customer postal code
         * @param  customer town
         * @param  customer phone number
         * @param  customer e-mail
         * @param  customer country id
         * @return true if the insertion was correct, false if there was an error
         */
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
        
        /**
         * Updates all the values of a customer
         * 
         * @param  id of the customer whose data will be updated
         * @param  new customer CVR
         * @param  new customer name
         * @param  new customer address
         * @param  new customer postal code
         * @param  new customer town
         * @param  new customer phone number
         * @param  new customer e-mail
         * @param  new id of the customer's country 
         * @return true if the update was correct, false if there was an error
         */
        function bUpdate($pnCustomerID, $pcCVR, $pcName, $pcAddress, $pcPostCode, $pcTown, $pcPhone, 
                $pcEmail, $pcCountryID){
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
        
        /**
         * Deletes a customer
         * 
         * @param  id of the customer to delete
         * @return 0 error / 1 success / 2 referential integrity problem
         */
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

    /**
     * Encapsulates the countries. Allows their being queried
     * 
     * @author Arturo Mora-Rioja
     * @date   7/4/2018
     */
    class Country extends DB {

        /**
         * Gets the data of all the countries
         * 
         * @return bidimensional array [country row, column values] / false if there was any error
         */
        function aSelectAll(){
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

    /**
     * Encapsulates the projects. 
     * Takes care of the corresponding queries, inserts, updates, and deletes
     *
     * @author Arturo Mora-Rioja
     * @date   7/4/2018
     */
    class Project extends DB {
        /**
         * Gets the data of all projects
         * 
         * @return bidimensional array [project row, column values] / false if there was any error 
         */
        function aSelectAll(){
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

        /**
         * Gets the projects of a customer
         * 
         * @param  id of the customer whose projects are to be retrieved
         * @return bidimensional array [project row, id and name] / false if there was any error
         */
        function aSelectByCustomer($pnCustomerID){
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

        /**
         * Gets the data of a project
         * 
         * @param  project id
         * @return array [project column values] / false if there was any error
         */
        function aSelect($pnProjectID){
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

        /**
         * Inserts a new project
         * 
         * @param  id of the project's customer
         * @param  project name
         * @param  project start date
         * @param  project end date
         * @param  project comments
         * @return true if the insertion was correct, false if there was an error
         */
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
        
        /**
         * Updates all the values of a project
         * 
         * @param  id of the project whose data will be updated
         * @param  new id of the project's customer
         * @param  new project name
         * @param  new project start date
         * @param  new project end date
         * @param  new project comments
         * @return true if the update was correct, false if there was an error
         */
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

        /**
         * Deletes a project
         * 
         * @param  id of the project to delete
         * @return 0 error / 1 success / 2 referential integrity problem
         */
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

    /**
     * Encapsulates the registered times. 
     * Takes care of the corresponding queries, inserts, updates, and deletes
     *
     * @author Arturo Mora-Rioja
     * @date   7/4/2018
     */
    class RegTime extends DB {
    
        /**
         * Gets the registered times of a customer or project
         * 
         * @param  customer id or project id (see below) 
         * @param  if true, $pnValueID is a customer id / if false, $pnValueID is a project id
         * @return bidimensional array [registered time row, column values] / false if there was any error 
         */
        function aSelectByCustomerOrProject($pnValueID, $pbIsCustomer){
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
                $cSQL .= " ORDER BY reg_time.dRegistration DESC";

                $aQuery = $cnDB->query($cSQL);
                while($rRow = $aQuery->fetch_row())
                    $aResults[] = $rRow;
                
                $aQuery->close();
                $this->bDisconnect($cnDB);

                return($aResults);
            }
        }
        
        /**
         * Gets the data of a registered time
         * 
         * @param  registered time id
         * @return array [registered time column values] / false if there was any error
         */
        function aSelect($pnRegTimeID){
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

        /**
         * Inserts a new registered time
         * 
         * @param  id of the registered time's project
         * @param  registration date
         * @param  registered time
         * @param  registered time comments
         * @return true if the insertion was correct, false if there was an error
         */
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

        /**
         * Updates all the values of a registered time
         * 
         * @param  id of the registered time whose data will be updated
         * @param  new project id of the registered time
         * @param  new registration date
         * @param  new registered time
         * @param  new registered time comment
         * @return true if the update was correct, false if there was an error
         */
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

        /**
         * Deletes a registered time
         * 
         * @param  id of the registered time to delete
         * @return 0 error / 1 success
         */
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