

function init() {

    $('a.del').click(function (event) {
        event.stopImmediatePropagation();

        // console.log(event.target.id);
        removeUser(event.target.id);

        return false;
    });

}


function removeUser(id) {

    var url = "/testsApplication/User/removeUser/?id=" + id;

    $.ajax({
        url: url,
        success: function(answer){
            var result = JSON.parse(answer);

            if(result.type == "success")
            {
                $("tr."+id).remove();
            }
        }
    });
}


function showResult(type, message) {


}