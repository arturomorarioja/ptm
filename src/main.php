<?php
    include("classes.php");

    $oCustomer = new Customer();
    $oCountry = new Country();
    $oProject = new Project();
    $oRegTime = new RegTime();

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
    } elseif(isset($_POST['txtProjectID'])){
        // Insert project
        if($_POST['txtProjectID'] == "NEW"){
            echo json_encode($oProject->bInsert($_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
        } else {    // Update project
            echo json_encode($oProject->bUpdate($_POST['txtProjectID'], $_POST['cmbCustomers'], 
                $_POST['txtName'], $_POST['txtStartDate'], $_POST['txtEndDate'], $_POST['txtComments']));
        }
    } elseif(isset($_POST['txtRegTimeID'])){
        // Insert registered time
        if($_POST['txtRegTimeID'] == "NEW"){
            echo json_encode($oRegTime->bInsert($_POST['cmbProjects'], 
                $_POST['txtRegDate'], $_POST['txtRegTime'], $_POST['txtComments']));
        } else {    // Update registered time
            echo json_encode($oRegTime->bUpdate($_POST['txtRegTimeID'], $_POST['cmbProjects'], 
                $_POST['txtRegDate'], $_POST['txtRegTime'], $_POST['txtComments']));
        }
    } elseif(isset($_POST['cElement'])){
        switch($_POST['cElement']){
            case "Customers":               // Get all customers
                echo json_encode($oCustomer->aAllCustomers()); break;
            case "Countries":               // Get all countries
                echo json_encode($oCountry->aAllCountries()); break;
            case "Customer-Get":            // Get the data of a specific customer
                echo json_encode($oCustomer->aCustomer($_POST['nCustomerID'])); break;
            case "Customer-Delete":         // Delete a specific customer
                echo json_encode($oCustomer->nDelete($_POST['nCustomerID'])); break;
            case "Projects":                // Get all projects
                echo json_encode($oProject->aAllProjects()); break;
            case "Project-Get":             // Get the data of a specific project 
                echo json_encode($oProject->aProject($_POST['nProjectID'])); break;
            case "Project-Delete":          // Delete a specific project
                echo json_encode($oProject->nDelete($_POST['nProjectID'])); break;
            case "Customer-Projects-Get":   // Get the projects of a specific customer
                echo json_encode($oProject->aProjectsByCustomer($_POST['nCustomerID'])); break;
            case "RegTimes-Get":            // Get the registered times of a specific customer or project
                echo json_encode($oRegTime->aRegTimes($_POST['nValueID'], $_POST['bIsCustomer'])); break; 
            case "RegTime-Get":             // Get the data of a specific registered time
                echo json_encode($oRegTime->aRegTime($_POST['nRegTimeID'])); break;
            case "RegTime-Delete":          // Delete a specific registered time
                echo json_encode($oRegTime->nDelete($_POST['nRegTimeID'])); break;
        }
    }
?>