<div class="form-step">
    <div class="ff-el-group">
        <div class="ff-el-input--label ff-el-is-required ">
            <label for="phone" aria-label="Phone Number"><h2>Phone Number<span class="required">&nbsp;*</span></h2></label>
        </div>
        <div class="ff-el-input--content">
            <input name="phone" 
                   class="ff-el-form-control ff-el-phone" 
                   type="tel" 
                   placeholder="Mobile Number" 
                   id="phone" 
                   inputmode="tel" 
                   required
                   value="<?php echo isset($saved_data['phone']) ? esc_attr($saved_data['phone']) : ''; ?>">
        </div>
    </div>
    
    <div class="ff-el-group">
        <div class="ff-el-input--label ff-el-is-required">
            <label for="email" aria-label="Email address"><h2>Email address<span class="required">&nbsp;*</span></h2></label>
        </div>
        <div class="ff-el-input--content">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="ff-el-form-control" 
                   placeholder="Email address" 
                   required
                   value="<?php echo isset($saved_data['email']) ? esc_attr($saved_data['email']) : ''; ?>">
        </div>
    </div>
    
    <div class="submit-button-wrapper">
        <button class="getty-button ff-btn ff-btn-submit next-button" type="submit" name="submit" data-next-step="<?php echo isset($current_step_data['next']) ? esc_attr($current_step_data['next']) : 'complete'; ?>">
            Compare Quotes
        </button>
    </div>
    
    <div class="ff-el-group">
        <div class="ff-el-input--content">
            <div class="ff-el-form-check ff-el-form-check- <?php echo (isset($saved_data['marketing_consent']) && $saved_data['marketing_consent'] == '1') ? 'ff_item_selected' : ''; ?>">
                <label class="ff-el-form-check-label" for="marketing_consent">
                    <input type="checkbox" 
                           name="marketing_consent" 
                           class="ff-el-form-check-input ff-el-form-check-checkbox" 
                           value="1" 
                           id="marketing_consent" 
                           aria-label="Receive Latest Promos & Offers on Life Insurance"
                           <?php echo (isset($saved_data['marketing_consent']) && $saved_data['marketing_consent'] == '1') ? 'checked' : 'checked'; ?>>
                    Receive Latest Promos &amp; Offers on Life Insurance
                </label>
            </div>
        </div>
    </div>
    
    <div class="ff-el-group ff-custom_html" tabindex="-1">
        <p style="text-align: center; font-size: 16px; line-height: 1.6;">
            <span style="color: #000000">By submitting this form and based on your requirements you agree as an FCA authorised broker we can contact you by phone, email or electronic messaging to provide the comparison service and in accordance with our </span>
            <a href="/privacy" target="_blank" style="color: #993366; text-decoration: none;">Privacy Policy</a>
            <span style="color: #000000"> and to our </span>
            <a href="/terms" target="_blank" style="color: #993366; text-decoration: none;">Terms of Use</a>
        </p>
    </div>
</div>

<style>
.ff-el-group {
    margin-bottom: 25px;
}

.ff-el-input--label {
    margin-bottom: 8px;
}

.ff-el-input--label label {
    font-weight: 600;
    color: #333;
    font-size: 16px;
    display: block;
}

.ff-el-is-required.asterisk-right label:after {
    content: " *";
    color: #ff0000;
}

.required {
    color: #ff0000;
}

.ff-el-form-control {
    width: 100%;
    
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.ff-el-form-control:focus {
    outline: none;
    border-color: #1a7efb;
    box-shadow: 0 0 0 2px rgba(26, 126, 251, 0.1);
}

.ff-el-form-check {
    margin-bottom: 15px;
}

.ff-el-form-check-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    font-size: 15px;
    line-height: 1.5;
}

.ff-el-form-check-input {
    margin-top: 3px;
    cursor: pointer;
    width: 18px;
    height: 18px;
}

.ff-custom_html {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 4px;
}

.ff-custom_html p {
    margin: 0;
}

.ff-custom_html a:hover {
    text-decoration: underline;
}

.ff-text-center {
    text-align: center;
}

.submit-button-wrapper {
    text-align: center;
    margin: 30px 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.ff_submit_btn_wrapper {
    margin: 30px 0;
}

.ff-btn-submit {
    background-color: #1a7efb;
    color: #ffffff;
    border: none;
    padding: 14px 40px;
    font-size: 18px;
    font-weight: 600;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    min-width: 250px;
    margin: 0 auto;
    display: block;
}

.ff-btn-submit:hover {
    background-color: #0056b3;
}

.ff-btn-submit:active {
    transform: translateY(1px);
}

@media screen and (max-width: 768px) {
    .ff-el-form-control {
        max-width: 100%;
    }
    
    .ff-el-form-check-label {
        font-size: 16px;
        line-height: 1.6;
    }
    
    .ff-el-form-check-label span {
        font-size: 16px;
    }
    
    .ff-el-form-check-input {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    
    .ff-btn-submit {
        width: 100%;
        min-width: auto;
        padding: 14px 20px;
        font-size: 16px;
    }
    
    .ff-custom_html {
        padding: 15px;
    }
    
    .ff-custom_html p {
        font-size: 14px !important;
    }
}

.getty-button {
    position: relative !important;
}
</style>
