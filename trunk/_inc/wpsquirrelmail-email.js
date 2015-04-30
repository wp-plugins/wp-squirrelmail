jQuery( document ).ready(function($) {
    
    $.post( my_ajax_obj.ajax_url, {
        _ajax_nonce: my_ajax_obj.nonce,
        action: "wpsquirrelmail_login"
    }, function(data) {
        if( data.response === true ) {
            $( '#wpsquirrelmail-div' ).empty();
            iframe = createIframe('wpsquirrelmail-iframe', 'wpsquirrelmail-div');
            
            addLink('#wpsquirrelmail-iframe', data.css);
            
            writeContent('#wpsquirrelmail-iframe', data.content);
            $('#wpsquirrelmail-iframe').contents().find('#password').val(data.password);
            if( data.autologin === 1 ) {
                $('#wpsquirrelmail-iframe').contents().find('#wpsquirrelmail-form').submit();
            }
        } else {
            $( '#wpsquirrelmail-div' ).empty();
            // Advise user of error
            $( '#wpsquirrelmail-div' ).html( data.message );
            // print error message to console
            console.log('Could not update Login form');
        }
    });
    
    /**
     * @description Create iframe element
     * @param {string} iframeId New Iframe Id
     * @param {string} elementId Target element Id
     * @returns {iframe}
     */
    function createIframe(iframeId, elementId){
        iframe = document.createElement("iframe");
        iframe.setAttribute("about", "blank");
        iframe.setAttribute("id", iframeId);
        iframe.style.width = "100%";
        iframe.style.height = "35em";
        document.getElementById(elementId).appendChild(iframe);
        
        return iframe;
    }
    
    /**
     * @description Writes data pass to the iFrame as a string
     * @param {string} iframeId
     * @param {string} content
     * @returns {void}
     */
    function writeContent(iframeId, content){
        var $body = $( iframeId ).contents().find("body");
        
        $body.append( content );
    }
    
    /**
     * 
     * @param {string} iframeId
     * @param {string} cssFile
     * @returns {void}
     */
    function addLink(iframeId, cssFile) {
        var $head = $( iframeId ).contents().find("head");
        var url = cssFile;
        
        $head.append($("<link/>", { rel: "stylesheet", href: url, type: "text/css" } ));
    }
});
