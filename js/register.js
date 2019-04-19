//AJAX check for email and username via AXIOS

/*--------------------
    EMAIL CHECK
--------------------*/
//Select the email input field AND listen for the event of losing focus.
var emailField = document.querySelector("#email");
emailField.addEventListener("focusout", function(){
    
    //Get email input from the email field
    var email = emailField.value;
            
    //Check if field has input before sending an AJAX call
    if(email != ""){ 
                
        //send an AJAX call via Axios to check_email.php			
        axios.post('ajax/check_email.php',{
            email: email
        })

        //response
        .then(function (response) {
            
            //update UI if email is taken already
            var feedback = document.querySelector("#emailFeedback");
                    
            if( response.data["status"] == "error"){
                        
                feedback.innerHTML = response.data["message"];
                feedback.classList.remove("hidden");
            }else if( response.data["status"] == "success"){
                feedback.innerHTML = "";
                feedback.classList.add("hidden");
            }
                    
        })

        //catch error
        .catch(function (error) {
            console.log(error);
        });

    }	
});

/*--------------------
    USERNAME CHECK
--------------------*/

//Select the email input field AND listen for the event of losing focus.
var usernameField = document.querySelector("#username");
usernameField.addEventListener("focusout", function(){
    
    //Get email input from the email field
    var username = usernameField.value;
            
    //Check if field has input before sending an AJAX call
    if(username != ""){ 
                
        //send an AJAX call via Axios to check_email.php			
        axios.post('ajax/check_username.php',{
            username: username
        })

        //response
        .then(function (response) {
            console.log(response.data["status"]);
            //update UI if user is taken already
            var feedback = document.querySelector("#usernameFeedback");
                    
            if( response.data["status"] == "error"){
                        
                feedback.innerHTML = response.data["message"];
                feedback.classList.remove("hidden");
            }else if( response.data["status"] == "success"){
                feedback.innerHTML = "";
                feedback.classList.add("hidden");
            }
                    
        })

        //catch error
        .catch(function (error) {
            console.log(error);
        });

    }	
});