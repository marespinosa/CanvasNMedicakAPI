<?php

add_shortcode( 'opal_register_form', 'opal_register_form' );

function opal_register_form() {
	ob_start();
	$questions = new CERBO_Questions();
	?>

    <form method="POST" id="regForm" enctype="multipart/form-data">
        <div class="tab-step"><span>1</span> of 25</div>
        <div class="tabs">

            <div class="tab bday">
                <div class="tab-center">
                    <h3>Let’s start with your birthday</h3>
                    <p>We need your date of birth to determine if you are eligible for treatment.</p>
                    <div class="bday-select bday-row">
                        <div class="bday-col bday-month">
                            <select id="b-month" name="month">
                                <option value="">Month</option>
								<?php
								for ( $i = 1; $i <= 12; $i ++ ) {
									printf( '<option value="%1$d">%2$s</option>', $i, date( 'M', mktime( 0, 0, 0, $i, 10 ) ) );
								}
								?>
                            </select>
                        </div>
                        <div class="bday-col bday-day">
                            <select id="b-day" name="day">
                                <option value="">Day</option>
								<?php
								for ( $i = 1; $i <= 31; $i ++ ) {
									printf( '<option value="%1$d">%1$d</option>', $i );
								}
								?>
                            </select>
                        </div>
                        <div class="bday-col bday-year">
                            <select id="b-year" name="year">
                                <option value="">Year</option>
								<?php
								for ( $i = 2022; $i >= 1905; $i -- ) {
									printf( '<option value="%1$d">%1$d</option>', $i );
								}
								?>
                            </select>
                        </div>
                    </div>
                    <div class="bday-error"></div>
                    <label class="label-agree"><input name="patient_consent" value="1" type="checkbox" id="agree">I agree to the Terms and Conditions, Privacy Policy, and Telehealth Consent.</label>
                </div>
            </div>
            <div class="tab">
                <div class="tab-center">
                    <h4>In which US State do you live?</h4>
                    <p>We need your state to determine if you are eligible for treatment. Note: We currently do not provide treatment to certain US States.</p>
                    <select name="state">
                        <option value="">Select..</option>
						<?php
						$states = $questions->get_states();
						foreach ( $states as $key => $label ) {
							printf( '<option value="%s">%s</option>', $key, $label );
						}
						?>
                    </select>
                </div>
            </div>
            <div class="tab">
                <h3>You’re eligible for treatment. </h3>
                <h4>Let’s continue with some basic information.</h4>
                <p>Your full name is required to provide treatment. We will not disclose or share your patient records with anyone except your provider.</p>
                <p><input placeholder="First Name..." oninput="this.className = ''" name="first_name"></p>
                <p><input placeholder="Last Name..." oninput="this.className = ''" name="last_name"></p>
                <p><input placeholder="E-mail..." oninput="this.className = ''" name="email"></p>
                <p><input placeholder="Phone..." id="phoneNumber" name="phone"></p>

                <!--                <h4>Upload Photo</h4>-->
                <!--                <input type="file" name="photo">-->
            </div>
            <div class="tab radioTab">
                <h3>Let’s talk about your health. </h3>
                <h4>What is your sex assigned at birth?</h4>
                <p>For example, on your original birth Certificate.</p>
                <label class="label-input"><input type="radio" name="sex" value="M" class="nextAutomaticRadio"><span>Male</span></label>
                <label class="label-input"><input type="radio" name="sex" value="F" class="nextAutomaticRadio"><span>Female</span></label>
            </div>
            <div class="tab heightInc">
                <h4>What is your height?</h4>
                <p><input type="number" placeholder="Foot" oninput="this.className = ''" name="height_foot[]" min="1" max="12">
                    <input type="number" placeholder="Inches" oninput="this.className = ''" name="height_inches[]" min="1" max="12"></p>

                <h4>What is your weight in pounds?</h4>
                <p><input type="number" placeholder="lbs" oninput="this.className = ''" name="lbs[]" min="1" max="999"></p>
            </div>
            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q7' ); ?></h4>
                <label class="label-input"><input type="radio" name="med_prof_q7[]" value="Sedentary" class="nextAutomaticRadio"><span>Sedentary</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q7[]" value="SomewhatActive" class="nextAutomaticRadio"><span>Somewhat Active</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q7[]" value="Active" class="nextAutomaticRadio"><span>Active</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q7[]" value="Athletic" class="nextAutomaticRadio"><span>Athletic</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q7[]" value="Competitive" class="nextAutomaticRadio"><span>Competitive</span></label>
            </div>


            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q1' ); ?></h4>
                <p>Certain conditions can complicate diagnosis, increase risks, or change the recommended treatments so its important for your provider to know.</p>
                <label class="label-input"><input type="radio" name="med_prof_q1[]" value="Yes" class="nextAutomaticRadio"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q1[]" value="No" class="nextAutomaticRadio"><span>No</span></label>
            </div>
            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q2' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="No Medical Problems/Conditions"><span>No Medical Problems/Conditions</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Diabetes"><span>Diabetes</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Hypertension (high blood pressure)"><span>Hypertension (high blood pressure)</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Heart disease"><span>Heart disease</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Thyroid condition"><span>Thyroid condition</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Asthma or COPD"><span>Asthma or COPD</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Anxiety or depression"><span>Anxiety or depression</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="HIV or AIDS"><span>HIV or AIDS</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Kidney disease"><span>Kidney disease</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Cancer"><span>Cancer</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Irregular heart beat"><span>Irregular heart beat</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q2[]" value="Vascular disease (stroke, blood clots, etc)"><span>Vascular disease (stroke, blood clots, etc)</span></label>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q8' ); ?></h4>
                <label class="label-input"><input type="radio" name="med_prof_q8[]" value="Yes" class="yesOption"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q8[]" value="No" class="NoOption"><span>No</span></label>
                <div id="ifYesExplain" style="display:none;"><p>If yes, explain...</p>
                    <textarea name="med_prof_q8[]" placeholder="explain here..."></textarea></div>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q9' ); ?></h4>
                <label class="label-input"><input type="radio" name="med_prof_q9[]" value="Yes" class="yesOptionL"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q9[]" value="No" class="NoOption"><span>No</span></label>
                <div id="med_prof_q9Ex" style="display:none;"><p>If yes, explain...</p>
                    <textarea name="med_prof_q9[]" placeholder="explain here..."></textarea></div>
            </div>

            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q10' ); ?></h4>
                <label class="label-input"><input type="radio" name="med_prof_q10[]" value="Yes" class="nextAutomaticRadio"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q10[]" value="No" class="nextAutomaticRadio"><span>No</span></label>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q11' ); ?></h4>

                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="None"><span>Cancer</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="Heart Disease"><span>Heart Disease</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="Dementia"><span>Dementia</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="Diabetes"><span>Diabetes</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="High Blood Pressure"><span>High Blood Pressure</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="High Cholesterol"><span>High Cholesterol</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q11[]" value="None of these"><span>None of these</span></label>

            </div>


            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q12' ); ?></h4>
                <label class="label-input"><input type="radio" name="med_prof_q12[]" value="Yes" class="yesOption12"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q12[]" value="No" class="nextAutomaticRadio"><span>No</span></label>

                <div id="ifYesExplain_12" style="display:none;"><p>If yes, explain...</p>
                    <textarea name="med_prof_q12[]" placeholder="explain here..."></textarea></div>
            </div>


            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q13' ); ?></h4>
                <p>*We recommend a routine physical as part of your ongoing health plan</p>
                <label class="label-input"><input type="radio" name="med_prof_q13[]" value="Yes" class="nextAutomaticRadio"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="med_prof_q13[]" value="No" class="nextAutomaticRadio"><span>No</span></label>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q14' ); ?></h4>
                <p>Please include any medicines you finished recently including topical medicines, and any injections vitamins, herbal remedies, or any other products you use. If you don’t take any medications, simply put “None”.</p>
                <textarea name="med_prof_q14[]" placeholder="Write your answer here..."></textarea>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_med_prof_question( 'med_prof_q15' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="None"><span>None</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="Penicillin/Amoxicillin"><span>Penicillin/Amoxicillin</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="Sulfa Drugs"><span>Sulfa drugs</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="Aspirin"><span>Aspirin</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="Codeine"><span>Codeine</span></label>
                <label class="label-input"><input type="checkbox" name="med_prof_q15[]" value="Latex"><span>Latex</span></label>
            </div>

            <div class="tab">
                <h4>By continuing, you acknowledge and understand that OpalRx is a telemedicine clinic and does not replace the need to seek routine care with your primary care provider.</h4>
                <label class="label-understand"><input type="checkbox" id="understand">I acknowledge and understand</label>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q6' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Reduce Aches & Pains"><span>Reduce Aches & Pains</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Reduce Inflammation"><span>Reduce Inflammation</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Reduce Fatigue"><span>Reduce Fatigue</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Improve Mood"><span>Improve Mood</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Encourage Weight Loss"><span>Encourage Weight Loss</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q6[]" value="Addiction Control"><span>Addiction Control</span></label>
            </div>


            <div class="tab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q7' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Reduce Aches & Pains"><span>Reduce Aches & Pains</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Reduce Inflammation"><span>Reduce Inflammation</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Reduce Fatigue"><span>Reduce Fatigue</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Improve Mood"><span>Improve Mood</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Encourage Weight Loss"><span>Encourage Weight Loss</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q7[]" value="Addiction Control"><span>Addiction Control</span></label>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q8' ); ?></h4>
                <label class="label-input"><input type="radio" name="auto_immu_q8[]" value="Yes" class="yesOptionMed"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q8[]" value="No" class="NoOption"><span>No</span></label>

                <div id="auto_immu_q8Ex" style="display:none;"><p>If yes, please briefly describe the following: </p>
                    <textarea name="auto_immu_q8[]" placeholder="Why did you previously take LDN, what LDN dosage did you take and did you notice any benefits or side effects while taking LDN..."></textarea>
                </div>
            </div>

            <div class="tab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q9' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="auto_immu_q9[]" value="None"><span>None</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q9[]" value="Lyme or associated infections"><span>Lyme or associated infections</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q9[]" value="Fibromyalgia or Chronic Fatigue Syndrome (FMS or CFS)"><span>Fibromyalgia or Chronic Fatigue Syndrome (FMS or CFS)</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q9[]" value="Cancer or malignancy of any kind"><span>Cancer or malignancy of any kind</span></label>
            </div>


            <div class="tab radioTab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q10' ); ?></h4>
                <label class="label-input"><input type="radio" name="auto_immu_q10[]" value="Yes" class="yesOption10"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q10[]" value="No" class="nextAutomaticRadio"><span>No</span></label>


            </div>


            <div class="tab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q11' ); ?></h4>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Codeine(including Tylenol #2, #3, or #4)"><span>Codeine (including Tylenol #2, #3, or #4)</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Hydrocodone(including Lortab, Norco, Vicodin)"><span>Hydrocodone (including Lortab, Norco, Vicodin)</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Tramadol(Ultram) or tapentadol (Nucynta)"><span>Tramadol (Ultram) or tapentadol (Nucynta)</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Oxycodone(including Percocets or OxyNeo)"><span>Oxycodone (including Percocets or OxyNeo)</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Fentanyl in any form"><span>Fentanyl in any form</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Methadone or Suboxone"><span>Methadone or Suboxone</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="Other opioid or narcotic medications"><span>Other opioid or narcotic medications</span></label>
                <label class="label-input"><input type="checkbox" name="auto_immu_q11[]" value="I do not take nor do I plan to take any narcotic medications"><span>I do not take nor do I plan to take any narcotic medications</span></label>
            </div>


            <div class="tab radios">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q12' ); ?></h4>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I am not currently pregnant nor breastfeeding"><span>I am not currently pregnant nor breastfeeding.</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I am currently pregnant or breastfeeding"><span>I am currently pregnant or breastfeeding.</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I plan to become pregnant or breastfeed within the next 6 months"><span>I plan to become pregnant or breastfeed within the next 6 months.</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I am currently going through menopause"><span>I am currently going through menopause.</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I had a hysterectomy or am post-menopausal"><span>I had a hysterectomy or am post-menopausal.</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q12[]" value="I am male"><span>I am male.</span></label>
            </div>

            <div class="tab radiostab">
                <h4><?php
					echo $questions->get_auto_immu_question( 'auto_immu_q13' ); ?></h4>
                <label class="label-input"><input type="radio" name="auto_immu_q13[]" value="Yes" class="yesOption13"><span>Yes</span></label>
                <label class="label-input"><input type="radio" name="auto_immu_q13[]" value="No" class="NoOption"><span>No</span></label>

                <div id="auto_immu_q13Ex" style="display:none;"><p>If yes, please explain... </p>
                    <textarea name="auto_immu_q13[]" placeholder="explain here..."></textarea></div>
            </div>


            <div class="tab">
                <h4>Choose Time Zone</h4>
                <select name="timezoneUSA">
                    <option value="">Select..</option>
					<?php
					$timezoneusa = $questions->get_timezoneUSA();
					foreach ( $timezoneusa as $key => $label ) {
						printf( '<option value="%s">%s</option>', $key, $label );
					}
					?>
                </select>

                <div class="daysMonth paddingtop">

                        <h5>Schedule an Appointment</h5>

                    <div class="calendar_cont">
                        <div class="date_wrapper">
                            <h6>Date</h6>
                            <div id="datepicker"></div>
                            <script>
                                $(function () {
                                    $("#datepicker").datepicker({
                                        onSelect: function (dateText, inst) {
                                            var dateObject = $(this).datepicker('getDate');
                                            var year = dateObject.getFullYear();
                                            var month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
                                            var day = dateObject.getDate().toString().padStart(2, '0');
                                            var formattedDate = year + "-" + month + "-" + day;
                                            $("#appointment").attr("value", formattedDate);
                                        }
                                    });
                                });
                            </script>
                            <input type="hidden" name="apppointmentTime" id="appointment">
                        </div>             

                        <div class="hours_wrapper time_wrapper">
                            <div id="hourappt" class="hours_wrapper">
                                <h6>Start</h6>
                                <ul class="hours">
        							<?php
        							for ( $i = 0; $i < 12; $i ++ ) {
        								$hour    = str_pad( $i, 2, '0', STR_PAD_LEFT );
        								$value   = "$hour:00:00";
        								$label   = "$hour:00";
        								$id      = "hourappt-$i";
        								$checked = $i === 0 ? 'checked' : '';
        								?>
                                        <li><input type="radio" id="<?php
        									echo $id; ?>" name="hourappt" value="<?php
        									echo $value; ?>" <?php
        									echo $checked; ?>>
                                            <label for="<?php
        									echo $id; ?>"><?php
        										echo $label; ?></label></li>
        							<?php
        							} ?>
                                </ul>
                            </div>
                            <div id="hourapptend" class="hours_wrapper">
                                <input type="hidden" id="hourapptend-0" name="hourapptend" value="00:00:00">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!---- tab---->


            <div class="tab">
                <p>We've got your medical questionnaire. Review how virtual care works & meet with your physician.</p>
                <div class="ldn-prods">
                    <div class="ldn-prod">
                        <div class="ldn-image">
                            <span>Secure & Confidential</span>
                            <img src="<?php
							echo CERBO_URL . '/assets/img/ldn-sample.png'; ?>">
                        </div>
                        <h4>New Patient Consultation</h4>
                        <p><strong>$150</strong>
                        </p>
                        <p><strong>What should I expect?</strong><br>
                            During your consultation, our physician will review your health history, discuss your current symptoms, and provide you with a personalized treatment plan. You can expect your appointment to last approximately 30 minutes.
                        </p>
                        <p><strong>How it works:</strong><br>
                            During your virtual care appointment, you can expect to have a one-on-one consultation with our physician. The physician will review your health history and the questionnaire you completed prior to the appointment. You will be asked to describe your current symptoms and any changes you have noticed in your health.
                        </p>
                        <p>The physician may also ask you to perform some simple tests, such as checking your blood pressure to help them make an accurate diagnosis. Based on your symptoms and test results, the physician will provide you with a personalized treatment plan, which may include medication, lifestyle changes, or further testing
                        </p>
                    </div>
                </div>


                <button type="button" class="submit-btn" id="iunderstandbtm">I understand, Continue</button>

                <input type="hidden" name="action" value="cerbo_register">


            </div> <!---- tab---->


        </div> <!--- end: Tabs---->

        <div style="overflow:auto;" class="confirmBook_nav">
            <div class="nav-btns" style="display: none">
                <button type="button" class="cerbo-nav-btn prev" id="prevBtn">Previous</button>
                <button type="button" class="cerbo-nav-btn next" id="nextBtn">Next</button>
            </div>
        </div>


    </form>

	<?php
	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}


?>
