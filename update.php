<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $updateId = $_POST['update_id'];
    $relationshipName = $_POST['relationship_name'];
    $relationshipNumber = $_POST['relationship_number'];
    $portfolioName = $_POST['portfolio_name'];
    $investmentBanker = $_POST['investment_banker'];
    $modelName = $_POST['model_name'];
    $portfolioValue = $_POST['portfolio_value'];
    
    $createdBy = $_POST['created_by'];
    $checkedBy = $_POST['checked_by'];
    
    $previousRebalanceDate = $_POST['previous_rebalance_date'];
    $rebalanceOpenDate = $_POST['rebalance_open_date'];
    $status = $_POST['status'];

    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "sample";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $update_sql = "UPDATE relationship
                   SET relationship_name = '$relationshipName', portfolio_name = '$portfolioName', 
                       investment_banker = '$investmentBanker', model_name = '$modelName', 
                       portfolio_value = '$portfolioValue'
                   WHERE relationship_number = '$updateId'";

    $update_updates_sql = "UPDATE updates
                           SET created_by = '$createdBy', 
                               updated_on = NOW(), checked_by = '$checkedBy'
                           WHERE relationship_number = '$updateId'";

    $update_details_sql = "UPDATE details
                           SET previous_rebalance_date = '$previousRebalanceDate', 
                               rebalance_open_date = '$rebalanceOpenDate', status = '$status'
                           WHERE relationship_number = '$updateId'";

    if ($conn->query($update_sql) === TRUE && 
        $conn->query($update_updates_sql) === TRUE && 
        $conn->query($update_details_sql) === TRUE) {
        header("Location: main.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
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
  </style>
</head>
<body>
<h2 class="center-heading">Update Relation</h2>
<?php
if (isset($_POST['update_id'])) {
    $updateId = $_POST['update_id'];
} else {
    header("Location: main.php");
    exit;
}

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
        WHERE r.relationship_number = '$updateId'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $relationshipName = $row['relationship_name'];
    $relationshipNumber = $row['relationship_number'];
    $portfolioName = $row['portfolio_name'];
    $investmentBanker = $row['investment_banker'];
    $modelName = $row['model_name'];
    $portfolioValue = $row['portfolio_value'];
    
    $createdBy = $row['created_by'];
    $checkedBy = $row['checked_by'];
    
    $previousRebalanceDate = $row['previous_rebalance_date'];
    $rebalanceOpenDate = $row['rebalance_open_date'];
    $status = $row['status'];
} else {
    header("Location: main.php");
    exit;
}

$conn->close();
?>

<div class="insert-form">
    <form method="post" action="update.php">
        <input type="hidden" name="update_id" value="<?php echo $updateId; ?>">
        
        <label for="relationship_name">Relationship Name:</label>
        <input type="text" id="relationship_name" name="relationship_name" placeholder="Relationship Name" value="<?php echo $relationshipName; ?>" required><br>
        
        <label for="relationship_number">Relationship Number:</label>
        <input type="text" id="relationship_number" name="relationship_number" placeholder="Relationship Number" value="<?php echo $relationshipNumber; ?>" required><br>
        
        <label for="portfolio_name">Portfolio Name:</label>
        <input type="text" id="portfolio_name" name="portfolio_name" placeholder="Portfolio Name" value="<?php echo $portfolioName; ?>" required><br>
        
        <label for="investment_banker">Investment Banker:</label>
        <input type="text" id="investment_banker" name="investment_banker" placeholder="Investment Banker" value="<?php echo $investmentBanker; ?>" required><br>
        
        <label for="model_name">Model Name:</label>
        <input type="text" id="model_name" name="model_name" placeholder="Model Name" value="<?php echo $modelName; ?>" required><br>
        
        <label for="portfolio_value">Portfolio Value:</label>
        <input type="text" id="portfolio_value" name="portfolio_value" placeholder="Portfolio Value" value="<?php echo $portfolioValue; ?>" required><br>
        
        <!-- Update fields from updates table -->
        <label for="created_by">Created By:</label>
        <input type="text" id="created_by" name="created_by" placeholder="Created By" value="<?php echo $createdBy; ?>" required><br>
        
        <label for="checked_by">Checked By:</label>
        <input type="text" id="checked_by" name="checked_by" placeholder="Checked By" value="<?php echo $checkedBy; ?>"><br>
        
        <!-- Update fields from details table -->
        <label for="previous_rebalance_date">Previous Rebalance Date:</label>
        <input type="date" id="previous_rebalance_date" name="previous_rebalance_date" value="<?php echo $previousRebalanceDate; ?>" required><br>
        
        <label for="rebalance_open_date">Rebalance Open Date:</label>
        <input type="date" id="rebalance_open_date" name="rebalance_open_date" value="<?php echo $rebalanceOpenDate; ?>" required><br>
        
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" placeholder="Status" value="<?php echo $status; ?>"><br>
        
        <button type="submit" name="update_submit">Update</button>
        <button class="cancel-button" onclick="window.location.href='main.php'" type="button">Cancel</button>
    </form>
</div>

</body>
</html>

