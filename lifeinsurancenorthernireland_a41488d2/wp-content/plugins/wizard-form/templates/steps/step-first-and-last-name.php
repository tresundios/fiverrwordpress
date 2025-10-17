<div>
   
    <div class="form-row">
       
        <div class="form-group">
             <h2>First Name</h2>
            <input type="text" 
                   name="first_name" 
                   placeholder="First Name" 
                   required
                   value="<?php echo isset($saved_data['first_name']) ? esc_attr($saved_data['first_name']) : ''; ?>"
                   class="form-control">
        </div>
        <div class="form-group">
            <h2>Last Name</h2>
            <input type="text" 
                   name="last_name" 
                   placeholder="Last Name" 
                   required
                   value="<?php echo isset($saved_data['last_name']) ? esc_attr($saved_data['last_name']) : ''; ?>"
                   class="form-control">
        </div>
    </div>
</div>

<style>
.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
}

.form-group h2 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #1a7efb;
    box-shadow: 0 0 0 2px rgba(26, 126, 251, 0.1);
}

@media screen and (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 20px;
    }
    
    .form-group {
        width: 100%;
    }
    
    .form-group h2 {
        font-size: 16px;
    }
    
    .form-control {
        font-size: 16px;
        padding: 12px;
    }
}
</style>
