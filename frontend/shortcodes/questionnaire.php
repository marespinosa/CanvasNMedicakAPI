
<!-- HTML form -->
<form id="myForm">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name"><br><br>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email"><br><br>
  <button type="submit">Submit</button>
</form>

<!-- JavaScript to capture form data and display results -->
<script>
  const form = document.querySelector('#regForm');
  form.addEventListener('submit', (event) => {
    event.preventDefault(); // prevent default form submission behavior
    const name = document.querySelector('#name').value;
    const email = document.querySelector('#email').value;
    const resultsTable = document.querySelector('#resultsTable'); // existing HTML table to display results
    const newRow = resultsTable.insertRow(-1); // insert new row at end of table
    newRow.innerHTML = `<td>${name}</td><td>${email}</td>`; // add form data to new row
  });
</script>

<!-- HTML table to display results -->
<table id="resultsTable">
  <tr>
    <th>Name</th>
    <th>Email</th>
  </tr>
</table>

