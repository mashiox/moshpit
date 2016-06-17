$(document).ready(function(){
    
    var clearForm = function(){
        $("form#igForm").each( function(idx){
            $(this)[idx].reset();
        });
        displayError(0);
    }
    
    var displayError = function( status ){
        $("div#statusMsg div.alert").hide();
        switch ( status ){
            case 0:
                $("div#uploadSuccess").show();
                break;
            case 1:
            case 2:
                $("div#cannotReachIG").show();
                break;
            case 3:
                $("div#incompatImage").show();
                break;
            case 4:
                $("div#apiFail").show();
                break;
            default:
                $("div#genFail").show();
        }
    }
    
    var postToServer = function(data, addr){
        
        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', addr, true);
        xhr.onload = function(){
            if ( xhr.status === 200 ){
                // post positive
                if ( !xhr.response ) alert("Something went very wrong!");
                else {
                    var res = JSON.parse( xhr.response );
                    switch ( res.status ){
                        case 0:
                            clearForm();
                            break;
                        default:
                            displayError( res.status );
                    }
                }
                console.log("sent");
                console.log(xhr);
            }
            else {
                displayError(4);
            }
        }
        xhr.send(data);
        
    }
    
    $("input#submit").click(function(evt){
        evt.preventDefault();
        
        var formData = new FormData();
        var file = document.getElementById('gram').files[0];
        if ( !file ) return;
        var username = $("input#igUser").val();
        if ( !username ) return;
        var password = $("input#igPass").val();
        if ( !password ) return;
        var caption = $("input#caption").val();
        formData.append( 'userfile[]', file, file.name );
        formData.append( 'password', password );
        formData.append( 'user', username );
        formData.append( 'caption', caption ); 
        
        postToServer( formData, "server.php" );
    })
    
    $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if ( input.length ) {
              input.val(log);
          }

      });
    
})

$(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });
})