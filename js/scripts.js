/*
    Calls the php code that retrieves data corresponding to the customer whose PK receives as a parameter

    Author: Arturo Mora-Rioja
    Date: 7/4/2018
*/
function FGetCustomer(pnCustomerID){
    $.ajax({
        url: "src/main.php",
        type: "POST",
        data: {nCustomerID_get:pnCustomerID},
        success: function(data){
            var acCustomer = JSON.parse(data);
            document.getElementById("txtCustomerID").value = pnCustomerID;
            document.getElementById("txtCVR").value = acCustomer[0];
            document.getElementById("txtName").value = acCustomer[1];
            document.getElementById("txtAddress").value = acCustomer[2];
            document.getElementById("txtPostCode").value = acCustomer[3];
            document.getElementById("txtTown").value = acCustomer[4];
            document.getElementById("txtPhone").value = acCustomer[5];
            document.getElementById("txtEmail").value = acCustomer[6];
            document.getElementById("cmbCountries").value = acCustomer[7];
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
        data: {cElement:"Countries"},
        success: function(data){
            var acCountries = JSON.parse(data);
            var cCountries = "";

            $.each(acCountries, function(nIndex, xValue) {
                cCountries += "<option value='" + xValue[0] + "'>" + xValue[1] + "</option>";
            });

            document.getElementById("cmbCountries").innerHTML = cCountries;
        }
    });
}