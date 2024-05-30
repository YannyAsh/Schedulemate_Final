<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="CSS/index_style.css" />

	<!-- this is the js for the icons -->
	<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>

	<!-- sweetalert2 js -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<title>Sign in & Sign up Form</title>
</head>

<body>

	<div class="container">
		<!-- Alerts -->
		<?php
		if (isset($_SESSION["success"])) {
			if ($_SESSION["success"] == 1) {
		?>
				<script>
					Swal.fire({
						icon: "success",
						title: "Registration successful! Please wait for approval.",
						text: "",
					});
				</script>
			<?php
				$_SESSION["success"] = null;
			} else {
				// No need to show login success alert
				$_SESSION["success"] = null;
			}
		}

		if (isset($_SESSION["errors"])) {
			foreach ($_SESSION["errors"] as $error) {
			?>
				<script>
					Swal.fire({
						icon: "error",
						title: "Oops...",
						text: "<?php echo $error; ?>",
					});
				</script>
		<?php
			}
			$_SESSION["errors"] = null;
		}
		?>

		<div class="forms-container">
			<div class="signin-signup">
				<!-- Sign IN form -->
				<form action="login_backend.php" class="sign-in-form" method="post">
					<img src="images/login_logo.png" alt="" width="300">
					<h2 class="title">Sign in</h2>

					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="email" name="userEmail" placeholder="Email" required />
					</div>

					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="userPass" placeholder="Password" required />
					</div>

					<input type="submit" value="Login" class="btn solid" name="login" />

				</form>

				<!-- Sign UP form -->
				<form action="register_backend.php" class="sign-up-form" method="post">
					<h2 class="title">Sign up</h2>

					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="number" name="userEmployID" placeholder="Employee ID" required>
					</div>

					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="userFname" placeholder="First Name" required>
					</div>

					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="userMname" placeholder="Middle Name" required>
					</div>

					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="userLname" placeholder="Last Name" required>
					</div>

					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="email" name="userEmail" placeholder="Email" />
					</div>


					<select class="input-field" name="userPosition" required>
						<option value="" disabled selected>Select School Position</option>
						<option value="admin">Admin</option>
						<option value="dean">Dean</option>
						<option value="chairperson">Chairperson</option>
					</select>

					<select class="input-field" name="userCollege" required>
						<option value="" disabled selected>Select College</option>
						<option value="coed">COLLEGE OF EDUCATION</option>
						<option value="coe">COLLEGE OF ENGINEERING</option>
						<option value="cot">COLLEGE OF TECHNOLOGY</option>
						<option value="come">COLLEGE OF MANAGEMENT AND ENTREPRENEURSHIP</option>
						<option value="cas">COLLEGE OF ARTS AND SCIENCES</option>
						<option value="ccict">COLLEGE OF COMPUTER INFORMATION AND COMMUNICATIONS TECHNOLOGY</option>
					</select>

					<select class="input-field" name="userProgram" required>
						<option value="" disabled selected>Select Program</option>
						<!-- College of Education Programs -->
						<option value="">BEEd - Bachelor in Elementary Education</option>
						<option value="">BECEd - Bachelor in Early Childhood Education</option>
						<option value="">BSNEd - Bachelor in Special Need Education</option>
						<option value="">BSEd-Math - Bachelor in Secondary Education (Mathematics)</option>
						<option value="">BSEd-Science - Bachelor in Secondary Education (Science )</option>
						<option value="">BSEd-Values Ed - Bachelor in Secondary Education (Values Education)</option>
						<option value="">BSEd-English - Bachelor in Secondary Education (English)</option>
						<option value="">BSEd-Filipino - Bachelor in Secondary Education (Filipino)</option>
						<option value="">BTLEd-IA - Bachelor in Technology and Livelihood Education (Industrial Arts)</option>
						<option value="">BTLEd-HE - Bachelor in Technology and Livelihood Education (Home Economics)</option>
						<option value="">BTLEd-ICT - Bachelor in Technology and Livelihood Education (Information and Communication Technology)</option>
						<option value="">BTVTEd-Draft - Bachelor in Technical and Vocational Teacher Education (Architectural Drafting)</option>
						<option value="">BTVTEd-Auto - Bachelor in Technical and Vocational Teacher Education (Automotive Technology)</option>
						<option value="">BTVTEd-Food - Bachelor in Technical and Vocational Teacher Education (Food Services Management Technology)</option>
						<option value="">BTVTEd-Elec - Bachelor in Technical and Vocational Teacher Education (Electrical Technology)</option>
						<option value="">BTVTEd-Elex - Bachelor in Technical and Vocational Teacher Education (Electronics Technology)</option>
						<option value="">BTVTEd-GFD - Bachelor in Technical and Vocational Teacher Education (Garments, Fashion and Design Technology)</option>
						<option value="">BTVTEd-WF - Bachelor in Technical and Vocational Teacher Education (Welding and Fabrication Technology)</option>

						<!-- College of Engineering -->
						<option value="">BSCE - Bachelor of Science in Civil Engineering</option>
						<option value="">BSCPE - Bachelor of Science in Computer Engineering</option>
						<option value="">BSECE - Bachelor of Science in Electronics Engineering</option>
						<option value="">BSEE - Bachelor of Science in Electrical Engineering</option>
						<option value="">BSIE - Bachelor of Science in Industrial Engineering</option>
						<option value="">BSME - Bachelor of Science in Mechanical Engineering</option>

						<!-- College of Technology -->
						<option value="">BSMx - Bachelor of Science in Mechatronics</option>
						<option value="">BSGD - Bachelor of Science in Graphics and Design</option>
						<option value="">BSTechM - Bachelor of Science in Technology Management</option>
						<option value="">BIT Automotive Technology - Bachelor of Industrial Technology (Automotive Technology)</option>
						<option value="">BIT Civil Technology - Bachelor of Industrial Technology (Civil Technology)</option>
						<option value="">BIT Cosmetology - Bachelor of Industrial Technology (Cosmetology)</option>
						<option value="">BIT Drafting Technology - Bachelor of Industrial Technology (Drafting Technology)</option>
						<option value="">BIT Electrical Technology - Bachelor of Industrial Technology (Electrical Technology)</option>
						<option value="">BIT Electronics Technology - Bachelor of Industrial Technology (Electronics Technology)</option>
						<option value="">BIT Food Preparation and Services Technology - Bachelor of Industrial Technology (Food Preparation and Services Technology)</option>
						<option value="">BIT Furniture and Cabinet Making - Bachelor of Industrial Technology (Furniture and Cabinet Making)</option>
						<option value="">BIT Garments Technology - Bachelor of Industrial Technology (Garments Technology)</option>
						<option value="">BIT Interior Design Technology - Bachelor of Industrial Technology (Interior Design Technology)</option>
						<option value="">BIT Machine Shop Technology - Bachelor of Industrial Technology (Machine Shop Technology)</option>
						<option value="">BIT Power Plant Technology - Bachelor of Industrial Technology (Power Plant Technology)</option>
						<option value="">BIT Refrigeration and Air-conditioning Technology - Bachelor of Industrial Technology (Refrigeration and Air-conditioning Technology)</option>
						<option value="">BIT Welding and Fabrication Technology - Bachelor of Industrial Technology (Welding and Fabrication Technology)</option>

						<!-- College of Management and Entrepreneurship -->
						<option value="">BPA - Bachelor of Public Administration</option>
						<option value="">BSHM - Bachelor of Science in Hospitality Management</option>
						<option value="">BSBA- MM - Bachelor of Science in Business Administration Major in Marketing Management</option>
						<option value="">BSTM - Bachelor of Science in Tourism Management</option>

						<!-- College of Information and Communications Technology -->
						<option value="bsit">BSIT - Bachelor of Science in Information Technology </option>
						<option value="bsis">BSIS - Bachelor of Science in Information Systems </option>
						<option value="bit-ct">BIT-CT - Bachelor in Industrial Technology- Computer Technology</option>

						<!-- College of Arts and Sciences -->
						<option value="">BAEL-ECP - Bachelor of Arts in English Language (English Across the Professions)</option>
						<option value="">BAEL-ELSD - Bachelor of Arts in English Language (English Language Studies as Discipline)</option>
						<option value="">BAL–LCS - Bachelor of Arts in Literature (Literature And Cultural Studies)</option>
						<option value="">BAL–LAP - Bachelor of Arts in Literature (Literature Across The Professions)</option>
						<option value="">BS MATH - Bachelor of Science in Mathematics</option>
						<option value="">BS STAT - Bachelor of Science in Statistics </option>
						<option value="">BSDevCom - Bachelor of Science in Development Communication</option>
						<option value="">BAF - Batsilyer ng Sining sa Filipino </option>
						<option value="">BS PSYCH - Bachelor of Science in Psychology</option>
						<option value="">Bachelor of Science in Nursing</option>
					</select>

					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="userPass" placeholder="Create Password" required>
					</div>

					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="userPasscon" placeholder="Confirm Password" required>
					</div>

					<input type="submit" class="btn" value="Sign up" name="submit" />
				</form>
			</div>
		</div>

		<div class="panels-container">
			<div class="panel left-panel">
				<div class="content">
					<h3>New here ?</h3>
					<p>
						Click the button below and join Schedulemate!
					</p>
					<button class="btn transparent" id="sign-up-btn">
						Sign up
					</button>
				</div>
				<img src="https://i.ibb.co/6HXL6q1/Privacy-policy-rafiki.png" class="image" alt="" />
			</div>
			<div class="panel right-panel">
				<div class="content">
					<h3>One of us ?</h3>
					<p>
						Welcome back to Schedulemate! Click the button below
					</p>
					<button class="btn transparent" id="sign-in-btn">
						Sign in
					</button>
				</div>
				<img src="https://i.ibb.co/nP8H853/Mobile-login-rafiki.png" class="image" alt="" />
			</div>
		</div>
	</div>

	<!-- js for the login design -->
	<script src="JS/index.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var collegeSelect = document.querySelector('select[name="userCollege"]');
			var programSelect = document.querySelector('select[name="userProgram"]');
			var programs = {
				"coed": ["BEEd - Bachelor in Elementary Education", "BECEd - Bachelor in Early Childhood Education", "BSNEd - Bachelor in Special Need Education", "BSEd-Math - Bachelor in Secondary Education (Mathematics)", "BSEd-Science - Bachelor in Secondary Education (Science)", "BSEd-Values Ed - Bachelor in Secondary Education (Values Education)", "BSEd-English - Bachelor in Secondary Education (English)", "BSEd-Filipino - Bachelor in Secondary Education (Filipino)", "BTLEd-IA - Bachelor in Technology and Livelihood Education (Industrial Arts)", "BTLEd-HE - Bachelor in Technology and Livelihood Education (Home Economics)", "BTLEd-ICT - Bachelor in Technology and Livelihood Education (Information and Communication Technology)", "BTVTEd-Draft - Bachelor in Technical and Vocational Teacher Education (Architectural Drafting)", "BTVTEd-Auto - Bachelor in Technical and Vocational Teacher Education (Automotive Technology)", "BTVTEd-Food - Bachelor in Technical and Vocational Teacher Education (Food Services Management Technology)", "BTVTEd-Elec - Bachelor in Technical and Vocational Teacher Education (Electrical Technology)", "BTVTEd-Elex - Bachelor in Technical and Vocational Teacher Education (Electronics Technology)", "BTVTEd-GFD - Bachelor in Technical and Vocational Teacher Education (Garments, Fashion and Design Technology)", "BTVTEd-WF - Bachelor in Technical and Vocational Teacher Education (Welding and Fabrication Technology)"],
				"coe": ["BSCE - Bachelor of Science in Civil Engineering", "BSCPE - Bachelor of Science in Computer Engineering", "BSECE - Bachelor of Science in Electronics Engineering", "BSEE - Bachelor of Science in Electrical Engineering", "BSIE - Bachelor of Science in Industrial Engineering", "BSME - Bachelor of Science in Mechanical Engineering"],
				"cot": ["BSMx - Bachelor of Science in Mechatronics", "BSGD - Bachelor of Science in Graphics and Design", "BSTechM - Bachelor of Science in Technology Management", "BIT Automotive Technology - Bachelor of Industrial Technology (Automotive Technology)", "BIT Civil Technology - Bachelor of Industrial Technology (Civil Technology)", "BIT Cosmetology - Bachelor of Industrial Technology (Cosmetology)", "BIT Drafting Technology - Bachelor of Industrial Technology (Drafting Technology)", "BIT Electrical Technology - Bachelor of Industrial Technology (Electrical Technology)", "BIT Electronics Technology - Bachelor of Industrial Technology (Electronics Technology)", "BIT Food Preparation and Services Technology - Bachelor of Industrial Technology (Food Preparation and Services Technology)", "BIT Furniture and Cabinet Making - Bachelor of Industrial Technology (Furniture and Cabinet Making)", "BIT Garments Technology - Bachelor of Industrial Technology (Garments Technology)", "BIT Interior Design Technology - Bachelor of Industrial Technology (Interior Design Technology)", "BIT Machine Shop Technology - Bachelor of Industrial Technology (Machine Shop Technology)", "BIT Power Plant Technology - Bachelor of Industrial Technology (Power Plant Technology)", "BIT Refrigeration and Air-conditioning Technology - Bachelor of Industrial Technology (Refrigeration and Air-conditioning Technology)", "BIT Welding and Fabrication Technology - Bachelor of Industrial Technology (Welding and Fabrication Technology)"],
				"come": ["BPA - Bachelor of Public Administration", "BSHM - Bachelor of Science in Hospitality Management", "BSBA- MM - Bachelor of Science in Business Administration Major in Marketing Management", "BSTM - Bachelor of Science in Tourism Management"],
				"ccict": ["BSIT - Bachelor of Science in Information Technology", "BSIS - Bachelor of Science in Information Systems", "BIT-CT - Bachelor in Industrial Technology- Computer Technology"],
				"cas": ["BAEL-ECP - Bachelor of Arts in English Language (English Across the Professions)", "BAEL-ELSD - Bachelor of Arts in English Language (English Language Studies as Discipline)", "BAL–LCS - Bachelor of Arts in Literature (Literature And Cultural Studies)", "BAL–LAP - Bachelor of Arts in Literature (Literature Across The Professions)", "BS MATH - Bachelor of Science in Mathematics", "BS STAT - Bachelor of Science in Statistics", "BSDevCom - Bachelor of Science in Development Communication", "BAF - Batsilyer ng Sining sa Filipino", "BS PSYCH - Bachelor of Science in Psychology", "Bachelor of Science in Nursing"]
			};

			collegeSelect.addEventListener('change', function() {
				var selectedCollege = this.value;
				programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>'; // Clear previous options
				if (selectedCollege in programs) {
					programs[selectedCollege].forEach(function(program) {
						var option = document.createElement('option');
						option.textContent = program;
						option.value = program;
						programSelect.appendChild(option);
					});
				}
			});
		});
	</script>

	<!-- //Depending on the userPOsition the input feilds for userCollege and userProgram will be hidden -->
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var positionSelect = document.querySelector('select[name="userPosition"]');
			var programSelect = document.querySelector('select[name="userProgram"]');
			var collegeSelect = document.querySelector('select[name="userCollege"]');

			// Function to hide/show program and college select fields
			function toggleFields() {
				if (positionSelect.value === 'dean') {
					programSelect.style.display = 'none';
					collegeSelect.style.display = 'block'; // Show college for Dean
				} else if (positionSelect.value === 'admin') {
					programSelect.style.display = 'none';
					collegeSelect.style.display = 'none'; // Hide both college and program for Admin
				} else {
					programSelect.style.display = 'block';
					collegeSelect.style.display = 'block'; // Show both college and program for other positions
				}
			}

			// Initial toggle based on the default value
			toggleFields();

			// Event listener for userPosition change
			positionSelect.addEventListener('change', toggleFields);
		});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var positionSelect = document.querySelector('select[name="userPosition"]');
			var programSelect = document.querySelector('select[name="userProgram"]');
			var collegeSelect = document.querySelector('select[name="userCollege"]');

			// Function to hide/show program and college select fields
			function toggleFields() {
				if (positionSelect.value === 'chairperson') {
					programSelect.required = true;
					collegeSelect.required = true;
				} else if (positionSelect.value === 'dean') {
					programSelect.required = false;
					collegeSelect.required = true;
				} else if (positionSelect.value === 'admin') {
					programSelect.required = false;
					collegeSelect.required = false;
				} else {
					// For other positions, make both fields required
					programSelect.required = true;
					collegeSelect.required = true;
				}
			}

			// Initial toggle based on the default value
			toggleFields();

			// Event listener for userPosition change
			positionSelect.addEventListener('change', toggleFields);
		});
	</script>
</body>

</html>