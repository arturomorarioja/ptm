<?php
    include("classes.php");

    $oCustomer = new Customer();
    $oCountry = new Country();
    $oProject = new Project();

    if(isset($_POST['txtCustomerID'])){
        // Insert customer
        if($_POST['txtCustomerID'] == "NEW"){
            echo json_encode($oCustomer->bInsert($_POST['txtCVR'], $_POST['txtName'], 
                $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], 
                $_POST['txtEmail'], $_POST['cmbCountries']));
            echo "<script>window.history.go(-1);</script>";
        } else {    // Update customer
            echo json_encode($oCustomer->bUpdate($_POST['txtCustomerID'], $_POST['txtCVR'], $_POST['txtName'], 
                $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], 
                $_POST['txtEmail'], $_POST['cmbCountries']));
            echo "<script>window.history.go(-1);</script>";
        }
    } elseif(isset($_POST['txtProjectID'])){
        // Insert project
        if($_POST['txtProjectID'] == "NEW"){
            echo json_encode($oProject->bInsert($_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
            echo "<script>window.history.go(-1);</script>";
        } else {    // Update project
            echo json_encode($oProject->bUpdate($_POST['txtProjectID'], $_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
            echo "<script>window.history.go(-1);</script>";
        }
    } elseif(isset($_POST['cElement'])){
        switch($_POST['cElement']){
            case "Customers":       // Get all customers
                echo json_encode($oCustomer->aAllCustomers()); break;
            case "Countries":       // Get all countries
                echo json_encode($oCountry->aAllCountries()); break;
            case "Customer-Get":    // Get the data of a specific customer
                echo json_encode($oCustomer->aCustomer($_POST['nCustomerID'])); break;
            case "Customer-Delete": // Delete a specific customer
                echo json_encode($oCustomer->nDelete($_POST['nCustomerID'])); break;
            case "Projects":        // Get all projects
                echo json_encode($oProject->aAllProjects()); break;
            case "Project-Get":     // Get the data of a specific project 
                echo json_encode($oProject->aProject($_POST['nProjectID'])); break;
            case "Project-Delete":  // Delete a specific project
                echo json_encode($oProject->nDelete($_POST['nProjectID'])); break;
        }
    }
?>