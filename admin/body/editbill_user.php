<!--begin::Form-->
        <form method="POST">
        <!--begin::Body-->
        <div class="card-body">
            <input type="hidden" name="bill_amount" value="<?= !empty($bill) && !empty($bill['bill_amount']) ? htmlspecialchars($bill['bill_amount']) : 0.0 ?>" />
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Payment Type</label>
                <div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
                    <?php if ($bill['is_full_pmt'] != 0): ?>
                    <input type="radio" class="btn-check" name="is_full_pmt" id="typebtnradio1" value="1" autocomplete="off" <?= $bill['is_full_pmt'] == 1 ? 'checked' : '' ?> />
                    <label class="btn btn-outline-primary" for="typebtnradio1">FULL</label>
                    <?php endif; ?>
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
                <?php if ($is_paid): ?>
                    <span>₹<?= !empty($bill) && !empty($bill['paid_amt']) && $bill['paid_amt'] != 0.00 ? htmlspecialchars($bill['paid_amt']) : '' ?></span>
                <?php else: ?>
                    <input type="number" step="any" placeholder="0.00" class="form-control" readonly name="paid_amt" value="<?= !empty($bill) && !empty($bill['paid_amt']) && $bill['paid_amt'] != 0.00 ? htmlspecialchars($bill['paid_amt']) : htmlspecialchars($bill['bill_amount']) ?>" />
                <?php endif; ?>
                <input type="hidden" name="original_paid_amt" value="<?= !empty($bill) && !empty($bill['paid_amt']) ? htmlspecialchars($bill['paid_amt']) : 0.0 ?>" />
            </div>
            <?php if (!$is_paid): ?>
            <div class="mb-3" id="myTargetDiv">
                <label for="exampleInputPassword1" class="form-label">Pending Amount</label>
                <input type="number" step="any" placeholder="0.00" class="form-control" readonly name="pending_amt" value="<?= !empty($bill) && !empty($bill['pending_amt']) ? htmlspecialchars($bill['pending_amt']) : 0.0 ?>" />
                <input type="hidden" name="original_pending_amt" value="<?= !empty($bill) && !empty($bill['pending_amt']) ? htmlspecialchars($bill['pending_amt']) : 0.0 ?>" />
            </div>
            <?php endif; ?>
            <div class="mb-3" id="myTargetDiv1">
                <label for="exampleInputPassword1" class="form-label">Cheque Number / UPI No.</label>
                <?php if ($is_paid): ?>
                    <br/><span><?= !empty($bill) && !empty($bill['cheque_no']) ? htmlspecialchars($bill['cheque_no']) : '' ?></span>
                <?php else: ?>
                    <input type="text" style="text-transform: uppercase;" class="form-control" name="cheque_no" value="<?= !empty($bill) && !empty($bill['cheque_no']) ? htmlspecialchars($bill['cheque_no']) : '' ?>" />
                <?php endif; ?>
                
                <input type="hidden" name="original_cheque_no" value="<?= !empty($bill) && !empty($bill['cheque_no']) ? htmlspecialchars($bill['cheque_no']) : '' ?>" />
            </div>
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="card-footer">
            <?php if (!$is_paid): ?>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php endif; ?>
        </div>
        <!--end::Footer-->
        </form>
        <!--end::Form-->
    

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        const original_paid_amt = parseFloat($('input[name="original_paid_amt"]').val()) || 0.00;
        const original_pending_amt = parseFloat($('input[name="original_pending_amt"]').val()) || 0.00;
        const original_cheque_no = $('input[name="original_cheque_no"]').val() || '';


        function togglePendingAmount() {
            if ($('#typebtnradio2').is(':checked')) {
                $('#myTargetDiv').show();
                $('input[name="pending_amt"]').val(original_pending_amt>0.00 ? original_pending_amt : '');
                $('input[name="paid_amt"]').val(original_paid_amt>0.00 ? original_paid_amt : '');
                $('input[name="paid_amt"]').prop('readonly', false);
                $('input[name="pending_amt"]').prop('readonly', false);
                $('input[name="paid_amt"]').prop('required', true).addClass('highlight');
                $('input[name="pending_amt"]').prop('required', true).addClass('highlight');
            } else {
                $('#myTargetDiv').hide();
                $('input[name="paid_amt"]').val($('input[name="bill_amount"]').val());
                $('input[name="paid_amt"]').prop('readonly', true);
                $('input[name="pending_amt"]').val(original_pending_amt>0.00 ? original_pending_amt : '');
                $('input[name="pending_amt"]').prop('required', false).removeClass('highlight');
            }
        }

        function toggleChequeNumber() {
            if ($('#btnradio3').is(':checked') || $('#btnradio2').is(':checked')) {
                $('#myTargetDiv1').show();
                $('input[name="cheque_no"]').prop('required', true).addClass('highlight');
                $('input[name="cheque_no"]').val(original_cheque_no);
            } else {
                $('#myTargetDiv1').hide();
                $('input[name="cheque_no"]').prop('required', false).removeClass('highlight');
                $('input[name="cheque_no"]').val(original_cheque_no);
            }
        }

        function checkValues(){
            const billAmount = parseFloat($('input[name="bill_amount"]').val()) || 0.00;
            const paidAmount = parseFloat($('input[name="paid_amt"]').val()) || 0.00;
            const pendingAmount = parseFloat(Math.abs(billAmount - paidAmount)).toFixed(2);
            if(pendingAmount >= billAmount){
                alert('Paid Amount cannot be greater than or equal to Bill Amount');
                $('input[name="paid_amt"]').val(billAmount.toFixed(2));
                $('input[name="pending_amt"]').val(0.00);
                return;
            }
        }

        // Initial check
        togglePendingAmount();
        toggleChequeNumber();

        // Bind change event
        $('input[name="is_full_pmt"]').change(function() {
            togglePendingAmount();
        });

        
        $('input[name="paid_amt"]').on('input', function() {
            //checkValues();
            let billAmount = parseFloat($('input[name="bill_amount"]').val()) || 0.00;
            if(original_pending_amt > 0.00){
                billAmount = parseFloat(Math.abs(billAmount - original_paid_amt));
            }
            const paidAmount = parseFloat($(this).val()) || 0.00;
            if(paidAmount > billAmount){
                alert('Paid Amount cannot be greater than Bill Amount');
                $(this).val(billAmount.toFixed(2));
                $('input[name="pending_amt"]').val(0.00);
                return;
            }
            const pendingAmount = parseFloat(Math.abs(billAmount - paidAmount)).toFixed(2);
            $('input[name="pending_amt"]').val(pendingAmount);
        });
        

        $('input[name="pmt_mode"]').change(function() {
            toggleChequeNumber();
        });
    });
</script>