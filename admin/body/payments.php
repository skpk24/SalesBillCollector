<?php
require 'db.php';

include('payment_filters.php');

//$result = $stmt->get_result();
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = json_decode(json_encode($bills), true);

$salesmen = array_values(array_unique(array_column($data, 'salesman')));
$beats = array_values(array_unique(array_column($data, 'beat_name')));
$bill_dates = array_values(array_unique(array_column($data, 'bill_date')));
// For retailers, we filter out the empty strings found in your file
$retailers = array_filter(array_unique(array_column($data, 'retailer_name')));

?>

<div class="row">
  <div class="col-md-12">

    <div class="card  text-white  mb-2">
      <div class="card-header bg-primary"><div class="card-title">Filter Width</div></div>
      <div class="card-body">
        <form method="get" class="filter">
          <input type="hidden" name="p" value="<?php echo htmlspecialchars($_GET['p']); ?>">
        <div class="row">
          <div class="col-2">
            <select id="salesmanSelect" name="salesman" class="form-select form-select-sm"><option value="">All Salesmen</option></select>
          </div>
          <div class="col-4">
            <label>
                <input type="text" id="from" name="from_date" class="form-control" placeholder="From Date/time" value="<?php echo !empty($from_date) ? htmlspecialchars($from_date) : ''; ?>">
            </label>
            <label>
                <input type="text" id="to" name="to_date" class="form-control" placeholder="To Date/time" value="<?php echo !empty($to_date) ? htmlspecialchars($to_date) : ''; ?>">
            </label>
          </div>
          <div class="col-3">
              <input type="text" name="bill_number" class="form-control" placeholder="Bill Number" value="<?php echo htmlspecialchars($bill_number); ?>">
          </div>
          <div class="col-2">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="<?php echo strtok($_SERVER['REQUEST_URI'], '?').'?p='.$_GET['p']; ?>">Reset</a>
          </div>
        </div>
        </form>
      </div>
    </div>

    <div class="card text-white mb-4">
      <div class="card-header bg-primary"><h3 class="card-title">Payments</h3></div>
      <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Bill No.</th>
                <th>Bill Date</th>
                <th>Retailer Name</th>
                <th>Beat Name</th>
                <th>Salesman</th>
                <th>Bill Amt</th>
                <th>Paid</th>
                <th>Cash</th>
                <th>UPI</th>
                <th>Cheque</th>
                <th>Is Full</th>
              </tr>
          </thead>
          <tbody>
            
          <?php    
            foreach ($bills as $r): ?>
            <tr>
              <td><a href="default.php?p=ZWRpdGJpbGwucGhw&bill_id=<?= $r['id'] ?>"><?= htmlspecialchars($r['bill_number']) ?></a></td>
              <td><?= htmlspecialchars($r['bill_date']) ?></td>
              <td><?= !empty($r['retailer_name']) ? htmlspecialchars($r['retailer_name']) : '' ?></td>
              <td><?= !empty($r['beat_name']) ? htmlspecialchars($r['beat_name']) : '' ?></td>
              <td><?= !empty($r['salesman']) ? htmlspecialchars($r['salesman']) : '' ?></td>
              <td><?= !empty($r['bill_amount']) ? htmlspecialchars($r['bill_amount']) : '' ?></td>
              <td><?= !empty($r['paid_amt']) ? htmlspecialchars($r['paid_amt']) : '' ?></td>
              <td><?= !empty($r['cash']) ? htmlspecialchars($r['cash']) : '' ?></td>
              <td><?= !empty($r['upi']) ? htmlspecialchars($r['upi']) : '' ?></td>
              <td><?= !empty($r['cheque']) ? htmlspecialchars($r['cheque']) : '' ?></td>
              <td><?= !empty($r['is_full_pmt']) ? ($r['is_full_pmt'] == 1? 'Yes' : 'No') : 'No' ?></td>
            </tr>
          <?php endforeach; ?>
          
          </tbody>
        </table>
        <table class="table table-hover text-nowrap">
          <tr>
            <td colspan="11">
              <div class="float-end">
                <form action="dataExporter.php" method="GET">
                    <input type="hidden" name="f" value="p">
                    <input type="hidden" name="fields" value="bill_number,bill_date,retailer_name,beat_name,salesman,bill_amount,paid_amt,pending_amt,cash,upi,cheque,is_full_pmt,pmt_mode,cheque_no,created_at,updated_at">
                    <input type="hidden" name="headings" value="Bill Number, Bill Date, Retailer Name, Beat Name, Salesman, Bill Amount, Paid, Pending, Cash, UPI, Cheque, Is Full, Mode, Ref No., Created At, Updated At">
                    <input type="hidden" name="fn" value="<?php echo !empty($_GET['salesman']) ? htmlspecialchars($_GET['salesman']) : ''; ?>">
                    <input type="hidden" name="p" value="<?php echo !empty($_GET['p']) ? htmlspecialchars($_GET['p']) : ''; ?>">
                    <input type="hidden" name="bill_number" value="<?php echo !empty($_GET['bill_number']) ? htmlspecialchars($_GET['bill_number']) : ''; ?>">
                    <input type="hidden" name="bill_date" value="<?php echo !empty($_GET['bill_date']) ? htmlspecialchars($_GET['bill_date']) : ''; ?>">
                    <input type="hidden" name="retailer_name" value="<?php echo !empty($_GET['retailer_name']) ? htmlspecialchars($_GET['retailer_name']) : ''; ?>">
                    <input type="hidden" name="beat_name" value="<?php echo !empty($_GET['beat_name']) ? htmlspecialchars($_GET['beat_name']) : ''; ?>">
                    <input type="hidden" name="salesman" value="<?php echo !empty($_GET['salesman']) ? htmlspecialchars($_GET['salesman']) : ''; ?>">
                    <input type="hidden" name="pmt_mode" value="<?php echo !empty($_GET['pmt_mode']) ? htmlspecialchars($_GET['pmt_mode']) : ''; ?>">
                    <input type="hidden" name="is_full_pmt" value="<?php echo !empty($_GET['is_full_pmt']) ? htmlspecialchars($_GET['is_full_pmt']) : ''; ?>">
                    <input type="hidden" name="cheque_no" value="<?php echo !empty($_GET['cheque_no']) ? htmlspecialchars($_GET['cheque_no']) : ''; ?>">
                    <input type="hidden" name="cash" value="<?php echo !empty($_GET['cash']) ? htmlspecialchars($_GET['cash']) : ''; ?>">
                    <input type="hidden" name="upi" value="<?php echo !empty($_GET['upi']) ? htmlspecialchars($_GET['upi']) : ''; ?>">
                    <input type="hidden" name="cheque" value="<?php echo !empty($_GET['cheque']) ? htmlspecialchars($_GET['cheque']) : ''; ?>">
                    <input type="hidden" name="created_at" value="<?php echo !empty($_GET['created_at']) ? htmlspecialchars($_GET['created_at']) : ''; ?>">
                    <input type="hidden" name="updated_at" value="<?php echo !empty($_GET['updated_at']) ? htmlspecialchars($_GET['updated_at']) : ''; ?>">
                    <input type="hidden" name="from_date" value="<?php echo !empty($from_date) ? htmlspecialchars($from_date) : ''; ?>">
                    <input type="hidden" name="to_date" value="<?php echo !empty($to_date) ? htmlspecialchars($to_date) : ''; ?>">
                    <button type="Submit" class="btn btn-primary" id="exportBtn">Export As CSV</button>
                </form>
              </div>
            </td>
          </tr>   
        </table>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Inject PHP arrays into Javascript
    const billDates = <?php echo json_encode($bill_dates); ?>;
    const salesmen = <?php echo json_encode($salesmen); ?>;
    const beats = <?php echo json_encode($beats); ?>;
    const retailers = <?php echo json_encode(array_values($retailers)); ?>;

    

    const salesman_select = <?php echo !empty($_GET['salesman']) ? "'".$_GET['salesman']."'" : "''"; ?>;
    const retailer_select = <?php echo !empty($_GET['retailer_name']) ? "'".$_GET['retailer_name']."'" : "''"; ?>;
    const bill_date_select = <?php echo !empty($_GET['bill_date']) ? "'".$_GET['bill_date']."'" : "''"; ?>; 
    const beat_name_select = <?php echo !empty($_GET['beat_name']) ? "'".$_GET['beat_name']."'" : "''"; ?>;

    // 2. Helper function to populate a select
    function populateSelect(selector, dataList, selectedValues) {
        const $select = $(selector);
        $.each(dataList, function(i, value) {
            const $option = new Option(value, value);
            if (selectedValues.includes(value)) {
                $option.selected = true;
            }
            $select.append($option);
        });
    }

    // 3. Execute population
    populateSelect('#salesmanSelect', salesmen, salesman_select);
    populateSelect('#beatSelect', beats, beat_name_select);
    populateSelect('#retailerSelect', retailers, retailer_select);
    populateSelect('#bill_dateSelect', billDates, bill_date_select);
});

