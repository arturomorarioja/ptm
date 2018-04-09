/**
 * Loads all customers in the customers page tbody
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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

/**
 * Loads all customers in the customers comboBox 
 * either in the projects page or in the registered time page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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

            // The search combo in reg_times.htm has a different element in its first position
            if(location.href.split("/").slice(-1) == "reg_times.htm")
                cCustomers = "<option value='0'>&lt;Select Customer&gt;</option>" + cCustomers;
            else
                cCustomers = "<option value='0'>&lt;All Customers&gt;</option>" + cCustomers;
            document.getElementById("cmbCustomerList").innerHTML = cCustomers;
        }
    });
}

/**
 * Loads the data of a customer in the modal form of the customers page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param  id of the customer whose data will be retrieved
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

/**
 * Initialises the customer modal form in the customers page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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

/**
 * Submits the customer form in the customers page
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 */
function FSubmitCustomer(){
    $("a#submit").click(function(btn){
        btn.preventDefault();   // By preventing the submit action, navigation will stay in the current page

        data = {
            txtCustomerID: $("input#txtCustomerID").val(),
            txtCVR: $("input#txtCVR").val(),
            txtName: $("input#txtName").val(),
            txtAddress: $("input#txtAddress").val(),
            txtPostCode: $("input#txtPostCode").val(),
            txtTown: $("input#txtTown").val(),
            cmbCountries: $("select#cmbCountries").val(),
            txtPhone: $("input#txtPhone").val(),
            txtEmail: $("input#txtEmail").val()
        };

        // Mandatory data check
        if(data.txtCVR == ""){ alert("CVR cannot be empty"); return; }
        if(data.txtName == ""){ alert("Name cannot be empty"); return; }
        if(data.txtAddress == ""){ alert("Address cannot be empty"); return; }
        if(data.txtPostCode == ""){ alert("Postal Code cannot be empty"); return; }
        if(data.txtTown == ""){ alert("Town cannot be empty"); return; }

        $.post("src/main.php", data, function(d){
            $("button#dismiss").click();    // The customer form is hidden
            FGetAllCustomers();             // Customers are reloaded in the table
        });
    });
}

/**
 * Deletes a customer.
 * If there is a referential integrity problem (the customer has projects), an alert will be displayed
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param  id of the customer to delete
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
            }
        });
}

/**
 * Loads all countries in the customers page's countries combo
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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

/**
 * Loads all projects in the projects page tbody
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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
            FFilterProjects();  // If a customer is selected, only its projects will be displayed
        }
    });
   
}

/**
 * Hides from the projects' page table the projects not belonging 
 * to the customer selected in the customers' combo 
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 */
function FFilterProjects(){
    nIndex = document.getElementById("cmbCustomerList").selectedIndex;
    cCustomer = document.getElementById("cmbCustomerList").options[nIndex].innerHTML;

    aTrs = document.getElementsByTagName("tr");
    
    Array.prototype.forEach.call(aTrs, function(tr) {
        // If the "<All Customers>" option is selected, 
        // or the customer in the table row is the selected customer,
        // or the selected row has no id (it would be the header row),
        // the row will be displayed. Otherwise it will be hidden
        if(cCustomer == "&lt;All Customers&gt;" || tr.id == cCustomer || tr.id == "")
            tr.style.display = "table-row";
        else
            tr.style.display = "none";
    });
}

/**
 * Loads a customer's projects in a combo
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param id of the customer whose projects will be loaded
 * @param id of the combo where the projects will be loaded
 * @param (optional) value of the project to leave as selected in the combo
 */
function FGetCustomerProjectsToCmb(pnCustomerID, pcComboId, pnSelectedValue){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "Customer-Projects-Get", nCustomerID: pnCustomerID},
        success: function(data){
            var PROJECT_ID = 0;
            var PROJECT_NAME = 1;
            var acProjects = JSON.parse(data);
            // The projects combo in the form does not include an "<All Projects>" value
            if(pcComboId == "cmbProjects")  
                cProjects = "";
            else    // The combo that filters projects for the table includes an "<All Projects>" value
                cProjects = "<option value='0'>&lt;All Projects&gt;</option>";

            $.each(acProjects, function(nIndex, xValue) {
                cProjects += "<option value='" + xValue[PROJECT_ID] + "'>"
                    + xValue[PROJECT_NAME] + "</option>";
            });
            document.getElementById(pcComboId).innerHTML = cProjects;
            
            if(pnSelectedValue != null)
                document.getElementById(pcComboId).value = pnSelectedValue;
        }
    });
}

