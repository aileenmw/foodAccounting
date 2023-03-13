function logout() {
    $.post("ajax/logout.php", function(response) {
        if(response == 1) {
          location.href = "index.php";
        } else {
          swal({
              title: "Noget gik galt",
              text: "Du er ikke blevet logget ud",
              icon: "error",
              timer: 3000,
              button: false,
          });
        }
    });
}

/**
 * only target; the element is chosen directly by id
 * target and parent: the elemet is found by closest to find parent and find for tag og class child
 * text variable can be "text" or "value" (if target is an input field)
 */
function copyEmail(el) {
    $copyText = $(el).closest("tr").find(".mailAddress").html();
    navigator.clipboard.writeText($copyText);
    alert($copyText + " er kopieret til udklipsholder");
}

async function copyEmail(el) {
    try {
        $copyText = $(el).closest("tr").find(".mailAddress").html();
        await navigator.clipboard.writeText($copyText);
        alert($copyText + " er kopieret til udklipsholder");
    } catch (err) {
      console.error('Failed to copy: ', err);
    }
  }

function validatePw(pw) {
    var resArr = [];
    if( pw == "" ) {
        resArr.push("Brugernavn kan ikke være tomt.\n");
    }
    if(pw.length < 6) {
        resArr.push( "Kodeordet skal bestå af mindst 6 tegn.\n");
    }
    reg = /[0-9]/;
    if(!reg.test(pw)) {
        resArr.push("Kodeordet skal indeholde mindst 1 tal.\n");
    }
    reg = /[a-z]/;
    if(!reg.test(pw)) {
        resArr.push("Kodeordet skal indeholde mindst 1 lille bogstav.\n")
    }
    reg = /[A-Z]/;
    if(!reg.test(pw)) {
        resArr.push("Kodeordet skal indeholde mindst 1 stort bogstav.\n");
    }

    return resArr;
};