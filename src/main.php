<?php
    /**
     * This file serves as an entry point to the server code.
     * A series of POST values inform about which PHP class and method to invoke.
     * 
     * @author Arturo Mora-Rioja
     * @date   7/4/2018 
     */
    include("classes.php");

    $oCustomer = new Customer();
    $oCountry = new Country();
    $oProject = new Project();
    $oRegTime = new RegTime();

    // The call comes from the customer form
    if(isset($_POST['txtCustomerID'])){
        // Insert customer
        if($_POST['txtCustomerID'] == "NEW"){
            echo json_encode($oCustomer->bInsert($_POST['txtCVR'], $_POST['txtName'], 
                $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], 
                $_POST['txtEmail'], $_POST['cmbCountries']));
        } else {    // Update customer
            echo json_encode($oCustomer->bUpdate($_POST['txtCustomerID'], $_POST['txtCVR'], $_POST['txtName'], 
                $_POST['txtAddress'], $_POST['txtPostCode'], $_POST['txtTown'], $_POST['txtPhone'], 
                $_POST['txtEmail'], $_POST['cmbCountries']));
        }
    // The call comes from the project form
    } elseif(isset($_POST['txtProjectID'])){
        // Insert project
        if($_POST['txtProjectID'] == "NEW"){
            echo json_encode($oProject->bInsert($_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
        } else {    // Update project
            echo json_encode($oProject->bUpdate($_POST['txtProjectID'], $_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
        }
    // The call comes from the registered time form
    } elseif(isset($_POST['txtRegTimeID'])){
        // Insert registered time
        if($_POST['txtRegTimeID'] == "NEW"){
            echo json_encode($oRegTime->bInsert($_POST['cmbProjects'], 
                $_POST['txtRegDate'], $_POST['txtRegTime'], $_POST['txtComments']));
        } else {    // Update registered time
            echo json_encode($oRegTime->bUpdate($_POST['txtRegTimeID'], $_POST['cmbProjects'], 
                $_POST['txtRegDate'], $_POST['txtRegTime'], $_POST['txtComments']));
        }
    // The call comes from a JavaScript function
    } elseif(isset($_POST['cElement'])){
        switch($_POST['cElement']){
            case "Customers":               // Get all customers
                echo json_encode($oCustomer->aSelectAll()); break;
            case "Countries":               // Get all countries
                echo json_encode($oCountry->aSelectAll()); break;
            case "Customer-Get":            // Get the data of a specific customer
                echo json_encode($oCustomer->aSelect($_POST['nCustomerID'])); break;
            case "Customer-Delete":         // Delete a specific customer
                echo json_encode($oCustomer->nDelete($_POST['nCustomerID'])); break;
            case "Projects":                // Get all projects
                echo json_encode($oProject->aSelectAll()); break;
            case "Project-Get":             // Get the data of a specific project 
                echo json_encode($oProject->aSelect($_POST['nProjectID'])); break;
            case "Project-Delete":          // Delete a specific project
                echo json_encode($oProject->nDelete($_POST['nProjectID'])); break;
            case "Customer-Projects-Get":   // Get the projects of a specific customer
                echo json_encode($oProject->aSelectByCustomer($_POST['nCustomerID'])); break;
            case "RegTimes-Get":            // Get the registered times of a specific customer or project
                echo json_encode($oRegTime->aSelectByCustomerOrProject($_POST['nValueID'], $_POST['bIsCustomer'])); break; 
            case "RegTime-Get":             // Get the data of a specific registered time
                echo json_encode($oRegTime->aSelect($_POST['nRegTimeID'])); break;
            case "RegTime-Delete":          // Delete a specific registered time
                echo json_encode($oRegTime->nDelete($_POST['nRegTimeID'])); break;
        }
    }
?>