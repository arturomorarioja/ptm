<?php
    include("classes.php");

    $oCustomer = new Customer();
    $oCountry = new Country();

    if(isset($_POST['txtCustomerID'])){
        if($_POST['txtCustomerID'] == "NEW"){
            alert("New customer");
            echo "<script>window.history.go(-1);</script>";
        } else {
            echo json_encode($oCustomer->bUpdate($_POST['txtCustomerID'], $_POST['txtCVR'], $_POST['txtName'], 
                $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], 
                $_POST['txtEmail'], $_POST['cmbCountries']));
            echo "<script>window.history.go(-1);</script>";
        }
    } elseif(isset($_POST['cElement'])){
        switch($_POST['cElement']){
            case "Customers":
                echo json_encode($oCustomer->aAllCustomers()); break;
            case "Countries":
                echo json_encode($oCountry->aAllCountries()); break;
            case "Customer-Get":
                echo json_encode($oCustomer->aCustomer($_POST['nCustomerID'])); break;
            case "Customer-Delete":
                echo json_encode($oCustomer->nDelete($_POST['nCustomerID'])); break;
        }
    }
?>