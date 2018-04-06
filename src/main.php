<?php
    include("classes.php");

    $oCustomer = new Customer();
    $oCountry = new Country();

    // THIS CODE IS LITTLE ELEGANT. IT SHOULD BE IMPROVED
    
    if(isset($_POST['nCustomerID_get'])) {
        echo json_encode($oCustomer->aCustomer($_POST['nCustomerID_get']));
    } elseif(isset($_POST['txtName'])){
        echo json_encode($oCustomer->bUpdate($_POST['txtCustomerID'], $_POST['txtCVR'], $_POST['txtName'], $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], $_POST['txtEmail'], $_POST['cmbCountries']));
        echo "<script>window.history.go(-1);</script>";
    } elseif(isset($_POST['cElement'])){
        echo json_encode($oCountry->aAllCountries());
    } else {
        echo json_encode($oCustomer->aAllCustomers());
    }
?>