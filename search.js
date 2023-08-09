document.addEventListener("DOMContentLoaded", updatefunction);

var countryDropdown = document.getElementById("countryDropdown");
var cityDropdown = document.getElementById("cityDropdown");
var populationDropdown = document.getElementById("populationDropdown");

countryDropdown.addEventListener("change", updatefunction);
cityDropdown.addEventListener("change", updatefunction);
populationDropdown.addEventListener("change", updatefunction);

var dropdown_going_to_be_changed = null;
var dropdownoptions = [];
var content = [];

function updatefunction() {
    var inputArray = [];
    inputArray.push(countryDropdown.value);
    inputArray.push(cityDropdown.value);
    inputArray.push(populationDropdown.value);

    var formData = new FormData();
    formData.append("country", inputArray[0]);
    formData.append("city", inputArray[1]);
    formData.append("population_type", inputArray[2]);

    return fetch('http://localhost/population_information/search_result_shower.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        dropdownoptions = data.options;
        content = data.content;
        dropdown_going_to_be_changed = data.changing;

        // Populate dropdowns and update content
        if (dropdown_going_to_be_changed === "country") {
            for (var country of dropdownoptions) {
                var option = document.createElement("option");
                option.value = country;
                option.textContent = country;
                countryDropdown.appendChild(option);
            }
        } else if (dropdown_going_to_be_changed === "city") {
            for (var city of dropdownoptions) {
                var option = document.createElement("option");
                option.value = city;
                option.textContent = city;
                cityDropdown.appendChild(option);
            }
        }

        const newolddata = document.getElementById("cell12");
        const newyoungdata = document.getElementById("cell22");
        const newchilddata = document.getElementById("cell32");

        newolddata.innerHTML = content[0];
        newyoungdata.innerHTML = content[1];
        newchilddata.innerHTML = content[2];
    })
    .catch(error => console.error('Error:', error));
}
