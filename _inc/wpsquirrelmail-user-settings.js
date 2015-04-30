jQuery( document ).ready(function($) {
    
    $.post( my_ajax_obj.ajax_url, {
        _ajax_nonce: my_ajax_obj.nonce,
        action: "wpsquirrelmail_user"
    }, function(data) {
        if( data.response === true ) {
            $( '#password').val(data.password);
        } else {
            // print error message to console
            console.log( data.message );
        }
        
        hideElement();
    });
    
    $( '#username').change(function(){
        hideElement();
    });
    
    $( '#password').change(function(){
        hideElement();
    });
    
    /**
     * @returns {void}
     */
    function hideElement() {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        var autologin = document.getElementById('autologin');
        
       if(username.replace(/\s+/g, '').length > 0 && password.replace(/\s+/g, '').length > 0) {
           autologin.disabled = false;
       } else {
           autologin.disabled = true;
       }
    }
});