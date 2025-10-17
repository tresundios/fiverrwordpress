<div class="form-step">
    <div class="form-group">
        <h2>How much cover would you like?</h2>
        <select name="cover_amount" class="ff-el-form-control" required>
            <option value="">- Select -</option>
            <option value="I'm not sure" <?php echo (!isset($saved_data['cover_amount']) || $saved_data['cover_amount'] === "I'm not sure") ? 'selected' : ''; ?>>I'm not sure</option>
            <?php
            $amounts = [
                'Less than £250,000',
                'More than £250,000',
                'More than £500,000',
                'More than £1,000,000'
            ];
            
            foreach ($amounts as $amount) {
                $selected = (isset($saved_data['cover_amount']) && $saved_data['cover_amount'] === $amount) ? 'selected' : '';
                echo '<option value="' . esc_attr($amount) . '" ' . $selected . '>' . esc_html($amount) . '</option>';
            }
            ?>
        </select>
        
        <h2 style="margin-top: 20px;">How long would you like the cover for?</h2>
        <select name="cover_duration" class="ff-el-form-control" required>
            <option value="">- Select -</option>
            <option value="I'm not sure" <?php echo (!isset($saved_data['cover_duration']) || $saved_data['cover_duration'] === "I'm not sure") ? 'selected' : ''; ?>>I'm not sure</option>
            <?php
            $durations = [
                'Less than 10 years',
                'More than 10 years',
                'More than 20 years'
            ];
            
            foreach ($durations as $duration) {
                $selected = (isset($saved_data['cover_duration']) && $saved_data['cover_duration'] === $duration) ? 'selected' : '';
                echo '<option value="' . esc_attr($duration) . '" ' . $selected . '>' . esc_html($duration) . '</option>';
            }
            ?>
        </select>
    </div>
</div>
