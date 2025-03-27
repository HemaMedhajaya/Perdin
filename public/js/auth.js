$("#formAuthentication").on("submit", function(e) {
    e.preventDefault(); 

    if (!navigator.onLine) {
        $("#responseMessage").html('<div class="alert alert-danger">Tidak ada koneksi internet!</div>');
        return;
    }
    $('#loadingOverlay').fadeIn();
    $.ajax({
        url: routes.loginPost, 
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            if (response.success) {
                $("#responseMessage").html('<div class="alert alert-success">Login berhasil! Mengalihkan...</div>');
                setTimeout(function() {
                    window.location.href = routes.otpAuth;
                },2000);
            } else {
                $("#responseMessage").html('<div class="alert alert-danger">Login gagal: ' + response.message + '</div>');
            }
        },
        error: function(xhr) {
            let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan!";
            $("#responseMessage").html('<div class="alert alert-danger">' + errorMessage + '</div>');
        },
        complete: function () {
            $('#loadingOverlay').fadeOut(); 
        }
    });
});
