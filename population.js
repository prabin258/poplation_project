// Function to perform login
function login(){
    const form = document.getElementById("loginform");
    const data = new FormData(form);

    // Send login data to the server for processing
    return fetch('http://localhost/population_information/login_procesor.php',{
        method:'POST',
        body: data
    })
    .then(response=>response.json())
    .then(data=> {
        console.log(typeof data);
        // Show notification if login is unsuccessful, otherwise, perform the login function
        if (!data.result) {
            notifieruser();
        } else {
            loginfunction();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to show notification for unsuccessful login
function notifieruser(){
    const notification = document.getElementById("notification");
    notification.style.display = "block";
}

function loginfunction(){
    const afterlogin = document.getElementById("afterlogin");
    const login = document.getElementById("loginpage");
    login.style.display = "none";
    afterlogin.style.display = "block";
}
