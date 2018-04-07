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

        // Updates all values of the customer whose PK receives as first parameter
        function bUpdate($pnCustomerID, $pcCVR, $pcName, $pcAddress, $pcPostCode, $pcTown, $pcPhone, $pcEmail, $pcCountryID){
            $cnDB = $this->dbConnect();
            if(!$cnDB)
                return false;
            else {
                $cSQL = "UPDATE customer";
                $cSQL .= " SET cCVR = '" . $pcCVR . "'";
                $cSQL .= ", cName = '" . $pcName . "'";
                $cSQL .= ", cAddress = '" . $pcAddress . "'";
                $cSQL .= ", cPostCode = '" . $pcPostCode . "'";
                $cSQL .= ", cTown = '" . $pcTown . "'";
                $cSQL .= ", cPhone = '" . $pcPhone . "'";
                $cSQL .= ", cEmail = '" . $pcEmail . "'";
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
?>