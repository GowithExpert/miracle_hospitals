<!-- Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
@Description: The code of the released Hospital software, does NOT lie under
GLP (General Public License) But it has proprietary copyrights. The purpose of the
Informing for public that, the Hospital web based mobile responsible application its associated
different roles are protected by the mentioned copyrights. *
@Version: Miracle Hospital - 1.0
@Author: Neoark Software
@Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
@Email: sales@neoarksoftware.com | support@neoarksoftware.com
@website: www.neoarks.com
@Phone: +91-880-090-0164
Date: 21st August, 2023 -->
<style>
	body {
		font-family: Arial, sans-serif;
	}

	h3 {
		font-size: 14px !important;
	}

	.modal-button {
		padding: 8px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-weight: bold;
		transition: background-color 0.2s;
	}

	.modal-button.delete {
		background-color: #005197;
		color: #fff;
		margin-top: 32px !important;
		border: none !important;
	}
	.modal-button.delete:hover {
		background-color: #005197 !important;
		color: #fff !important;
	}

	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		max-height: 100% !important;
		background-color: rgba(0, 0, 0, 0.5);
	}

	.modal-content {
		background-color: #fff;
		margin: 20% auto;
		padding: 20px;
		width: 25%;
		position: relative;
		border-radius: 8px;
		box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
	}

	.modal-icon.cancel {
		position: absolute;
		top: 10px;
		right: 15px;
		font-size: 20px;
		cursor: pointer;
	}


	/* Adjustments for mobile view */
	@media (max-width: 600px) {
		.modal-content {
			width: 80%;
			margin: 30% auto;
		}

		.modal-content p {
			/* text-align: center; */
			margin: 10px 0;
			/* Add margin to top and bottom for spacing */
			white-space: normal;
			/* Add this property to reset white-space handling */
			word-wrap: break-word;
		}
	}
</style>