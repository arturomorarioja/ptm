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
    var acTrs = document.getElementById(pcTable).getElementsByTagName("tr");
    var acTds = [];
    var cTableContent = "";

    // An array is filled with all <td> values and indexes for the column to order.
    for(nCount = 0; nCount < acTrs.length; nCount++)
        acTds.push(acTrs[nCount].getElementsByTagName("td")[pnIndex].innerHTML + "||" + nCount.toString());

    // The array is sorted based on content
    acTds.sort();

    // <tr>s are sorted based on the <td>s array
    for(nCount = 0; nCount < acTds.length; nCount++){
        nIndex = parseInt(acTds[nCount].substring(acTds[nCount].indexOf("||") + 2));
        cTableContent += "<tr>" + 
            acTrs[nIndex].innerHTML
            + "</tr>";
    }
    document.getElementById(pcTable).innerHTML = cTableContent;
}