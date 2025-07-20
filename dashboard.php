<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sample";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the delete button was pressed
if (isset($_POST['delete_button'])) {
    $deleteId = $_POST['delete_id'];

    // Prepare and execute the MySQL query to delete the row
    $deleteSql = "DELETE FROM portfolio WHERE relationship_number = '$deleteId'";

    if ($conn->query($deleteSql) === TRUE) {
        // Display an alert and refresh the page
        echo "<script>alert('Row deleted successfully!'); window.location.href = 'dashboard.php';</script>";
        exit; // Terminate script execution to prevent further output
    } else {
        // Display an alert with the error message
        echo "<script>alert('Error deleting row: " . $conn->error . "');</script>";
    }
}

?>

<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sample";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if row update form was submitted
if (isset($_POST['update_button'])) {
  //var_dump($_POST); // Debugging output
  $rno = $_POST['rno'];
  $chk = $_POST['chk'];
  $upd = $_POST['upd'];
  $status = $_POST['status'];

  $sql = "UPDATE portfolio SET checked_on='$chk', updated_on='$upd', status='$status' WHERE relationship_number = '$rno'";

  if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Row updated successfully!'); window.location.href = 'dashboard.php';</script>";
      exit;
  } else {
      echo "<script>alert('Error updating row: " . $conn->error . "');</script>";
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<!DOCTYPE html>
<html>
<head>
<style>
  .center-heading {
    text-align: center;
    background-color: #c64ef2;
    padding: 10px;
    color: white;
    margin: 0; /* Remove default margin to have toolbar start immediately after h2 */
  }

/* Style for the toolbar */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #c64ef2;
    color: white;
  }

  /* Style for the search bar */
  .search-bar {
    margin-left: 20px;
  }

  /* Style for the insert button */
  .insert-button {
    margin-right: 20px;
  }

/* Style for the table */
table {
  border-collapse: collapse;
  width: 50%;
}

/* Style for table header */
th {
  background-color: #007bff; /* Blue color */
  color: white;
}

/* Style for table cells */
td {
  padding: 8px;
  text-align: left;
}

/* Style for alternating table rows */
tr:nth-child(even) {
  background-color: #f2f2f2;
}

/* Style for the message when no records exist */
.no-records {
  color: red;
  font-weight: bold;
}

</style>
</head>
<body>
<h2 class="center-heading">Portfolio Table</h2>
<div class="toolbar">
  <div class="insert-button">
    <button class="insert-button" onclick="showUpdateForm()">Update</button>
  </div>

  <div class="sort-container">
    <label for="status-sort">Status</label>
    <select id="status-sort" onchange="sortTable()">
      <option value="all">All</option>
      <option value="closed">Closed</option>
      <option value="pending">Pending</option>
      <option value="rebalance_pending">Rebalance pending</option>
    </select>
  </div>

  <div class="sort-container">
    <label for="model-sort">Model</label>
    <select id="model-sort" onchange="modelTable()">
      <option value="all">All</option>
      <option value="sample_model">Sample model</option>
      <option value="tree_model">Tree model</option>
    </select>
  </div>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search relationship name">
  <button onclick="searchTable()">Search</button>
  </div>
</div>

<?php
// Replace these with your actual MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sample";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the update form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rname'])) {
  $rname = $_POST['rname'];
  $rno = $_POST['rno'];
  $pname = $_POST['pname'];
  $ivb = $_POST['ivb'];
  $mname = $_POST['mname'];
  $pval = $_POST['pval'];
  $cdate = $_POST['cdate'];
  $cname = $_POST['cname'];
  $prdate = $_POST['prdate'];
  $rodate = $_POST['rodate'];
  $status = $_POST['status'];

  // Prepare and execute the MySQL query to insert the new row
  $sql = "INSERT INTO portfolio (relationship_name, relationship_number, portfolio_name, investment_banker, model_name,
      portfolio_value, created_on, created_by, previous_rebalance_date, rebalance_open_date, status)
      VALUES ('$rname', '$rno', '$pname', '$ivb', '$mname', '$pval', '$cdate', '$cname', '$prdate', '$rodate', '$status')";

  if ($conn->query($sql) === TRUE) {
      echo "Row inserted successfully!";
  } else {
      echo "Error inserting row: " . $conn->error;
  }
}

// Query to select all rows from the portfolio table
$sql = "SELECT * FROM portfolio ORDER BY status ASC, updated_on DESC";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Start the HTML table
    echo "<table id='portfolio-table' border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>";
    echo "<th>Relationship Name</th>";
    echo "<th>Relationship Number</th>";
    echo "<th>Portfolio Name</th>";
    echo "<th>Investment Banker</th>";
    echo "<th>Model Name</th>";
    echo "<th>Portfolio Value</th>";
    echo "<th>Created on</th>";
    echo "<th>Created by</th>";
    echo "<th>Updated on</th>";
    echo "<th>Checked by</th>";
    echo "<th>Previuos Rebalance Date</th>";
    echo "<th>Rebalance Open Date</th>";
    echo "<th>Status</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    // Loop through the rows and display the data in table rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["relationship_name"] . "</td>";
        echo "<td>" . $row["relationship_number"] . "</td>";
        echo "<td>" . $row["portfolio_name"] . "</td>";
        echo "<td>" . $row["investment_banker"] . "</td>";
        echo "<td>" . $row["model_name"] . "</td>";
        echo "<td>" . $row["portfolio_value"] . "</td>";
        echo "<td>" . $row["created_on"] . "</td>";
        echo "<td>" . $row["created_by"] . "</td>";
        echo "<td>" . $row["updated_on"] . "</td>";
        echo "<td>" . $row["checked_on"] . "</td>";
        echo "<td>" . $row["previous_rebalance_date"] . "</td>";
        echo "<td>" . $row["rebalance_open_date"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td>";
        echo "<form method='post' action='dashboard.php'>"; 
        echo "<input type='hidden' name='rno' value='" . $row["relationship_number"] . "'>";
        echo "<input type='text' name='chk' placeholder='Checked by'>";
        echo "<input type='text' name='upd' placeholder='Updated on (YYYY-MM-DD)'>";
        echo "<input type='text' name='status' placeholder='Status'>";
        echo "<button type='submit' name='update_button'>Update</button>";
        echo "</form>";

        echo "<form method='post' action='dashboard.php'>";
        echo "<input type='hidden' name='delete_id' value='" . $row["relationship_number"] . "'>";
        echo "<button type='submit' name='delete_button'>Delete</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";

    }

    echo "<tr id='no-records-row' style='display: none;'>";
    echo "<td colspan='14' class='no-records'>No records found</td>";
    echo "</tr>";

    // Close the table
    echo "</table>";
} else {
    // If no records exist, print a message
    echo "No records exist.";
}

