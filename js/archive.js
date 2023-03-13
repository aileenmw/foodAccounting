/**
 * toggle element
 * @param string -> element to be toggles
 */
function toggleElement(el) {
    if($(el).css("display") ==  "none") {
        $(el).css("display", "block")
    } else { 
        $(el).css("display", "none")
    }
}; 