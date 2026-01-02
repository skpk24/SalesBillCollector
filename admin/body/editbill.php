<?php
require 'db.php';

$id = $_GET['bill_id'] ?? null;
$message = "";

if (!$id) {
    die("Error: No bill ID specified.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pmt_mode    = $_POST['pmt_mode'];
    $cheque_no   = $_POST['cheque_no'];
    $paid_amt    = $_POST['paid_amt'];
    $pending_amt = $_POST['pending_amt'];
    // Checkboxes are only sent if checked
    $is_full_pmt = isset($_POST['is_full_pmt']) ? 1 : 0; 
    
    $stmt = $pdo->prepare("UPDATE sales_bills SET pmt_mode=:n, cheque_no=:d, paid_amt=:p, pending_amt=:q, is_full_pmt=:f WHERE id=:id");
    $result = $stmt->execute([
        ':n' => $pmt_mode,
        ':d' => $cheque_no,
        ':p' => $paid_amt,
        ':q' => $pending_amt,
        ':f' => $is_full_pmt,
        ':id' => $id
    ]);
    
    $message = $result ? "Bill updated successfully!" : "Update failed.";
    //header('Location: default.php?p=cm9sZXMucGhw'); 
}

// 2. Fetch Current Data to populate form
    $stmt = $pdo->prepare("SELECT * FROM sales_bills WHERE id = ?");
    $stmt->execute([$id]);
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bill) {
        die("Error: Bill not found.");
    }
?>

<!--begin::Quick Example-->

    <h3><a href="default.php?p=ZGF0YS5waHA=" class="alert-link"><< BACK</a>.</h3> Go back to the bills list.
    <?php if ($message): ?>
    <div class="alert alert-success" role="alert">
        <p><strong><?= $message ?></strong></p>
    </div>
    <?php endif; ?>

    <div class="card card-primary card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <h2>Edit Bill # <?= !empty($bill) && !empty($bill['bill_number']) ? htmlspecialchars($bill['bill_number']) : '' ?></h2>
            <div><h2>Bill Amount: ₹<?= !empty($bill) && !empty($bill['bill_amount']) ? htmlspecialchars($bill['bill_amount']) : '' ?></h2></div>
            <div class="card-title">Retailer Name : <?= !empty($bill) && !empty($bill['retailer_name']) ? htmlspecialchars($bill['retailer_name']) : '' ?></div>
            
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <form method="POST">
        <!--begin::Body-->
        <div class="card-body">
            <input type="hidden" name="bill_amount" value="<?= !empty($bill) && !empty($bill['bill_amount']) ? htmlspecialchars($bill['bill_amount']) : 0.0 ?>" />
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Payment Type</label>
                <div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="is_full_pmt" id="typebtnradio1" value="1" autocomplete="off" <?= $bill['is_full_pmt'] == 1 ? 'checked' : '' ?> />
                    <label class="btn btn-outline-primary" for="typebtnradio1">FULL</label>
                    <input type="radio" class="btn-check"  name="is_full_pmt" id="typebtnradio2" value="0" autocomplete="off" <?= $bill['is_full_pmt'] == 0 ? 'checked' : '' ?> />  
                    <label class="btn btn-outline-primary" data-toggle="collapse" data-target="#myTargetDiv" for="typebtnradio2">PART</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Payment Mode</label>
                <div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="pmt_mode" id="btnradio1" value="CASH" autocomplete="off" <?= empty($bill['pmt_mode']) || $bill['pmt_mode'] == 'CASH' ? 'checked' : '' ?> />
                    <label class="btn btn-outline-primary" for="btnradio1">CASH</label>
                    <input type="radio" class="btn-check" name="pmt_mode" id="btnradio2" value="UPI" autocomplete="off" <?= $bill['pmt_mode'] == 'UPI' ? 'checked' : '' ?> />  
                    <label class="btn btn-outline-primary" for="btnradio2">UPI</label>
                    <input type="radio" class="btn-check" name="pmt_mode" id="btnradio3" value="CHEQUE" autocomplete="off" <?= $bill['pmt_mode'] == 'CHEQUE' ? 'checked' : '' ?> />  
                    <label class="btn btn-outline-primary" for="btnradio3">Cheque</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Paid Amount</label>
                <input type="text" class="form-control" readonly name="paid_amt" value="<?= !empty($bill) && !empty($bill['paid_amt']) && $bill['paid_amt'] != 0.00 ? htmlspecialchars($bill['paid_amt']) : htmlspecialchars($bill['bill_amount']) ?>" />
            </div>
            <div class="mb-3" id="myTargetDiv">
                <label for="exampleInputPassword1" class="form-label">Pending Amount</label>
                <input type="text" class="form-control" readonly name="pending_amt" value="<?= !empty($bill) && !empty($bill['pending_amt']) ? htmlspecialchars($bill['pending_amt']) : 0.0 ?>" />
            </div>
            <div class="mb-3" id="myTargetDiv1">
                <label for="exampleInputPassword1" class="form-label">Cheque Number</label>
                <input type="text" class="form-control" name="cheque_no" value="<?= !empty($bill) && !empty($bill['cheque_number']) ? htmlspecialchars($bill['cheque_number']) : '' ?>" />
            </div>
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <!--end::Footer-->
        </form>
        <!--end::Form-->
    </div>
<!--end::Quick Example-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function togglePendingAmount() {
            if ($('#typebtnradio2').is(':checked')) {
                $('#myTargetDiv').show();
                $('input[name="pending_amt"]').val(0.0);
                $('input[name="paid_amt"]').prop('readonly', false);
                $('input[name="pending_amt"]').prop('readonly', false);

                $('input[name="paid_amt"]').on('keypress', function (e) {
                    var ch = String.fromCharCode(e.which);
                    if (!/[0-9]/.test(ch)) {
                        e.preventDefault();
                    }
                });
            } else {
                $('#myTargetDiv').hide();
                $('input[name="paid_amt"]').val($('input[name="bill_amount"]').val());
                $('input[name="paid_amt"]').prop('readonly', true);
                $('input[name="pending_amt"]').val(0.0);
            }
        }

        function toggleChequeNumber() {
            if ($('#btnradio3').is(':checked')) {
                $('#myTargetDiv1').show();
                $('input[name="cheque_number"]').val('');
            } else {
                $('#myTargetDiv1').hide();
                $('input[name="cheque_number"]').val('');
            }
        }

        function updatePendingAmount() {
            $('input[name="paid_amt"]').on('keyup change', function() {
                const val = $(this).val();
                const billAmount = parseFloat($('input[name="bill_amount"]').val()) || 0.00;
                const paidAmount = parseFloat(val) || 0.00;
                const pendingAmount = parseFloat(Math.abs(billAmount - paidAmount)).toFixed(2);

                $('input[name="paid_amt"]').val(parseFloat(val).toFixed(2));
                $('input[name="pending_amt"]').val(pendingAmount);
                /*
                if(pendingAmount >= billAmount){
                    alert('Paid Amount cannot be greater than or equal to Bill Amount');
                    $('input[name="paid_amt"]').val(billAmount.toFixed(2));
                    $('input[name="pending_amt"]').val(0.00);
                    return;
                }*/
            });
        }
        // Initial check
        togglePendingAmount();
        toggleChequeNumber();

        // Bind change event
        $('input[name="is_full_pmt"]').change(function() {
            togglePendingAmount();
        });

        /*
        $('input[name="paid_amt"]').change(function() {
            updatePendingAmount();
        });*/

        $('input[name="pmt_mode"]').change(function() {
            toggleChequeNumber();
        });
    });
</script>