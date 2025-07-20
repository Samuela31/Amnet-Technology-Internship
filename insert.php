<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sample";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
  // Retrieve data from the form
  $relationshipName = $_POST['relationship_name'];
  $relationshipNumber = $_POST['relationship_number'];
  $portfolioName = $_POST['portfolio_name'];
  $investmentBanker = $_POST['investment_banker'];
  $modelName = $_POST['model_name'];
  $portfolioValue = $_POST['portfolio_value'];

  $createdOn = $_POST['created_on'];
  $createdBy = $_POST['created_by'];

  $previousRebalanceDate = $_POST['previous_rebalance_date'];
  $rebalanceOpenDate = $_POST['rebalance_open_date'];
  $status = $_POST['status'];

  // Insert SQL queries
  $insert_relationship_sql = "INSERT INTO relationship(relationship_name, relationship_number, portfolio_name, investment_banker, model_name, portfolio_value)
                              VALUES ('$relationshipName', '$relationshipNumber', '$portfolioName', '$investmentBanker', '$modelName', '$portfolioValue')";

  $insert_updates_sql = "INSERT INTO updates(relationship_number, created_on, created_by)
                        VALUES ('$relationshipNumber', NOW(), '$createdBy')";

  $insert_details_sql = "INSERT INTO details(relationship_number, previous_rebalance_date, rebalance_open_date, status)
                         VALUES ('$relationshipNumber', '$previousRebalanceDate', '$rebalanceOpenDate', '$status')";

  // Execute the insert queries
  if ($conn->query($insert_relationship_sql) === TRUE &&
      $conn->query($insert_updates_sql) === TRUE &&
      $conn->query($insert_details_sql) === TRUE) {
      echo "<script>alert('Record inserted successfully!'); window.location.href = 'main.php';</script>";
      exit;
  } else {
      echo "<script>alert('Error inserting record: " . $conn->error . "');</script>";
  }
}

$conn->close();
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
  }

</style>
</head>
<body>
<h2 class="center-heading">Insert Relation</h2>

<div class="insert-form">
  <form method="post" action="insert.php">
    <!-- Relationship Table -->
    <label for="relationship_name">Relationship Name:</label>
    <input type="text" id="relationship_name" name="relationship_name" required><br>

    <label for="relationship_number">Relationship Number:</label>
    <input type="text" id="relationship_number" name="relationship_number" required><br>

    <label for="portfolio_name">Portfolio Name:</label>
    <select id="portfolio_name" name="portfolio_name" required>
        <option value="Portfolio 1">Portfolio 1</option>
        <option value="Portfolio 2">Portfolio 2</option>
    </select><br>

    <label for="investment_banker">Investment Banker:</label>
    <input type="text" id="investment_banker" name="investment_banker" required><br>

    <label for="model_name">Model Name:</label>
    <select id="model_name" name="model_name" required>
        <option value="Sample model">Sample model</option>
        <option value="Tree model">Tree model</option>
    </select><br>

    <label for="portfolio_value">Portfolio Value:</label>
    <input type="text" id="portfolio_value" name="portfolio_value" required><br>

    <!-- Updates Table -->
    <label for="created_by">Created By:</label>
    <input type="text" id="created_by" name="created_by" required><br>

    <!-- Details Table -->
    <label for="previous_rebalance_date">Previous Rebalance Date:</label>
    <input type="date" id="previous_rebalance_date" name="previous_rebalance_date" required><br>

    <label for="rebalance_open_date">Rebalance Open Date:</label>
    <input type="date" id="rebalance_open_date" name="rebalance_open_date" required><br>

    <label for="status">Status:</label>
    <select id="status" name="status" required>
        <option value="Closed">Closed</option>
        <option value="Pending">Pending</option>
        <option value="Rebalance pending">Rebalance pending</option>
    </select><br>

    <button type="submit" name="submit">Submit</button>
    <button class="cancel-button" onclick="window.location.href='main.php'">Cancel</button>
  </form>
</div>

</body>
</html>