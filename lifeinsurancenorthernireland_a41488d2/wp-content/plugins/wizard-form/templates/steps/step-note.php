<div class="form-step">

    <!-- Quick Note -->
    <div class="ff-el-group" style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; border-left: 4px solid #4CAF50;">
        <h3 style="margin-top: 0; color: #2c3e50; font-weight: 600;">Quick note before your quote:</h3>
        <ul style="margin: 10px 0 0 20px; padding: 0; list-style: none;">
            <li style="margin-bottom: 10px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0;">✅</span> <b>Free Service</b> – you don't pay us; the insurer does.
            </li>
            <li style="margin-bottom: 10px; padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0;">💰</span> <b>Better Value Cover</b> – quality protection without overpaying.
            </li>
            <li style="padding-left: 25px; position: relative;">
                <span style="position: absolute; left: 0;">❤️</span> <b>Your Family's Claim Gets Paid Without Hassle</b> – it's our job to make sure your policy's set up right.
            </li>
        </ul>
    </div>
    <!-- End Quick Note -->
    
    <div class="submit-button-wrapper">
        <button class="getty-button ff-btn ff-btn-submit next-button" type="submit" name="submit" data-next-step="<?php echo isset($current_step_data['next']) ? esc_attr($current_step_data['next']) : 'complete'; ?>">
            Get Quotes
        </button>
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
