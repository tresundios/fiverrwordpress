<div class="form-step">
    <h2>What is your date of birth? <span class="required">*</span></h2>
    <p class="info-text">Please enter your date of birth to help us provide accurate quotes.</p>
    
    <div class="dob-container">
        <div class="dob-field">
            
            <select name="dob_day" id="dob_day" class="ff-el-form-control" required>
                <option value="">- Day -</option>
                <?php
                for ($day = 1; $day <= 31; $day++) {
                    $selected = (isset($saved_data['dob_day']) && $saved_data['dob_day'] == $day) ? 'selected' : '';
                    echo '<option value="' . $day . '" ' . $selected . '>' . $day . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="dob-field">
            
            <select name="dob_month" id="dob_month" class="ff-el-form-control" required>
                <option value="">- Month -</option>
                <?php
                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                foreach ($months as $month) {
                    $selected = (isset($saved_data['dob_month']) && $saved_data['dob_month'] === $month) ? 'selected' : '';
                    echo '<option value="' . esc_attr($month) . '" ' . $selected . '>' . esc_html($month) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="dob-field">
            
            <select name="dob_year" id="dob_year" class="ff-el-form-control" required>
                <option value="">- Year -</option>
                <?php
                $current_year = date('Y');
                for ($year = 1939; $year <= 2007; $year++) {
                    $selected = (isset($saved_data['dob_year']) && $saved_data['dob_year'] == $year) ? 'selected' : '';
                    echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<style>
.dob-container {
    display: flex;
    gap: 15px;
    margin-top: 15px;
    justify-content: center;
}

.dob-field {
    flex: 1;
    max-width: 100%;
}

.dob-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.ff-el-form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.info-text {
    margin-top: 0;
    margin-bottom: 10px;
    color: #666;
    font-size: 14px;
}

.required {
    color: #ff0000;
}

@media screen and (max-width: 768px) {
    .dob-container {
        flex-direction: column;
    }
    
    .dob-field {
        max-width: 100%;
    }
}
</style>
