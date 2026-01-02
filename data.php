<?php
require __DIR__ . '/admin/db.php';

include(__DIR__ . '/report_filters.php');

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
    <div class="card">
      <div class="card-header"><h3 class="card-title">Sales Bills</h3></div>
      <div class="card-body table-responsive p-0">
        <form method="get" class="filter">
          <input type="hidden" name="p" value="<?php echo htmlspecialchars($_GET['p']); ?>">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Bill Number</th>
                <th>Bill Date</th>
                <th>Retailer Name</th>
                <th>Beat Name</th>
                <th>Bill Amt</th>
                <th>Paid Amt</th>
                <th>Pending Amt</th>
                <th>Is Full</th>
                <th>Pmt Mode</th>
                <th>Cheque No.</th>
              </tr>
          </thead>
          <tbody>
            <tr>
                <td><input type="text" name="bill_number" class="form-control"   value="<?php echo htmlspecialchars($bill_number); ?>"></td>
                <td>
                    <select name="bill_date" class="form-select form-select-sm" id="bill_dateSelect"><option value="">All Bill Dates</option></select>
                </td>
                <td>
                  <select id="retailerSelect" name="retailer_name" class="form-select form-select-sm"><option value="">All Retailers</option></select>
                </td>
                <td>
                  <select id="beatSelect" name="beat_name" class="form-select form-select-sm"><option value="">All Beats</option></select>
                </td>
                <td><input type="text" name="bill_amount" class="form-control" value="<?php echo htmlspecialchars($bill_amount); ?>"></td>
                <td>
                    <select name="is_full_pmt" class="form-select form-select-sm">
                        <option value="">Any</option>
                        <option value="1" <?php if ($is_full_pmt === '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if ($is_full_pmt === '0') echo 'selected'; ?>>No</option>
                    </select>
                </td>
                <td>
                  <select name="pmt_mode" class="form-select form-select-sm">
                        <option value="">Any</option>
                        <option value="CASH" <?php if ($pmt_mode === 'CASH') echo 'selected'; ?>>CASH</option>
                        <option value="UPI" <?php if ($pmt_mode === 'UPI') echo 'selected'; ?>>UPI</option>
                        <option value="CHEQUE" <?php if ($pmt_mode === 'CHEQUE') echo 'selected'; ?>>CHEQUE</option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?php echo strtok($_SERVER['REQUEST_URI'], '?').'?p='.$_GET['p']; ?>">Reset</a>
                </td>
            </tr>
          <?php    
            foreach ($bills as $r): ?>
            <tr>
              <td>
                <?php if(!empty($r['paid_amt']) && $r['bill_amount'] == $r['paid_amt']){?>
                  <?= htmlspecialchars($r['bill_number']) ?>
                <?php }else{?>
                  <a href="default.php?p=ZWRpdGJpbGwucGhw&bill_id=<?= $r['id'] ?>">
                    <?= htmlspecialchars($r['bill_number']) ?>
                  </a>
                <?php } ?>
              </td>
              <td><?= htmlspecialchars($r['bill_date']) ?></td>
              <td><?= !empty($r['retailer_name']) ? htmlspecialchars($r['retailer_name']) : '' ?></td>
              <td><?= !empty($r['beat_name']) ? htmlspecialchars($r['beat_name']) : '' ?></td>
              <td><?= !empty($r['bill_amount']) ? htmlspecialchars($r['bill_amount']) : '' ?></td>
              <td><?= !empty($r['paid_amt']) ? htmlspecialchars($r['paid_amt']) : '' ?></td>
              <td><?= !empty($r['pending_amt']) ? htmlspecialchars($r['pending_amt']) : '' ?></td>
              <td><?= !empty($r['is_full_pmt']) ? ($r['is_full_pmt'] == 1? 'Yes' : 'No') : '' ?></td>
              <td><?= !empty($r['pmt_mode']) ? htmlspecialchars($r['pmt_mode']) : '' ?></td>
              <td><?= !empty($r['cheque_no']) ? htmlspecialchars($r['cheque_no']) : '' ?></td>
            </tr>
          <?php endforeach; ?>
          
          </tbody>
        </table>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
</script>