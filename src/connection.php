<?php
    /*
        It represents a connection to the ptm database

        Author: Arturo Mora-Rioja
        Date: 6/4/2018
    */
    class DB {
        public function dbConnect() {
            $cServer = "localhost";
            $cDB = "ptm";
            $cUser = "ptm_user";
            $cPwd = "reviso";

            $cnDB = @new mysqli($cServer, $cUser, $cPwd, $cDB); 
            if ($cnDB->connect_error) {
                die("Connection unsuccessful: " . $cnDB->connect_error());
                exit();
            } else 
                return($cnDB);   
        }

        public function bDisconnect($pcnDB) {
            return(mysqli_close($pcnDB));
        }
    }
?>