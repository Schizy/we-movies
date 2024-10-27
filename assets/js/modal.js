$('.btn-modal').click(function(){
    var url = $(this).data("url");
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'html',
        success: function(res) {

            $('.modal-body').html(res);

            // show modal
            $('#myModal').modal('show');

        },
        error:function(request, status, error) {
            console.log("ajax call went wrong:" + request.responseText);
        }
    });
});
