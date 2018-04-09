/**
 * Formats a time string ("hh:mm:ss") into a ("hh\h mm'") format
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 * 
 * @param  time string
 * @return formatted string
 */
function FcFormatTime(pcTime){
    return pcTime.substring(0, 2) + "h " + pcTime.substring(3, 5 ) + "'";
}

/**
 * Converts a time string ("hh:mm:ss") to minutes.
 * It ignores the seconds.
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 * 
 * @param  time string
 * @return number of minutes
 */
function FnTimeToMinutes(pcTime){
    return parseInt(pcTime.substring(3, 5)) + parseInt(pcTime.substring(0,2) * 60);
}

/**
 * Formats a number of minutes into a ("hh\h mm'") format
 * 
 * @author Arturo Mora-Rioja
 * @date   8/4/2018
 * 
 * @param  number of minutes
 * @return formatted string
 */
function FcFormatMinutes(pnMinutes){
    var nHours = parseInt(pnMinutes / 60);
    var nRemainingMinutes = (pnMinutes - (nHours * 60));

    return nHours.toString() + "h " + 
        nRemainingMinutes.toString() + (nRemainingMinutes < 10 ? "0" : "") + "&apos;";
}