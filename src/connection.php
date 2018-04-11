<?php
/**
 * Encapsulates a connection to the ptm database 
 * 
 * @author Arturo Mora-Rioja
 * @date   6/4/2018
 */
    class DB {

    /**
     * Opens a connection to the ptm database
     * 
     * @returns a connection object
     */
    public function dbConnect() {
            $cServer = "localhost";
            $cDB = "ptm";
            $cUser = "ptm_user";
            $cPwd = "ptm_pwd";

            $cnDB = @new mysqli($cServer, $cUser, $cPwd, $cDB); 
            if ($cnDB->connect_error) {
                die("Connection unsuccessful: " . $cnDB->connect_error());
                exit();
            } else 
                return($cnDB);   
        }

    /**
     * Closes a connection to the ptm database
     * 
     * @param the connection object to disconnect
     */
    public function bDisconnect($pcnDB) {
            return(mysqli_close($pcnDB));
        }
    }
?>