<div class="bp3-card">
    <h1>Generate users.</h1>
    <div class="bp3-form-group"><label class="bp3-label" for="number">How many should be generated ?<span class="bp3-text-muted">(required)</span></label>
        <div class="bp3-form-content">
            <div class="bp3-input-group">
                <input id="generate_number" type="number" name="number" min="0" placeholder="Enter a number" required="" class="bp3-input">
            </div>
        </div>
        <button id="generate_button" class="bp3-button bp3-intent-primary col-md-3 mt-2 offset-md-9" onclick="generateUsers()">
            Generate
		</button>
		
    </div>
</div>

<div class="col-md-12 has-text-centered">
	<div id="waiting" class="spinner-border offset-md-6" role="status" style="display:none;">
		<span class="sr-only">Loading...</span>
	</div>
</div>