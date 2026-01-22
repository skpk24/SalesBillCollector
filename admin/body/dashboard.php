
<div class="row"><div class="col-md-12">
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Upload File (only .csv)</h3></div>
    <div class="card-body table-responsive p-0">
    <br/>
    <form action="default.php?p=ZGFzaGJvYXJkLnBocA==" method="post" enctype="multipart/form-data">
            <span>Select CSV file to upload:</span>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload and Read" name="submit">
    </form>
    <br>
    <strong>Note:</strong> The CSV file should have the following columns:
    <strong>Bill Number, Bill Date,	Retailer Name,	Bill Amount, Salesman,	Beat Name</strong><br/>
    Existing Bill Numbers will be skipped during import to avoid duplicates.
    </div>
</div>
</div>
</div>
<br/>
<?php
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Upload the file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    
        $dbFields = 
                [
                    "Bill Number"   => "bill_number" ,
                    "Bill Date"     => "bill_date",
                    "Retailer Name" => "retailer_name",
                    "Beat Name"     => "beat_name",
                    "Salesman"      => "salesman",
                    "Bill Amount"   => "bill_amount"
                ];

        $fields = [];

        $sql = "SELECT bill_number FROM sales_bills";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $billNumbers = $stmt->fetchAll(PDO::FETCH_COLUMN);

        //echo " DATA ".json_encode($billNumbers)."<br>";
        $rowcounter = -1;

        echo "<div class=\"row\"><div class=\"col-md-12\"><div class=\"card mb-4\">";
        echo "<form action=\"dataImporter.php\" method=\"POST\" onsubmit=\"return confirm('Do you really want to Import the Data?');\">";
        echo "<input type=\"hidden\" name=\"user_id\" value=\"". htmlspecialchars($user_id). "\"/>";
        echo " <div class=\"card-header bg-primary text-white\">";
        echo "<h3 class=\"card-title mb-0\">The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.</h3>";
        //echo "<button type=\"submit\" class=\"btn btn-primary float-end\">Import All</button><br>";
        echo "</div><div class=\"card-body p-0\">";
        // Read the file (Specifically for CSV files like yours)
        if (($handle = fopen($target_file, "r")) !== FALSE) {
            
            echo "<table border='1' class=\"table table-striped\">";
            $is_header = true;
            $is_body_start = true;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if(!empty($billNumbers) && is_array($billNumbers) && in_array($data[0], $billNumbers)){
                    continue; // skip existing bill numbers
                }else{
                    $rowcounter++;
                }
                //$rowcounter++;
                if($is_header){
                    echo "<thead>";
                }else{
                    if($is_body_start)
                    echo "<tbody>";
                }
                echo "<tr>";
                $counter = 0;
                $headerCounter = 0;
                foreach ($data as $cell) {
                    if($is_header){
                    $fields[$headerCounter++] = $dbFields[$cell] ?? null;
                    }
                    echo "<td>" . htmlspecialchars($cell). "</td>";
                    if(!$is_header){
                        echo "<input type=\"hidden\" name=\"".  $fields[$counter++] . "[]\" value=\"". htmlspecialchars($cell). "\"/>";
                    }
                }
                echo "</tr>";
                if($is_header){
                    echo "</thead>";
                }
                $is_header = false;
                $is_body_start = false;
            }
            fclose($handle);
            echo "</tbody>";
            echo "</table>";
        }
        echo "<div class=\"card-footer\">";
        echo "Total New Records to Import: ". htmlspecialchars($rowcounter). "";
        if($rowcounter > 0){
            echo "<button type=\"submit\" class=\"btn btn-primary float-end\">Import All</button>";
        }
        echo "</div></div></form></div></div></div>";

        //echo "<div>". json_encode($fields1). "</div>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>