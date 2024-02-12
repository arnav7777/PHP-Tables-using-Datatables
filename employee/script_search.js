function searchEmployees() {
  var input = document.getElementById('search').value;
  console.log("Value of input:", input);
  var resultsContainer = document.getElementById('results');
  var detailsContainer = document.getElementById('employee-details');

  if (input.length === 0) {
      resultsContainer.style.display = 'none';
      detailsContainer.innerHTML = '';
      return;
  }

  // Make an AJAX request to fetch employees from the server
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var employees = JSON.parse(xhr.responseText);
          displayEmployeeOptions(employees);
      }
  };

  // Replace 'getEmployees.php' with the correct path to your PHP script
  xhr.open('GET', 'getEmployees.php?query=' + encodeURIComponent(input), true);
  xhr.send();
}

function displayEmployeeOptions(employees) {
    var resultsContainer = document.getElementById('results');
    resultsContainer.innerHTML = '';

    employees.forEach(function (employee) {
        var resultItem = document.createElement('ul');
        resultItem.className = 'result-item dropdown-item list-unstyled';

        var nameItem = document.createElement('li');
        nameItem.textContent = "Name: " + employee.name;

        var emailItem = document.createElement('li');
        emailItem.textContent = "Email: " + employee.email;

        resultItem.appendChild(nameItem);
        resultItem.appendChild(emailItem);

        resultItem.onclick = function () {
            showEmployeeDetails(employee.employee_id);
        };

        resultsContainer.appendChild(resultItem);
    });

    resultsContainer.style.display = 'block';
}


function showEmployeeDetails(employeeId) {
  var detailsContainer = document.getElementById('employee-details');

  // Make an AJAX request to get employee details from the server
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          detailsContainer.innerHTML = xhr.responseText;
      }
  };

  // Replace 'employeeDetails.php' with the correct path to your PHP script
  xhr.open('GET', 'employeeDetails.php?employeeId=' + encodeURIComponent(employeeId), true);
  xhr.send();

  // Hide the results container after selecting an option
  document.getElementById('results').style.display = 'none';
}
