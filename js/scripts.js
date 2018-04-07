/*
    Loads all customers

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetAllCustomers(){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Customers"},
        success: function(data){
            var CUSTOMER_ID = 0;
            var CVR = 1;
            var NAME = 2;
            var TOWN = 3;
            var COUNTRY = 4;
            var acCustomers = JSON.parse(data);
            var cCustomers = "";

            $.each(acCustomers, function(nIndex, xValue) {
                cModal = " data-toggle='modal' data-target='#modalCustomers'"
                    + " onClick='FGetCustomer(" + xValue[CUSTOMER_ID] + ")'";
                cCustomers += ("<tr><td" + cModal + ">" 
                    + xValue[CVR] + "</td><td" + cModal + ">" 
                    + xValue[NAME] + "</td><td" + cModal + ">" 
                    + xValue[TOWN] + "</td><td" + cModal + ">" 
                    + xValue[COUNTRY] + "</td><td class='td-del'>"
                    + "<img src='img/delete.png' class='btn-del'" 
                    + " onClick='FDeleteCustomer(" + xValue[CUSTOMER_ID] + ")' /></td></tr>");
            });
            document.getElementById("content").innerHTML = cCustomers;
        }
    });
}

/*
    Loads all customers in the customers comboBoxes in projects.htm

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetAllCustomersToCmb(){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Customers"},
        success: function(data){
            var CUSTOMER_ID = 0;
            var NAME = 2;
            var acCustomers = JSON.parse(data);
            var cCustomers = "";

            $.each(acCustomers, function(nIndex, xValue) {
                cCustomers += "<option value='" + xValue[CUSTOMER_ID] + 
                    "'>" + xValue[NAME] + "</option>";
            });

            document.getElementById("cmbCustomers").innerHTML = cCustomers;

            // The search combo needs an empty element in its first position
            cCustomers = "<option value='0'>&lt;All Customers&gt;</option>" + cCustomers;
            document.getElementById("cmbCustomerList").innerHTML = cCustomers;
        }
    });
}

/*
    Calls the php code that retrieves data corresponding to the customer whose PK receives as a parameter

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetCustomer(pnCustomerID){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Customer-Get", nCustomerID: pnCustomerID},
        success: function(data){
            var CVR = 0;
            var NAME = 1;
            var ADDRESS = 2;
            var POST_CODE = 3;
            var TOWN = 4;
            var PHONE = 5;
            var EMAIL = 6;
            var COUNTRY_ID = 7;
            var acCustomer = JSON.parse(data);
            document.getElementById("modalCaption").innerHTML = "Update Customer";
            document.getElementById("txtCustomerID").value = pnCustomerID;
            document.getElementById("txtCVR").value = acCustomer[CVR];
            document.getElementById("txtName").value = acCustomer[NAME];
            document.getElementById("txtAddress").value = acCustomer[ADDRESS];
            document.getElementById("txtPostCode").value = acCustomer[POST_CODE];
            document.getElementById("txtTown").value = acCustomer[TOWN];
            document.getElementById("txtPhone").value = acCustomer[PHONE];
            document.getElementById("txtEmail").value = acCustomer[EMAIL];
            document.getElementById("cmbCountries").value = acCustomer[COUNTRY_ID];
        }
    });
}

/*
    Initialises the customer modal

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FCustomerInit(){
    document.getElementById("modalCaption").innerHTML = "New Customer";
    document.getElementById("txtCustomerID").value = "NEW";
    document.getElementById("txtCVR").value = "";
    document.getElementById("txtName").value = "";
    document.getElementById("txtAddress").value = "";
    document.getElementById("txtPostCode").value = "";
    document.getElementById("txtPhone").value = "";
    document.getElementById("txtTown").value = "";
    document.getElementById("txtPhone").value = "";
    document.getElementById("txtEmail").value = "";
    document.getElementById("cmbCountries").value = "DNK";
}

/*
    Deletes the customer whose PK receives as a parameter

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FDeleteCustomer(pnCustomerID){
    if(window.confirm("The customer will be deleted. Are you sure?"))
        $.ajax({
            url: "src/main.php",
            type: "POST",
            data: {cElement: "Customer-Delete", nCustomerID: pnCustomerID},
            success: function(data){
                var RET_ERROR = 0;
                var RET_OK = 1;
                var RET_REFERENTIAL_INTEGRITY = 2;
                var nResult = JSON.parse(data);

                switch(nResult){
                    case RET_ERROR:
                        alert("The customer could not be deleted"); break;
                    case RET_OK:
                        location.reload(); break;
                    case RET_REFERENTIAL_INTEGRITY:
                        alert("The customer cannot be deleted, because it has projects assigned."); break;
                } 
                alert(result);
            }
        });
}

/*
    Loads all countries in the country comboBox

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetAllCountriesToCmb(){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Countries"},
        success: function(data){
            var COUNTRY_ID = 0;
            var COUNTRY_NAME = 1;
            var acCountries = JSON.parse(data);
            var cCountries = "";

            $.each(acCountries, function(nIndex, xValue) {
                cCountries += "<option value='" + xValue[COUNTRY_ID] + "'>" + xValue[COUNTRY_NAME] + "</option>";
            });

            document.getElementById("cmbCountries").innerHTML = cCountries;
        }
    });
}

/*
    Initialises the project modal

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FProjectInit(){
    document.getElementById("modalCaption").innerHTML = "New Project";
    document.getElementById("txtProjectID").value = "NEW";
    document.getElementById("txtName").value = "";
    document.getElementById("cmbCustomers").selectedIndex = 0;
    document.getElementById("txtStartDate").value = "";
    document.getElementById("txtEndDate").value = "";
    document.getElementById("txtComments").value = "";
}

/*
    Loads all projects

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetAllProjects(){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Projects"},
        success: function(data){
            var PROJECT_ID = 0;
            var CUSTOMER_NAME = 1;
            var NAME = 2;
            var START_DATE = 3;
            var END_DATE = 4;
            var acProjects = JSON.parse(data);
            var cProjects = "";

            $.each(acProjects, function(nIndex, xValue) {
                cModal = " data-toggle='modal' data-target='#modalProjects'"
                    + " onClick='FGetProject(" + xValue[PROJECT_ID] + ")'";
                
                cProjects += ("<tr id='" + xValue[CUSTOMER_NAME] + "'><td" + cModal + ">" 
                    + xValue[CUSTOMER_NAME] + "</td><td" + cModal + ">" 
                    + xValue[NAME] + "</td><td" + cModal + ">" 
                    + xValue[START_DATE] + "</td><td" + cModal + ">" 
                    + (xValue[END_DATE] === null ? "ongoing" : xValue[END_DATE]) + "</td><td class='td-del'>"
                    + "<img src='img/delete.png' class='btn-del'" 
                    + " onClick='FDeleteProject(" + xValue[PROJECT_ID] + ")' /></td></tr>");
            });
            document.getElementById("content").innerHTML = cProjects;
        }
    });
}

/*
    Filters the projects on the page table according to the selected customer

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FFilterProjects(){
    nIndex = document.getElementById("cmbCustomerList").selectedIndex;
    cCustomer = document.getElementById("cmbCustomerList").options[nIndex].innerHTML;

    aTrs = document.getElementsByTagName("tr");
    
    Array.prototype.forEach.call(aTrs, function(trs) {
        // If the "<All Customers>" option is selected, 
        // or the customer in the table row is the selected customer,
        // or the selected row has no id (it would be the header row),
        // the row will be displayed. Otherwise it will be hidden
        if(cCustomer == "&lt;All Customers&gt;" || trs.id == cCustomer || trs.id == "")
            trs.style.display = "table-row";
        else
            trs.style.display = "none";
    });
}

/*
    Calls the php code that retrieves data corresponding to the customer whose PK receives as a parameter

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetProject(pnProjectID){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Project-Get", nProjectID: pnProjectID},
        success: function(data){
            var CUSTOMER_ID = 0;
            var NAME = 1;
            var START_DATE = 2;
            var END_DATE = 3;
            var COMMENTS = 4;
            var acProject = JSON.parse(data);
            document.getElementById("modalCaption").innerHTML = "Update Project";
            document.getElementById("txtProjectID").value = pnProjectID;
            document.getElementById("cmbCustomers").value = acProject[CUSTOMER_ID];
            document.getElementById("txtName").value = acProject[NAME];
            document.getElementById("txtStartDate").value = acProject[START_DATE];
            document.getElementById("txtEndDate").value = acProject[END_DATE];
            document.getElementById("txtComments").value = acProject[COMMENTS];
        }
    });
}

/*
    Deletes the project whose PK receives as a parameter

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FDeleteProject(pnProjectID){
    if(window.confirm("The project will be deleted. Are you sure?"))
        $.ajax({
            url: "src/main.php",
            type: "POST",
            data: {cElement: "Project-Delete", nProjectID: pnProjectID},
            success: function(data){
                var RET_ERROR = 0;
                var RET_OK = 1;
                var RET_REFERENTIAL_INTEGRITY = 2;
                var nResult = JSON.parse(data);

                switch(nResult){
                    case RET_ERROR:
                        alert("The project could not be deleted"); break;
                    case RET_OK:
                        location.reload(); break;
                    case RET_REFERENTIAL_INTEGRITY:
                        alert("The project cannot be deleted, because it has registered times assigned."); break;
                } 
                alert(result);
            }
        });
}
