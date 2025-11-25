$(document).ready(function() {
    // If logged in, go to profile
    if (localStorage.getItem('user_token')) {
        window.location.href = 'profile.html';
    }

    $('#btn-register').click(function() {
        const username = $('#reg-username').val();
        const password = $('#reg-password').val();

        if(!username || !password) {
            alert("Please fill all fields");
            return;
        }

        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: { username: username, password: password },
            success: function(res) {
                alert(res.message);
                if(res.success) window.location.href = 'login.html';
            },
            error: function() {
                alert("Server Error");
            }
        });
    });
});