/**
 * Loads the data of a project in the modal form of the projects page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param  id of the project whose data will be retrieved
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

/**
 * Initialises the project modal form in the projects page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
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

/**
 * Submits the project form in the projects page
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 */
function FSubmitProject(){
    $("a#submit").click(function(btn){
        btn.preventDefault();   // By preventing the submit action, navigation will stay in the current page

        data = {
            txtProjectID: $("input#txtProjectID").val(),
            txtName: $("input#txtName").val(),
            cmbCustomers: $("select#cmbCustomers").val(),
            txtStartDate: $("input#txtStartDate").val(),
            txtEndDate: $("input#txtEndDate").val(),
            txtComments: $("textarea#txtComments").val()
        };

        // Mandatory data check
        if(data.txtName == ""){ alert("Name cannot be empty"); return; }
        if(data.txtStartDate == ""){ alert("Start Date cannot be empty"); return; }

        $.post("src/main.php", data, function(d){
            $("button#dismiss").click();    // The project form is hidden   
            FGetAllProjects();              // Projects are reloaded in the table
        });
    });
}

/**
 * Deletes a project.
 * If there is a referential integrity problem (the project has registered times), an alert will be displayed
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param  id of the project to delete
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
                        FGetAllProjects(); break;   // Projects are reloaded in the table
                    case RET_REFERENTIAL_INTEGRITY:
                        alert("The project cannot be deleted, because it has registered times assigned."); break;
                } 
            }
        });
}

/**
 * Loads in the registered times page tbody the registered times for a customer or project.
 * It also displays below the table the sum of registered times.
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param id of the customer or project (see below)
 * @param if true, the 1st parameter belongs to a customer / if false, the 1st parameter belongs to a project
 */
function FLoadRegTimes(pnValueID, pbIsCustomer){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "RegTimes-Get", nValueID: pnValueID, bIsCustomer: pbIsCustomer},
        success: function(data){
            var REG_TIME_ID = 0;
            var PROJECT_NAME = 1;
            var REG_DATE = 2;
            var TIME = 3;
            var COMMENTS = 4;
            var acRegTimes = JSON.parse(data);
            var cRegTimes = "";
            var nTotal = 0;

            $.each(acRegTimes, function(nIndex, xValue) {
                cModal = " data-toggle='modal' data-target='#modalRegTimes'"
                    + " onClick='FGetRegTime(" + xValue[REG_TIME_ID] + ")'";    

                cRegTimes += ("<tr id='" + xValue[REG_TIME_ID] + "'><td" + cModal + ">" 
                    + xValue[PROJECT_NAME] + "</td><td" + cModal + ">" 
                    + xValue[REG_DATE] + "</td><td" + cModal + ">" 
                    + FcFormatTime(xValue[TIME]) + "</td><td" + cModal + ">" 
                    + xValue[COMMENTS] + "</td><td class='td-del'>"
                    + "<img src='img/delete.png' class='btn-del'" 
                    + " onClick='FDeleteRegTime(" + xValue[REG_TIME_ID] + ")' /></td></tr>");

                nTotal += FnTimeToMinutes(xValue[TIME]);
            });
            document.getElementById("content").innerHTML = cRegTimes;

            // The sum of registered times is displayed below the table
            var cTotal = "<div class='row row-content'>";
            cTotal += "<label for='txtTotal' class='col-form-label offset-1'>Total registered time</label>";
            cTotal += "<div class='col offset-2'><input type='text' class='form-control text-right'";
            cTotal += " value='" + FcFormatMinutes(nTotal) + "' disabled /></div>";

            document.getElementById("total").innerHTML = cTotal;
        }
    });
}

/**
 * Loads the data of a registered time in the modal form of the registered times page
 * 
 * @author Arturo Mora-Rioja
 * @date   7/4/2018
 * 
 * @param  id of the registered time whose data will be retrieved
 */
