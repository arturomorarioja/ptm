/**
 * Sorts the table rows of a tbody based on the values of a table column
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 * 
 * @param id of the tbody to sort
 * @param index of the column based on whose values the rows will be sorted
 */
function FTableSort(pcTable, pnIndex){
    var acTrs = $("#" + pcTable + " tr");
    var acTds = [];
    var acVisibility = [];
    var cTableContent = "";

    // An array is populated with all <td> values and indexes for the column to order.
    acTrs.each(function(index, value){
        acTds.push($(this).find("td:eq(" + pnIndex + ")").html() + "||" + index.toString());
    });

    // The array is sorted based on content
    acTds.sort();

    // <tr>s are sorted based on the <td>s array
    $.each(acTds, function(index, value){
        nTdIndex = parseInt(acTds[index].substring(acTds[index].indexOf("||") + 2));

        tr = $("#" + pcTable + " tr:eq(" + nTdIndex + ")");
        
        cTableContent += "<tr>"
            + tr.html()
            + "</tr>";

        // The id and visibility of each <tr> is stored in an array in the final sort order
        acVisibility.push([tr.id, tr.css("display")]);
    });
    $("#" + pcTable).html(cTableContent);

    // id and visibility are restored to the <tr>s
/*     acTrs = document.getElementById(pcTable).getElementsByTagName("tr");
    for(nCount = 0; nCount < acVisibility.length; nCount++){
        acTrs[nCount].id = acVisibility[nCount][0];
        acTrs[nCount].style.display = acVisibility[nCount][1];
    } */

    $.each(acVisibility, function(index, value){
        tr = $("#" + pcTable + " tr:eq(" + index + ")");

        tr.id = acVisibility[index][0];
        tr.css("display", acVisibility[index][1]);
    });
}