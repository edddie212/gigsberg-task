$(document).ready(function () {
    $("#userForm").submit(function (e) {
        e.preventDefault();

        const username = $("#username").val();
        if (!/^[a-zA-Z]+$/.test(username)) {
            alert("Username must be letters only.");
            return false;
        }

        const email = $("#email").val();
        if (!/^\S+@\S+\.\S+$/.test(email)) {
            alert("Please enter a valid email.");
            return false;
        }

        const password = $("#password").val();
        if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/.test(password)) {
            alert("Password must be at least 8 characters long with at least one lowercase letter, one uppercase letter, and one special character.");
            return false;
        }

        const phone = $("#phone").val();
        if (!/^\d{10}$/.test(phone)) {
            alert("Phone number must be 10 digits.");
            return false;
        }

        const url = $("#url").val();
        // Regular expression to match a basic URL structure (example.com, example.co.uk)
        const urlPattern = /^(?:(?:https?:\/\/)?(?:www\.)?)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z]{2,}$/i;
        if (!urlPattern.test(url)) {
            alert("Please enter a valid URL, like 'example.com' or 'sub.example.com'.");
            return false;
        }

        const action = $("#action").val();

        $.ajax({
            url: './data/actions.php',
            type: 'POST',
            data: {
                action: action,
                username: username,
                email: email.toLowerCase(),
                password: password,
                birthdate: $("#birthdate").val(),
                phone: phone,
                url: url,
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },

            error: function () {
                alert("An error occurred while submitting the form.");
            }
        });
    });
});

//Delete user
$(document).on('click', '.deleteBtn', function () {
    const username = $(this).data('username');
    const action = "delete";
    if (confirm('Are you sure you want to delete ' + username + '?')) {
        $.ajax({
            url: './data/actions.php',
            type: 'POST',
            data: {username: username, action: action},
            success: function (response) {
                if (response.success) {
                    alert('User deleted successfully');
                    // Refetch and update the user table
                    fetchUsers();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred while deleting the user.');
            }
        });
    }
});


// When the user clicks on a username
$('#usersTable').on('click', 'td.username', function() {
    // Remove highlight from all username td elements and highlight the clicked one
    $('#usersTable td').removeClass('highlighted-username');
    const clickedUsernameTd = $(this).addClass('highlighted-username');

    const username = $(this).text();
    $.ajax({
        url: './data/actions.php',
        type: 'GET',
        data: { username: username },
        success: function(response) {
            if (response.success) {
                // Populate the modal fields
                $('#modal-username').text(response.data.Username);
                $('#modal-email').text(response.data.Email);
                $('#modal-birthdate').text(response.data.Birthdate);
                $('#modal-phone').text(response.data.Phone_number);
                $('#modal-url').text(response.data.URL);
                // Show the modal
                $('#userModal').show();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error fetching user data');
            clickedUsernameTd.removeClass('highlighted-username');
        }
    });

    //No scroll if modal opened
    $('body').addClass('no-scroll');
});

// Handle closing the modal
$(document).on('click', function(event) {
    // Check if the clicked element is the close button or outside the modal
    if ($(event.target).hasClass('close') || event.target.id === 'userModal') {
        $('#userModal').hide();
        $('#usersTable td').removeClass('highlighted-username');
        $('body').removeClass('no-scroll');
    }
});


//Get all users from DB table and render
function fetchUsers() {
    $.ajax({
        url: './data/actions.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const usersTable = $('#usersTable');
                // Clear existing rows except the header
                usersTable.find('tr:gt(0)').remove();
                response.data.forEach(function(user) {
                    usersTable.append('<tr class="username-row"><td class="username">' + user.Username + '</td><td>' + user.Email + '</td><td><input type="hidden" id="action" value="delete"/><button class="deleteBtn" data-username="' + user.Username + '">Delete</button></td></tr>');
                });
            } else {
                $('#usersTable').append('<h2>Sorry No Users Found</h2>')
            }
        },
        error: function() {
            alert("An error occurred while fetching users.");
        }
    });
}

// Initially fetch users when the page loads
$(document).ready(function () {
    fetchUsers();
});

