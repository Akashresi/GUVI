$(document).ready(function() {
    // If logged in, go to profile
    if (localStorage.getItem('user_token')) {
        window.location.href = 'profile.html';
    }

    $('#btn-login').click(function() {
        const username = $('#login-username').val();
        const password = $('#login-password').val();

        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: { username: username, password: password },
            success: function(res) {
                if (res.success) {
                    localStorage.setItem('user_token', res.data.token);
                    window.location.href = 'profile.html';
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert("Server Error");
            }
        });
    });
});