// Close the database connection
$conn->close();
?>

<script>
function showUpdateForm() {
  var delimiter = '#'; // Delimiter to separate input fields
  var updateForm = prompt("Enter details for new row separated by '" + delimiter + "':\n\n" +
    "Relationship Name:\n" +
    "Relationship Number:\n" +
    "Portfolio Name:\n" +
    "Investment Banker:\n" +
    "Model Name:\n" +
    "Portfolio Value:\n" +
    "Created On (YYYY-MM-DD):\n" +
    "Created By:\n" +
    "Previous Rebalance Date (YYYY-MM-DD):\n" +
    "Rebalance Open Date (YYYY-MM-DD):\n"+
    "Status:");

  if (updateForm !== null) {
    var details = updateForm.split(delimiter);

    // Create the data string to send
    var data = "rname=" + encodeURIComponent(details[0]) +
               "&rno=" + encodeURIComponent(details[1]) +
               "&pname=" + encodeURIComponent(details[2]) +
               "&ivb=" + encodeURIComponent(details[3]) +
               "&mname=" + encodeURIComponent(details[4]) +
               "&pval=" + encodeURIComponent(details[5]) +
               "&cdate=" + encodeURIComponent(details[6]) +
               "&cname=" + encodeURIComponent(details[7]) +
               "&prdate=" + encodeURIComponent(details[8]) +
               "&rodate=" + encodeURIComponent(details[9]) +
               "&status=" + encodeURIComponent(details[10]);

    var url = "dashboard.php"; 

    // Create a new XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        alert('Row inserted successfully!');
        location.reload(); // Refresh the page to show the updated data
      } else if (xhr.readyState === 4) {
        alert('Error updating row: ' + xhr.responseText);
      }
    };
    xhr.send(data);
  }
}

function searchTable() {
  var input = document.getElementById("search-input").value.toLowerCase();

  // Check if input is not empty before performing the search
  if (input === "") {
    alert("Please enter a search term!");
    return;
  }

  var table = document.getElementById("portfolio-table");
  var rows = table.getElementsByTagName("tr");
  var noRecordsRow = document.getElementById("no-records-row");

  var found = false; // Flag to track if any records are found
  
  for (var i = 1; i < rows.length; i++) {
    var row = rows[i];
    var relationshipName = row.cells[0].innerText.toLowerCase();
    
    if (relationshipName.includes(input)) {
      row.style.display = "";
      found = true; // Set found to true if a matching record is found
    } else {
      row.style.display = "none";
    }
  }

  // Display "No records found" if no matching records were found
  if (found) {
    noRecordsRow.style.display = "none";
  } else {
    noRecordsRow.style.display = "";
  }
}

function sortTable() {
  var statusDropdown = document.getElementById("status-sort");
  var selectedStatus = statusDropdown.value;
  console.log("Selected Status:", selectedStatus);

  var table = document.getElementById("portfolio-table");
  var rows = table.getElementsByTagName("tr");
  var found = false;

  for (var i = 1; i < rows.length; i++) {
    var row = rows[i];
    var rowStatus = row.cells[12].innerText.toLowerCase();

    if (selectedStatus === "all" || rowStatus === selectedStatus.replace("_", " ").toLowerCase()) {
      row.style.display = "";
      found = true;
    } else {
      row.style.display = "none";
    }
  }

  var noRecordsRow = document.getElementById("no-records-row");
  if (found) {
    noRecordsRow.style.display = "none";
  } else {
    noRecordsRow.style.display = "";
  }
}

function modelTable() {
  var modelDropdown = document.getElementById("model-sort");
  var selectedmodel = modelDropdown.value;
  console.log("Selected Model:", selectedmodel);

  var table = document.getElementById("portfolio-table");
  var rows = table.getElementsByTagName("tr");
  var found = false;

  for (var i = 1; i < rows.length; i++) {
    var row = rows[i];
    var rowmodel = row.cells[4].innerText.toLowerCase();

    if (selectedmodel === "all" || rowmodel === selectedmodel.replace("_", " ").toLowerCase()) {
      row.style.display = "";
      found = true;
    } else {
      row.style.display = "none";
    }
  }

  var noRecordsRow = document.getElementById("no-records-row");
  if (found) {
    noRecordsRow.style.display = "none";
  } else {
    noRecordsRow.style.display = "";
  }
}
</script>

</body>
</html>