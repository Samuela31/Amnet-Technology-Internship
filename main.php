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

    // Prepare and execute the MySQL query to delete rows from child tables
    $deleteUpdatesSql = "DELETE FROM updates WHERE relationship_number = '$deleteId'";
    $deleteDetailsSql = "DELETE FROM details WHERE relationship_number = '$deleteId'";

    // Execute the queries to delete from child tables
    if ($conn->query($deleteUpdatesSql) === TRUE &&
        $conn->query($deleteDetailsSql) === TRUE) {
        
        // After deleting from child tables, delete from the main table
        $deleteRelationshipSql = "DELETE FROM relationship WHERE relationship_number = '$deleteId'";

        if ($conn->query($deleteRelationshipSql) === TRUE) {
            // Display an alert and refresh the page
            echo "<script>alert('Row deleted successfully!'); window.location.href = 'main.php';</script>";
            exit; // Terminate script execution to prevent further output
        } else {
            // Display an alert with the error message
            echo "<script>alert('Error deleting row: " . $conn->error . "');</script>";
        }
    } else {
        // Display an alert with the error message
        echo "<script>alert('Error deleting child rows: " . $conn->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<style>
 .center-heading {
    text-align: center;
    background-color: #c64ef2;
    padding: 10px;
    color: white;
    margin: 0; /* To remove default margin to have toolbar start immediately after h2 */
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
  <div class="search-bar">
    <input type="text" id="search-input" placeholder="Search relationship name">
    <button onclick="searchTable()">Search</button>
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

  <div class="insert-button">
    <button class="insert-button" onclick="location.href='insert.php'">Add record</button>
  </div>
</div>

<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sample";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT r.*, u.*, d.*
        FROM relationship r
        LEFT JOIN updates u ON r.relationship_number = u.relationship_number
        LEFT JOIN details d ON r.relationship_number = d.relationship_number
        ORDER BY d.status, u.updated_on DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
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
        echo "<td>" . $row["checked_by"] . "</td>";
        echo "<td>" . $row["previous_rebalance_date"] . "</td>";
        echo "<td>" . $row["rebalance_open_date"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";

        echo "<td>";
        echo "<form method='post' action='update.php'>";
        echo "<input type='hidden' name='update_id' value='" . $row["relationship_number"] . "'>";
        echo "<button type='submit' name='update_button'>Update</button>";
        echo "</form>";

        echo "<form method='post' action='main.php'>";
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

$conn->close();
?>

<script>
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
