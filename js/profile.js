$(document).ready(function() {
    const token = localStorage.getItem('user_token');

    // If NOT logged in, go to login
    if (!token) {
        window.location.href = 'login.html';
    }

    // 1. Fetch Data on Load
    $.ajax({
        url: 'php/profile.php',
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
        },
        error: function() {
            alert("Failed to load profile");
        }
    });

    // 2. Update Data
    $('#btn-update').click(function() {
        $.ajax({
            url: 'php/profile.php',
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

    // 3. Logout
    $('#btn-logout').click(logout);

    function logout() {
        localStorage.removeItem('user_token');
        window.location.href = 'login.html';
    }
});