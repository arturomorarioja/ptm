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
function FGetAllCountries(){
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
