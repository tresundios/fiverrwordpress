<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wizard_Ghost
 * @subpackage Wizard_Ghost/public/partials
 */

?>

<div class="wizard-ghost-container">
	<div class="wizard-ghost-form-wrapper">
		<div class="wizard-progress-bar">
			<div class="wizard-progress-fill"></div>
		</div>
		<!-- Step 1: Occupation -->
		<div class="wizard-step wizard-step-1 active">
			<div class="wizard-header">
				<h1>Find out if you are eligible for Private Sick Pay™</h1>
			</div>

			<div class="wizard-content">
				<div class="wizard-question">
					<h2>What is your occupation?</h2>
					<form class="wizard-form" method="post" data-step="1">
						<div class="form-group">
							<input type="text" name="occupation" class="form-control" placeholder="Occupation" required>
						</div>
						<button type="submit" class="btn btn-primary btn-continue generic-button">
							<span class="content">
								Continue
								<span class="arrow">→</span>
							</span>
						</button>
						<?php wp_nonce_field('wizard_ghost_step_1', 'wizard_ghost_nonce'); ?>
					</form>
				</div>

				<div class="wizard-benefits">
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>100% of Eligible Claims Paid – 95% of All Claims Approved</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>Simple Process – Check Your Eligibility in Minutes</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>£810 Million Paid in 2023 – Trusted by Thousands</strong></p>
					</div>
					<div class="benefit-item">
						<span class="benefit-icon">✓</span>
						<p><strong>Non-Profit Providers - More Payouts, Less Hassle</strong></p>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 2: Loading -->
		<div class="wizard-step wizard-step-2">
			<div class="wizard-header">
				<h1>We're now checking your occupations eligibility...</h1>
			</div>

			<div class="wizard-content wizard-loading">
				<div class="loading-spinner">
					<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
						<circle cx="50" cy="50" r="45" fill="none" stroke="#e0e0e0" stroke-width="3"></circle>
						<circle cx="50" cy="50" r="45" fill="none" stroke="#0073aa" stroke-width="3"
							stroke-dasharray="141.3" stroke-dashoffset="0" class="spinner-circle"></circle>
						<path d="M 50 20 L 50 50 L 70 70" stroke="#0073aa" stroke-width="3" fill="none"
							stroke-linecap="round" stroke-linejoin="round" class="checkmark"></path>
					</svg>
				</div>

				<div class="loading-contact">
					<!-- <div class="contact-item">
						<span class="contact-icon">☎</span>
						<p>0300 303 5758</p>
					</div> -->
					<div class="contact-item">
						<span class="contact-icon">✉</span>
						<p>info@sickpaylifeline.co.uk</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Step 3: Personal Details -->
		<div class="wizard-step wizard-step-3">
			<div class="wizard-header">
				<h1>Congratulations 🎉</h1>
				<p>Your Occupation is Eligible For Private Sick Pay.</p>
				<p class="subtitle">We now need to check if you personally qualify.</p>
			</div>

			<div class="wizard-content">
				<form class="wizard-form" method="post" data-step="3">
					<div class="form-row">
						<div class="form-group">
							<label for="first_name">First Name</label>
							<input type="text" id="first_name" name="first_name" class="form-control"
								placeholder="First name" required>
						</div>
						<div class="form-group">
							<label for="last_name">Last Name</label>
							<input type="text" id="last_name" name="last_name" class="form-control"
								placeholder="Last name" required>
						</div>
					</div>

					<div class="form-group">
						<label for="phone">Phone number</label>
						<p class="help-text">So we can contact you about your cover and support you when making a claim
						</p>
						<div class="phone-input-wrapper">
							<div class="phone-country-select">
								<span class="phone-flag">🇬🇧</span>
								<select class="phone-country-code" name="phone_country_code" id="phone_country_code">
									<option value="+1" data-flag="🇨🇦">+1 (CAN)</option>
									<option value="+1" data-flag="🇯🇲">+1 (JAM)</option>
									<option value="+1" data-flag="🇺🇸">+1 (USA)</option>
									<option value="+7" data-flag="🇰🇿">+7</option>
									<option value="+7" data-flag="🇷🇺">+7</option>
									<option value="+20" data-flag="🇪🇬">+20</option>
									<option value="+27" data-flag="🇿🇦">+27</option>
									<option value="+30" data-flag="🇬🇷">+30</option>
									<option value="+31" data-flag="🇳🇱">+31</option>
									<option value="+32" data-flag="🇧🇪">+32</option>
									<option value="+33" data-flag="🇫🇷">+33</option>
									<option value="+34" data-flag="🇪🇸">+34</option>
									<option value="+34" data-flag="🇪🇸">+34</option>
									<option value="+36" data-flag="🇭🇺">+36</option>
									<option value="+39" data-flag="🇮🇹">+39</option>
									<option value="+40" data-flag="🇷🇴">+40</option>
									<option value="+41" data-flag="🇨🇭">+41</option>
									<option value="+43" data-flag="🇦🇹">+43</option>
									<option value="+44" data-flag="🇬🇧" selected>+44</option>
									<option value="+45" data-flag="🇩🇰">+45</option>
									<option value="+46" data-flag="🇸🇪">+46</option>
									<option value="+47" data-flag="🇳🇴">+47</option>
									<option value="+48" data-flag="🇵🇱">+48</option>
									<option value="+49" data-flag="🇩🇪">+49</option>
									<option value="+51" data-flag="🇵🇪">+51</option>
									<option value="+52" data-flag="🇲🇽">+52</option>
									<option value="+53" data-flag="🇨🇺">+53</option>
									<option value="+54" data-flag="🇦🇷">+54</option>
									<option value="+55" data-flag="🇧🇷">+55</option>
									<option value="+56" data-flag="🇨🇱">+56</option>
									<option value="+57" data-flag="🇨🇴">+57</option>
									<option value="+58" data-flag="🇻🇪">+58</option>
									<option value="+60" data-flag="🇲🇾">+60</option>
									<option value="+61" data-flag="🇦🇺">+61</option>
									<option value="+62" data-flag="🇮🇩">+62</option>
									<option value="+63" data-flag="🇵🇭">+63</option>
									<option value="+64" data-flag="🇳🇿">+64</option>
									<option value="+65" data-flag="🇸🇬">+65</option>
									<option value="+66" data-flag="🇹🇭">+66</option>
									<option value="+81" data-flag="🇯🇵">+81</option>
									<option value="+82" data-flag="🇰🇷">+82</option>
									<option value="+84" data-flag="🇻🇳">+84</option>
									<option value="+86" data-flag="🇨🇳">+86</option>
									<option value="+90" data-flag="🇹🇷">+90</option>
									<option value="+91" data-flag="🇮🇳">+91</option>
									<option value="+92" data-flag="🇵🇰">+92</option>
									<option value="+93" data-flag="🇦🇫">+93</option>
									<option value="+94" data-flag="🇱🇰">+94</option>
									<option value="+95" data-flag="🇲🇲">+95</option>
									<option value="+98" data-flag="🇮🇷">+98</option>
									<option value="+211" data-flag="🇸🇸">+211</option>
									<option value="+212" data-flag="🇲🇦">+212</option>
									<option value="+213" data-flag="🇩🇿">+213</option>
									<option value="+216" data-flag="🇹🇳">+216</option>
									<option value="+218" data-flag="🇱🇾">+218</option>
									<option value="+220" data-flag="🇬🇲">+220</option>
									<option value="+221" data-flag="🇸🇳">+221</option>
									<option value="+222" data-flag="🇷">+222</option>
									<option value="+223" data-flag="🇲🇱">+223</option>
									<option value="+224" data-flag="🇬🇳">+224</option>
									<option value="+225" data-flag="🇨🇮">+225</option>
									<option value="+226" data-flag="🇧🇫">+226</option>
									<option value="+227" data-flag="🇳🇪">+227</option>
									<option value="+228" data-flag="🇹🇬">+228</option>
									<option value="+229" data-flag="🇧🇯">+229</option>
									<option value="+230" data-flag="🇲🇺">+230</option>
									<option value="+231" data-flag="🇱🇷">+231</option>
									<option value="+232" data-flag="🇸🇱">+232</option>
									<option value="+233" data-flag="🇬🇭">+233</option>
									<option value="+234" data-flag="🇳🇬">+234</option>
									<option value="+235" data-flag="🇹🇩">+235</option>
									<option value="+236" data-flag="🇨🇫">+236</option>
									<option value="+237" data-flag="🇨🇲">+237</option>
									<option value="+238" data-flag="🇨🇻">+238</option>
									<option value="+240" data-flag="🇬🇶">+240</option>
									<option value="+241" data-flag="🇬🇦">+241</option>
									<option value="+242" data-flag="🇨🇬">+242</option>
									<option value="+244" data-flag="🇦🇴">+244</option>
									<option value="+245" data-flag="🇬🇼">+245</option>
									<option value="+248" data-flag="🇸🇨">+248</option>
									<option value="+249" data-flag="🇸🇩">+249</option>
									<option value="+250" data-flag="🇷🇼">+250</option>
									<option value="+251" data-flag="🇪🇹">+251</option>
									<option value="+252" data-flag="🇸🇴">+252</option>
									<option value="+253" data-flag="🇩🇯">+253</option>
									<option value="+254" data-flag="🇰🇪">+254</option>
									<option value="+255" data-flag="🇹🇿">+255</option>
									<option value="+256" data-flag="🇺🇬">+256</option>
									<option value="+257" data-flag="🇧🇮">+257</option>
									<option value="+258" data-flag="🇲🇿">+258</option>
									<option value="+260" data-flag="🇿🇲">+260</option>
									<option value="+261" data-flag="🇲🇬">+261</option>
									<option value="+263" data-flag="🇿🇼">+263</option>
									<option value="+264" data-flag="🇳🇦">+264</option>
									<option value="+265" data-flag="🇲🇼">+265</option>
									<option value="+266" data-flag="🇱🇸">+266</option>
									<option value="+267" data-flag="🇧🇼">+267</option>
									<option value="+269" data-flag="🇰🇲">+269</option>
									<option value="+291" data-flag="🇪🇷">+291</option>
									<option value="+297" data-flag="🇦🇼">+297</option>
									<option value="+351" data-flag="🇵🇹">+351</option>
									<option value="+352" data-flag="🇱🇺">+352</option>
									<option value="+353" data-flag="🇮🇪">+353</option>
									<option value="+354" data-flag="🇮🇸">+354</option>
									<option value="+355" data-flag="🇦🇱">+355</option>
									<option value="+356" data-flag="🇲🇹">+356</option>
									<option value="+357" data-flag="🇨🇾">+357</option>
									<option value="+358" data-flag="🇫🇮">+358</option>
									<option value="+359" data-flag="🇧🇬">+359</option>
									<option value="+370" data-flag="🇱🇹">+370</option>
									<option value="+371" data-flag="🇱🇻">+371</option>
									<option value="+372" data-flag="🇪🇪">+372</option>
									<option value="+373" data-flag="🇲🇩">+373</option>
									<option value="+374" data-flag="🇦🇲">+374</option>
									<option value="+375" data-flag="🇧🇾">+375</option>
									<option value="+376" data-flag="🇦🇩">+376</option>
									<option value="+377" data-flag="🇲🇨">+377</option>
									<option value="+380" data-flag="🇺🇦">+380</option>
									<option value="+381" data-flag="🇷🇸">+381</option>
									<option value="+382" data-flag="🇲🇪">+382</option>
									<option value="+385" data-flag="🇭🇷">+385</option>
									<option value="+386" data-flag="🇸🇮">+386</option>
									<option value="+387" data-flag="🇧">+387</option>
									<option value="+389" data-flag="🇲🇰">+389</option>
									<option value="+420" data-flag="🇨🇿">+420</option>
									<option value="+421" data-flag="🇸🇰">+421</option>
									<option value="+423" data-flag="🇱🇮">+423</option>
									<option value="+501" data-flag="🇧🇿">+501</option>
									<option value="+502" data-flag="🇬🇹">+502</option>
									<option value="+503" data-flag="🇸🇻">+503</option>
									<option value="+504" data-flag="🇭🇳">+504</option>
									<option value="+505" data-flag="🇳🇮">+505</option>
									<option value="+506" data-flag="🇨🇷">+506</option>
									<option value="+507" data-flag="🇵🇦">+507</option>
									<option value="+509" data-flag="🇭🇹">+509</option>
									<option value="+591" data-flag="🇧🇴">+591</option>
									<option value="+592" data-flag="🇬🇾">+592</option>
									<option value="+593" data-flag="🇪🇨">+593</option>
									<option value="+595" data-flag="🇵🇾">+595</option>
									<option value="+597" data-flag="🇸🇷">+597</option>
									<option value="+598" data-flag="🇺🇾">+598</option>
									<option value="+676" data-flag="🇹🇴">+676</option>
									<option value="+677" data-flag="🇸🇧">+677</option>
									<option value="+678" data-flag="🇻🇺">+678</option>
									<option value="+679" data-flag="🇫🇯">+679</option>
									<option value="+850" data-flag="🇰🇵">+850</option>
									<option value="+855" data-flag="🇰🇭">+855</option>
									<option value="+856" data-flag="🇱🇦">+856</option>
									<option value="+880" data-flag="🇧🇩">+880</option>
									<option value="+886" data-flag="🇹🇼">+886</option>
									<option value="+960" data-flag="🇲🇻">+960</option>
									<option value="+961" data-flag="🇱🇧">+961</option>
									<option value="+962" data-flag="🇯🇴">+962</option>
									<option value="+963" data-flag="🇸🇾">+963</option>
									<option value="+964" data-flag="🇮🇶">+964</option>
									<option value="+965" data-flag="🇰🇼">+965</option>
									<option value="+966" data-flag="🇸🇦">+966</option>
									<option value="+967" data-flag="🇾🇪">+967</option>
									<option value="+968" data-flag="🇴🇲">+968</option>
									<option value="+971" data-flag="🇦🇪">+971</option>
									<option value="+972" data-flag="🇮🇱">+972</option>
									<option value="+973" data-flag="🇧🇭">+973</option>
									<option value="+974" data-flag="🇶🇦">+974</option>
									<option value="+975" data-flag="🇧🇹">+975</option>
									<option value="+976" data-flag="🇲🇳">+976</option>
									<option value="+977" data-flag="🇳🇵">+977</option>
									<option value="+992" data-flag="🇹🇯">+992</option>
									<option value="+993" data-flag="🇹🇲">+993</option>
									<option value="+994" data-flag="🇦🇿">+994</option>
									<option value="+995" data-flag="🇬🇪">+995</option>
									<option value="+996" data-flag="🇰🇬">+996</option>
									<option value="+998" data-flag="🇺🇿">+998</option>
								</select>
							</div>
							<input type="tel" id="phone" name="phone" class="form-control" placeholder="7911 123456"
								data-format="uk" required>
						</div>
						<div class="error-message phone-error" style="display: none;">
							<span class="error-icon">✕</span>
							<span class="error-text">Please enter a valid phone number</span>
						</div>
					</div>

					<div class="form-group">
						<label for="email">Email address</label>
						<p class="help-text">This is where we'll send your your quote.</p>
						<div class="email-input-wrapper">
							<span class="email-icon">✉</span>
							<input type="email" id="email" name="email" class="form-control"
								placeholder="address@mail.com" required>
						</div>
					</div>

					<div class="form-group">
						<label for="dob">Date Of Birth</label>
						<div class="date-dropdowns-wrapper">
							<div class="date-select-group">
								<select name="dob_day" id="dob_day" class="form-control date-select" required>
									<option value="">Day</option>
									<?php for ($i = 1; $i <= 31; $i++) : ?>
										<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
								<select name="dob_month" id="dob_month" class="form-control date-select" required>
									<option value="">Month</option>
									<?php
									$months = array(
										'01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
										'05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
										'09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
									);
									foreach ($months as $num => $name) : ?>
										<option value="<?php echo $num; ?>"><?php echo $name; ?></option>
									<?php endforeach; ?>
								</select>
								<select name="dob_year" id="dob_year" class="form-control date-select" required>
									<option value="">Year</option>
									<?php
									$current_year = date('Y');
									for ($i = $current_year - 18; $i >= $current_year - 100; $i--) : ?>
										<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
									<?php endfor; ?>
								</select>
							</div>
							<input type="hidden" id="dob" name="dob" required>
						</div>
					</div>

					<div class="form-group">
						<label>What do you need help with today?</label>
						<div class="radio-group">
							<label class="radio-option">
								<input type="radio" name="help_with" value="I want sick-pay cover now" required>
								<span>I want sick-pay cover now</span>
							</label>
							<label class="radio-option">
								<input type="radio" name="help_with" value="I want to see my options">
								<span>I want to see my options</span>
							</label>
							<label class="radio-option">
								<input type="radio" name="help_with" value="I want to understand how it works">
								<span>I want to understand how it works</span>
							</label>
							<label class="radio-option">
								<input type="radio" name="help_with" value="I’m just looking">
								<span>I’m just looking</span>
							</label>
						</div>
					</div>

					<button type="submit" class="btn btn-primary btn-submit generic-button">
						<span class="content">
							Submit
							<span class="arrow">→</span>
						</span>
					</button>

					<p class="disclaimer">By submitting this form and based on your requirements you agree that we can
						contact you by phone, email or electronic messaging in accordance with our Privacy Policy.</p>

					<?php wp_nonce_field('wizard_ghost_step_3', 'wizard_ghost_nonce'); ?>
				</form>
			</div>
		</div>
	</div>
</div>