$(document).ready(function() {
    const token = localStorage.getItem('user_token');
    const currentPage = window.location.pathname.split("/").pop();

    // --- Redirect Logic ---
    if (token && (currentPage === 'login.html' || currentPage === 'register.html')) {
        window.location.href = 'profile.html';
    }
    if (!token && currentPage === 'profile.html') {
        window.location.href = 'login.html';
    }

    // --- Register ---
    $('#btn-register').click(function() {
        $.ajax({
            url: 'register.php',
            type: 'POST',
            data: {
                username: $('#reg-username').val(),
                password: $('#reg-password').val()
            },
            success: function(res) {
                alert(res.message);
                if(res.success) window.location.href = 'login.html';
            }
        });
    });

    // --- Login ---
    $('#btn-login').click(function() {
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: {
                username: $('#login-username').val(),
                password: $('#login-password').val()
            },
            success: function(res) {
                if (res.success) {
                    // Store session in LocalStorage ONLY
                    localStorage.setItem('user_token', res.data.token);
                    window.location.href = 'profile.html';
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // --- Profile (Fetch & Update) ---
    if (currentPage === 'profile.html') {
        // Fetch Data on Load
        $.ajax({
            url: 'profile.php',
            type: 'POST',
            headers: { 'Authorization': token },
            data: { action: 'fetch' },
            success: function(res) {
                if (res.success) {
                    $('#age').val(res.data.age);
                    $('#dob').val(res.data.dob);
                    $('#contact').val(res.data.contact);
                } else {
                    alert("Session Invalid");
                    logout();
                }
            }
        });

        // Update Data
        $('#btn-update').click(function() {
            $.ajax({
                url: 'profile.php',
                type: 'POST',
                headers: { 'Authorization': token },
                data: {
                    action: 'update',
                    age: $('#age').val(),
                    dob: $('#dob').val(),
                    contact: $('#contact').val()
                },
                success: function(res) {
                    alert(res.message);
                }
            });
        });

        $('#btn-logout').click(logout);
    }

    function logout() {
        localStorage.removeItem('user_token');
        window.location.href = 'login.html';
    }
});