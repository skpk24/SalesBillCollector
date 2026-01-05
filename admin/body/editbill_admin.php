<form method="POST">
    <!--begin::Body-->
    <div class="card-body">
        <input type="hidden" name="originalCash" value="<?= !empty($bill) && !empty($bill['cash']) ? htmlspecialchars($bill['cash']) : 0.0 ?>" />
            <input type="hidden" name="originalUpi" value="<?= !empty($bill) && !empty($bill['upi']) ? htmlspecialchars($bill['upi']) : 0.0 ?>" />
            <input type="hidden" name="originalCheque" value="<?= !empty($bill) && !empty($bill['cheque']) ? htmlspecialchars($bill['cheque']) : 0.0 ?>" />
            <input type="hidden" name="bill_number" value="<?= !empty($bill) && !empty($bill['bill_number']) ? htmlspecialchars($bill['bill_number']) : '' ?>" />
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
                <input type="radio" class="btn-check" name="pmt_mode" id="btnradio4" value="NONE" autocomplete="off" <?= $bill['pmt_mode'] == 'NONE' ? 'checked' : '' ?> />  
                <label class="btn btn-outline-primary" for="btnradio4">NONE</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Paid Amount</label>
            <input type="number" step="any" placeholder="0.00" class="form-control"  name="paid_amt" value="<?= !empty($bill) && !empty($bill['paid_amt']) && $bill['paid_amt'] != 0.00 ? htmlspecialchars($bill['paid_amt']) : 0.00 ?>" />
        </div>
        <div class="mb-3" id="myTargetDiv">
            <label for="exampleInputPassword1" class="form-label">Pending Amount</label>
            <input type="number" step="any" placeholder="0.00" class="form-control"  name="pending_amt" value="<?= !empty($bill) && !empty($bill['pending_amt']) ? htmlspecialchars($bill['pending_amt']) : 0.0 ?>" />
        </div>
        
        <div class="mb-3" id="myTargetDiv1">
            <label for="exampleInputPassword1" class="form-label">Cheque Number / UPI No.</label>
            <input type="text" style="text-transform: uppercase;" class="form-control" name="cheque_no" value="<?= !empty($bill) && !empty($bill['cheque_no']) ? htmlspecialchars($bill['cheque_no']) : '' ?>" />
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Retailer Name</label>
            <input type="text" class="form-control" name="retailer_name" value="<?= !empty($bill) && !empty($bill['retailer_name']) ? htmlspecialchars($bill['retailer_name']) : '' ?>" />
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Beat Name</label>
            <input type="text" class="form-control"  name="beat_name" value="<?= !empty($bill) && !empty($bill['beat_name']) ? htmlspecialchars($bill['beat_name']) : '' ?>" />     
        </div> 
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Salesman</label>
            <select name="salesman" class="form-control" required>
              <option value="">Select Salesman</option>
              <?php if($bill['user_id']): ?>
                <option value="<?= $bill['user_id']."#".$bill['salesman'] ?>" selected><?= htmlspecialchars($bill['salesman']) ?></option>   
              <?php endif; ?>
              <?php foreach ($staffUsers as $u): ?>
                <?php if($bill['user_id'] != $u['id']): ?>
                <option value="<?= $u['id']."#".$u['fullname'] ?>"><?= htmlspecialchars($u['fullname']) ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!--end::Body-->
    <!--begin::Footer-->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    <!--end::Footer-->
</form>

