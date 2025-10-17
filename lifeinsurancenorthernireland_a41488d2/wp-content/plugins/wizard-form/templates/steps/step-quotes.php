<div class="form-step">
    <h2>I'd like quotes for:</h2>
    <div class="ff_el_checkable_photo_holders">
        <div class="ff-el-form-check ff-el-form-check- ff-el-image-holder">
            <label style="background-image: url(https://lifeinsuranceunder30.co.uk/wp-content/uploads/2025/03/man-3.png)" class="ff-el-image-input-src" for="quotes_option_just_me" aria-label="Just me"></label>
            <label class="ff-el-form-check-label" for="quotes_option_just_me">
                <input type="radio" name="quotes_option" data-name="quotes_option" class="ff-el-form-check-input ff-el-form-check-radio auto-submit-radio" value="Just me" id="quotes_option_just_me" aria-label="Just me" required <?php echo (isset($saved_data['quotes_option']) && $saved_data['quotes_option'] === 'Just me') ? 'checked' : ''; ?>>
                <span>Just me</span>
            </label>
        </div>
        <div class="ff-el-form-check ff-el-form-check- ff-el-image-holder">
            <label style="background-image: url(https://lifeinsuranceunder30.co.uk/wp-content/uploads/2025/03/male-female.png)" class="ff-el-image-input-src" for="quotes_option_partner" aria-label="Me and my partner"></label>
            <label class="ff-el-form-check-label" for="quotes_option_partner">
                <input type="radio" name="quotes_option" data-name="quotes_option" class="ff-el-form-check-input ff-el-form-check-radio auto-submit-radio" value="Me and my partner" id="quotes_option_partner" aria-label="Me and my partner" <?php echo (isset($saved_data['quotes_option']) && $saved_data['quotes_option'] === 'Me and my partner') ? 'checked' : ''; ?>>
                <span>Me and my partner</span>
            </label>
        </div>
    </div>
</div>

<style>
.ff_el_checkable_photo_holders {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin: 20px 0;
}

.ff-el-image-holder {
    flex: 1;
    max-width: 250px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.2s;
}

.ff-el-image-holder:hover {
    transform: scale(1.05);
}

.ff-el-image-input-src {
    display: block;
    width: 100%;
    height: 200px;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    border: 3px solid #ddd;
    border-radius: 10px;
    cursor: pointer;
    transition: border-color 0.3s;
    margin-bottom: 10px;
}

.ff-el-image-holder input[type="radio"]:checked ~ .ff-el-image-input-src,
.ff-el-image-holder.ff_item_selected .ff-el-image-input-src {
    border-color: #007bff;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
}

.ff-el-form-check-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
}

.ff-el-form-check-input {
    display: none;
}

.ff-el-form-check-label span {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-top: 10px;
}

.ff-el-image-holder input[type="radio"]:checked + span,
.ff-el-image-holder.ff_item_selected .ff-el-form-check-label span {
    color: #007bff;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Handle radio button selection with image
    $('.ff-el-image-holder').on('click', function(e) {
        // Don't trigger if clicking directly on the radio input
        if ($(e.target).is('input[type="radio"]')) {
            return;
        }
        
        e.preventDefault();
        
        // Remove selected class from all options
        $('.ff-el-image-holder').removeClass('ff_item_selected');
        
        // Add selected class to clicked option
        $(this).addClass('ff_item_selected');
        
        // Check the radio button
        $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
    });
    
    // Auto-submit on radio button selection
    $('.auto-submit-radio').on('change', function() {
        if ($(this).is(':checked')) {
            // Add a small delay for better UX
            setTimeout(function() {
                $('.wizard-form').submit();
            }, 300);
        }
    });
    
    // Ensure the selected option is highlighted on page load
    $('.auto-submit-radio:checked').closest('.ff-el-image-holder').addClass('ff_item_selected');
});
</script>