$(function () {
    var dateFormat = "yy-mm-dd";

    $("#from").datetimepicker({
        dateFormat: dateFormat,
        changeMonth: true,
        changeYear: true,
        changeTime: true,
        defaultTime:'00:00',
        format:'d/m/Y H:i',
	      formatDate:'Y/m/d',
        onClose: function (selectedDate) {
            $("#to").datetimepicker("option", "minDate", selectedDate);
        },
        onSelect: function (dateText) {
            var d = $(this).datepicker("getDate");   // JS Date
            if (d) {
                var ts = Math.floor(d.getTime() / 1000); // Unix timestamp (sec)
                $("#from_ts").val(ts);
            }
        }
    });

    $("#to").datetimepicker({
        dateFormat: dateFormat,
        changeMonth: true,
        changeYear: true,
        changeTime: true,
        defaultTime:'23:00',
        format:'d/m/Y H:i',
	      formatDate:'Y/m/d',
        onClose: function (selectedDate) {
            $("#from").datetimepicker("option", "maxDate", selectedDate);
        },
        onSelect: function (dateText) {
            var d = $(this).datetimepicker("getDate");   // JS Date
            if (d) {
                // set to end of day for inclusive range
                alert("Setting to date to end of day");
                d.setHours(23, 59, 59, 999);
                var ts = Math.floor(d.getTime() / 1000); // Unix timestamp (sec)
                $("#to_ts").val(ts);
            }
        }
    });
});

</script>