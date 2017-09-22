$('#installer').on("click", function(event){
    
    $('#progress').html("Please wait...");
    $('#installer').hide();
    
    $.ajax({
        url: "/installer/install",
        type: "GET",
        success: function(resp) {
            alert("installed successfully")
            $('#installer').show();
            $('#progress').hide();

        },
        error: function(XHR){
            console.log(XHR);
        }
    })
})