function FGetRegTime(pnRegTimeID){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {cElement: "RegTime-Get", nRegTimeID: pnRegTimeID},
        success: function(data){
            var CUSTOMER_ID = 0;
            var PROJECT_ID = 1;
            var REG_DATE = 2;
            var TIME = 3;
            var COMMENTS = 4;
            var acProject = JSON.parse(data);
            document.getElementById("modalCaption").innerHTML = "Update Registered Time";
            document.getElementById("txtRegTimeID").value = pnRegTimeID;
            document.getElementById("cmbCustomers").value = acProject[CUSTOMER_ID];
            document.getElementById("txtRegDate").value = acProject[REG_DATE];
            document.getElementById("txtRegTime").value = acProject[TIME];
            document.getElementById("txtComments").value = acProject[COMMENTS];

            // The projects combo will be loaded based on the selected customer in the customers combo.
            // The corresponding project will be set as selected in the projects combo.
            FGetCustomerProjectsToCmb(acProject[CUSTOMER_ID], 'cmbProjects', acProject[PROJECT_ID]);
        }
    });
}

/**
 * Initialises the registered times modal form in the registered times page
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 */
function FRegTimeInit(){
    document.getElementById("modalCaption").innerHTML = "New Registered Time";
    document.getElementById("txtRegTimeID").value = "NEW";
    document.getElementById("cmbCustomers").selectedIndex = 0;
    // Initial load of the projects combo based on the customer selected by default
    FGetCustomerProjectsToCmb(document.getElementById("cmbCustomers").value, "cmbProjects");
    document.getElementById("txtRegDate").value = "";
    document.getElementById("txtRegTime").value = "";
    document.getElementById("txtComments").value = "";
}

/**
 * Reloads registered times in the registered times page's table 
 * after a registered time has been submitted or deleted
 * 
 * @author Arturo Mora-Rioja
 * @date   9/4/2018
 */
function FReloadRegTimes(){
    // Registered times are reloaded based on the current filter (by customer or by project)
    if(document.getElementById("cmbProjectList").value == 0){
        nValue = document.getElementById("cmbCustomerList").value;
        bIsCustomer = true;
    } else {
        nValue = document.getElementById("cmbProjectList").value;
        bIsCustomer = false;
    }
    FLoadRegTimes(nValue, bIsCustomer);     
}

/**
 * Submits the registered time form in the registered time page
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 */
function FSubmitRegTime(){
    $("a#submit").click(function(btn){
        btn.preventDefault();   // By preventing the submit action, navigation will stay in the current page

        data = {
            txtRegTimeID: $("input#txtRegTimeID").val(),
            cmbCustomers: $("select#cmbCustomers").val(),
            cmbProjects: $("select#cmbProjects").val(),
            txtRegDate: $("input#txtRegDate").val(),
            txtRegTime: $("input#txtRegTime").val(),
            txtComments: $("textarea#txtComments").val()
        };

        // Mandatory data check
        if(data.cmbProjects == null){ alert("A project must be chosen"); return; }
        if(data.txtRegDate == ""){ alert("Registration Date cannot be empty"); return; }
        if(data.txtRegTime == ""){ alert("Registered Time cannot be empty"); return; }

        $.post("src/main.php", data, function(d){
            $("button#dismiss").click();    // The customer form is hidden
            FReloadRegTimes();              // Registered times are reloaded in the table
        });
    });
}

/**
 * Deletes a registered time
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 * 
 * @param  id of the registered time to delete
 */
function FDeleteRegTime(pnRegTimeID){
    if(window.confirm("The registered time will be deleted. Are you sure?"))
        $.ajax({
            url: "src/main.php",
            type: "POST",
            data: {cElement: "RegTime-Delete", nRegTimeID: pnRegTimeID},
            success: function(data){
                var RET_ERROR = 0;
                var RET_OK = 1;
                var nResult = JSON.parse(data);

                switch(nResult){
                    case RET_ERROR:
                        alert("The registered time could not be deleted"); break;
                    case RET_OK:
                        FReloadRegTimes(); break;   // Registered times are reloaded in the table
                } 
            }
        });
}