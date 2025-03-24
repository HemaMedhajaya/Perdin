$(document).ready(function () {
    $(".permission-checkbox").on("change", function () {
        var permission_id = $(this).data("permission-id");
        var role_id = $(this).data("role-id");
        var isChecked = $(this).is(":checked");

        $.ajax({
            url: routes.permissionroleData, 
            type: "POST",
            data: {
                _token: csrfToken, 
                role_id: role_id,
                permission_id: permission_id,
                action: isChecked ? "add" : "remove"
            },
            success: function (response) {
                console.log(response.message);
            },
            error: function (xhr) {
                console.error("Error:", xhr.responseText);
            }
        });
    });
});
