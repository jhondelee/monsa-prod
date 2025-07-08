 // User Logout
    $('.auth-logout').click(function() { $($('#logout-form')).submit(); });
    
    // Collapse in Navigation Group
    $('.nav-group li').each(function() { 
        if ($(this).hasClass('active')) { 
            $(this).closest('.nav-group').addClass('active'); 
            $(this).closest('.nav-second-level').addClass('in');
        }  
    });