<div class="form-step">
    <h2>Postcode <span class="required">*</span></h2>
    <div class="form-group">
        <input type="text" 
               name="postcode" 
               placeholder="SW1A 1AA" 
               title="Please enter a valid UK postcode" 
               required
               value="<?php echo isset($saved_data['postcode']) ? esc_attr($saved_data['postcode']) : ''; ?>"
               class="form-control">
        <p class="info-text">We need your postcode to provide accurate quotes for your area.</p>
    </div>
</div>

<style>
.form-control {
    width: 100%;
    max-width: 300px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    text-transform: uppercase;
}

.info-text {
    margin-top: 8px;
    color: #666;
    font-size: 14px;
}

.required {
    color: #ff0000;
}
</style>
