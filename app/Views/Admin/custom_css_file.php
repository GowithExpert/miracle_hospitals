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
<head>
<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
<!----Materialize CSS File include --->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/materialize/css/materialize.css'); ?>">
<!-- <link rel="stylesheet" type="text/css" href="<//?= base_url('public/assets/fontawesome/css/all.css'); ?>"> -->

<link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
</head>
<style type="text/css">

/************css_file.css - START ***************/
.txt_break-at100 {
		width: 100px;
		word-break: break-all;
		font-size: 13px !important;
		font-weight: 400 !important;
	}

	.txt_break-at200 {
		width: 200px;
		word-break: break-all;
		font-size: 14px !important;
		font-weight: 500 !important;
	}

	.txt_break-at300 {
		width: 300px;
		word-wrap: break-word;    /*Before it was word-break: break-all; */
		font-size: 13px !important;
		font-weight: 400 !important;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	}

	.dropdown-content li>a,
	.dropdown-content li>span {
		font-size: 11px !important;
		color: #26a69a;
		display: block;
		line-height: 22px;
		padding: 14px 5px !important;
	}

	.colour_hver {
		color: grey !important;
		text-decoration: itelic !important;
		-webkit-tap-highlight-color: transparent;
		font-size: 14px;
	}

	.colour_hver:focus {
		color: gray !important;
	}

	*::-webkit-scrollbar {
		width: 0.6em;
	}

	*::-webkit-scrollbar-thumb {
		background-color: #005197;
	}
/*************css_file.css - END ****************/



/*************** Admin Css - START ***************/

.scrollToTop {
  bottom: 60px;
  display: none;
  font-size: 32px;
  line-height: 38px;
  font-weight: bold;
  height: 50px;
  position: fixed;
  right: 50px;
  text-align: center;
  text-decoration: none;
  width: 50px;
  z-index: 999;
  color: #fff;
  /*border-color: #0059b3;*/
  background-color: #0059b3;
  border: 1px solid #005197;
  transform: rotate(43deg);
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  -ms-transition: all 0.5s;
  -o-transition: all 0.5s;
  transition: all 0.5s;
}

/* line 714, ../../../app/sass/custom_style.scss */
.scrollToTop > i {
  transform: rotate(-45deg);
  font-size: 32px;
}
/* line 717, ../../../app/sass/custom_style.scss */
.scrollToTop:hover {
  background-color: #fff;
  text-decoration: none;
  outline: none;
  color: #0059b3;
}
/* line 722, ../../../app/sass/custom_style.scss */
.scrollToTop:focus {
  background-color: #fff;
  text-decoration: none;
  outline: none;
  color: #0059b3;
}


.doctors-nav .slick-prev,
.doctors-nav .slick-next {
  background-color: transparent;
  border: 2px solid #005197;
  border-radius: 50%;
  color: #0059b3;
  cursor: pointer;
  display: block;
  font-size: 0;
  font-weight: bold;
  height: 50px;
  line-height: 44px;
  margin-top: -10px;
  outline: medium none;
  padding: 10px;
  position: absolute;
  text-align: center;
  top: 50%;
  width: 50px;
  -webkit-transition: all 0.5s;
  -o-transition: all 0.5s;
  transition: all 0.5s;
}

.doctors-nav .slick-next {
  background-position: center center;
  background-repeat: no-repeat; 
  right: -6%;
  margin-top: -20px !important;
}
.doctors-nav .slick-prev {
  background-position: center center;
  background-repeat: no-repeat;
  left: -6%;
  margin-top: -20px !important;
}

.testimonial-parg::before {
	color: #0059b3;
	font-size: 20px;
	padding: 3px;
}

.testimonial-parg:after {
	color: #0059b3;
	font-size: 20px;
	padding: 3px;
}

.testimonial-nav .slick-dots li.slick-active {
	background-color: #0059b3;
}


.slick-dots li button {
    border: 0;
    background: transparent;
    display: block;
    height: 20px;
    width: 20px;
    outline: none;
    line-height: 0;
    font-size: 0;
    color: transparent;
    /*padding: 5px;*/
    cursor: pointer;
}


.slick-dots li button:before {
    position: absolute;
    top: 0px;
    left: 0px;
    content: "•";
    width: 30px;
    height: 30px;
    font-family: "slick";
    font-size: 6px;
    line-height: 20px;
    text-align: center;
    color: black;
    opacity: 0.25;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}


.testimonial-nav .slick-dots li.slick-active button::before {
	color: #0059b3;
	padding-right: 20px;
}


.slick-dots li {
	border: 2px solid #0059b3 !important;
}

.testimonial-img {
    border: 3px solid #0059b3;
}


.navbar-default {
    background-color: #0059b3;
    border-color: #005197;
}

.single-top-feature {
   background-color: #166ab5fa;
}

.opening-hours {
	 background-color: #fff;
}

.footer-bottom {
    background-color: #005197;
    display: inline;
    float: left;
    width: 100%;
    padding: 20px 0;
    text-align: center;
    color: #fff;
}

.readmore_area a span {
  background-color: #005197;
}

.line {
    border-bottom: 2px solid #005197;
    background: none repeat scroll 0 0 transparent;
    height: 1px;
    margin: 0 auto 45px;
    padding: 5px;
    position: relative;
    width: 120px;
}

.line::after {
    border-top-color: #005197;
    border-width: 8px;
    margin-left: -8px;005197
}

.media-icon {
    background-color: #0059b3;
    border: 2px solid #005197;
}

.whyChoose-right .media:hover .media-icon {
    background-color: #fff;
    color: #005197 !important;
}

.doctor_specialization {
    background-color: #166ab5fa !important;
    margin: 0px !important;
    color: white !important;
    text-align: center !important;
    padding: 5px;
    font-size: 17px;
    border: 1px #005197 solid;
}

.doctor_appointment {
    padding: 5px;
    text-align: center;
    font-size: 17px;
    color: white;
    background-color: #166ab5fa !important;
    border: 1px #005197 solid;
}

h2 {
  font-family: "Raleway", sans-serif;
  color: #606060;
  font-size: 30px;
  font-weight: 700;
  line-height: 25px;
  margin: 0;
}


.blog-comments-box li > h2 {
    color: #0059b3 !important;
    font-size: 33px;
    line-height: 32px;
    margin-bottom: 0;
    padding-bottom: 0;
}

.blog-comments-box li>h2 {
    color: #0059b3 !important;
}


.blog-comments-box li span {
    color: #0059b3 !important;
    display: block;
    font-size: 16px;
}

.service-icon .service-icon-effect {
    box-shadow: 0 0 0 4px #0059b3;
}


.service-icon-effect {
    display: inline-block;
    font-size: 0px;
    margin: 15px 30px;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    text-align: center;
    position: relative;
    z-index: 1;
    color: #0059b3 !important;
}


.service-icon .service-icon-effect {
    box-shadow: 0 0 0 4px #0059b3;
}

.service-icon .service-icon-effect:after {
    box-shadow: 3px 3px #005197;
}

.contact-info > p span {
    color: #005197 !important;
}

.footer-service li > a span {
    color: #005197 !important;
}

.footer-service li>a {
    border-bottom: 1px solid #005197;
}


.colr_hver:hover {
    color: #005197 !important;
}

.whyChoose-right .media-heading {
    font-size: 22px;
    line-height: 1.3em;
    padding-bottom: 6px;
    color: #0059b3 !important;
}


.tag-nav a:hover, .tag-nav a:focus {
    background-color: #0059b3;
    border-color: #005197;
}

.colr_hver:hover {
    color: #0059b3 !important;
}

.dropdown-menu > li > a {
    clear: both;
    color: #0059b3;
    display: block;
    font-weight: bold;
    line-height: 1.42857;
    padding: 5px 20px;
    white-space: nowrap;
    -moz-transition: all 0.5s;
    -o-transition: all 0.5s;
    -webkit-transition: all 0.5s;
    transition: all 0.5s;
}


.footer-service li>a {
    border-bottom: 1px solid #005197;
}


*::-webkit-scrollbar-thumb {
    background-color: #0059b3;
}


.service-icon-effect {
    display: inline-block;
    font-size: 0px;
    margin: 15px 30px;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    text-align: center;
    position: relative;
    z-index: 1;
    color: #0059b3 !important;
}

.txt:hover {
    color: #0059b3 !important;
}


a {
    color: #0059b3;
    text-decoration: none;
}


.single-service > h3 a:hover {
    color: #585858f7 !important;
}

.whyChoose-right .media-heading {
    font-size: 22px;
    line-height: 1.3em;
    padding-bottom: 6px;
    color: #606060 !important;
}

/*************** Admin Css - END ***************/

	/*.navbar-default {
	    background-color: #005197;
	    border-color: #005197;
	}

	.footer-bottom {
	    background-color: #005197;
	    display: inline;
	    float: left;
	    width: 100%;
	    padding: 20px 0;
	    text-align: center;
	    color: #fff;
	}*/

	.single-footer-widget {
	  float: left;
	  display: inline;
	  width: 100%;
	  padding: 0 10px;
	  color: #555;
	  padding: 10px 10px;
	}

	a {
	    text-decoration: none;
	    color: #786d6d;
	}

	.btn_algin:hover {
		color: #786d6d !important;
	}

	body {
	    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
	    font-size: 14px;
	    line-height: 1.42857143;
	    color: #786d6d;
	    background: rgb(224, 227, 231);
	}
	
	

	.v_ellip{
		background: none !important;
		color: #333 !important;
		box-shadow: none !important;
		margin-top: 8px;
	}
	.v_ellip:hover{
		color: #333 !important;
	}
	.v_ellip:focus{
		color: #333 !important;
	}
	.section-title {
		font-size: 20px;
		font-weight: bold;
		margin-top: 20px;
		color: #004080;
	}
	.plcehldr_lft::placeholder{
		font-family: inherit;
		font-weight: 600 !important;
		text-align: left;
	
	}
	.txtara {
		font-family: serif !important;
	}

	.txtara:focus {
		outline: none !important;
	}
	.plcehldr_algn_lft::placeholder {
      text-align: left !important;
    }
	.plcehld_clor::placeholder {
		color: grey;
		font-size: 15px;
		font-weight: 500;
		font-family: serif !important;
	}
	.headng {
		font-weight: 600;
		font-size: 30px !important;
	}
	.section-heading {
		/*padding-top: 45px !important;*/
	}
	#total_hospital_expenses{
		text-align: right;
		border:1px solid silver;
		border-radius: 3px;
	}
	#total_paid_amount{
		text-align: right;
		border:1px solid silver;
		text-indent: 6px;
	}	
	#dues_amount{
		text-align: right;
		border:1px solid silver;
		text-indent: 6px;
		border-radius: 3px;
	}
	.options-container {
		position: relative;
		display: inline-block;
	}

	.options-btn {
		background-color: #fff;
		border: none;
		padding: 6px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		border-radius: 4px;
		color: #333;
	}

	.options-content {
		display: none;
		position: absolute;
		background-color: #f9f9f9;
		min-width: 160px;
		box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
		z-index: 2;
	}
	.btn_wdth_hgt{
		width: 100%;
    	height: 40px;
	}

	.options-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}
	.date_inpt {
      text-indent: 3px !important;
    }
	.fnt_size_expen {
      display: flex;
      justify-content: center;
    }
	.plcehldr_algn_lft {
      box-sizing: border-box !important;
      padding-right: 5px !important;
    }
	.validation_error_msg {
      font-size: 11px;
      color: red;
      position: absolute;
    }
	.options-content a:hover{
		color: #333 !important;
		background-color: #f1f1f1;
	}
	.options-btn:hover{
		color: #333 !important;
	}
	.options-container.active .options-content {
		display: block;
	}
	.dis_blk{
		display: block;
	}
	.inpt_pad{
		box-sizing: border-box !important;
		padding-right: 14px !important;
	}
	.section-content {
		margin-left: 20px;
	}
	.highlight {
		padding: 10px;
		border-radius: 5px;
		margin-bottom: 20px;
	}
	.footer {
		text-align: center;
		margin-top: 50px;
		font-size: 14px;
		color: #888;
	}
	.mrgn_50{
		margin-top: 50px;
	}
	.custm_mrgn{
		margin: 1.3rem 0 0.912rem 0 !important;
	}
	.txt_flx{
		position: absolute !important;
		align-items: center !important;
		margin-top: 50px !important;
		margin-left: -14px !important;
	}
	.p_content{
		text-align: center;
		font-size: 14px
		margin: 0px 0px 15px 0px !important;
	}
	.input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }
	.brdr_none{
		border: none !important;
	}
	.btn_algn {
      padding: 10px 20px;
      background-color: #005197;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }
	.btn_algnment{
		display: contents !important;
	}
	.btn_algn:focus {
      background: #005197 !important;
    }

    .btn_algn:hover {
      background-color: #005197 !important;
      color: #fff;
    }

    .input-group input[type="text"],
    .input-group input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    .button-group {
      text-align: center;
      margin-top: 40px;
    }
	#reportToggle {
		display: flex;
		align-items: center;
		cursor: pointer;
		padding: 10px;
		border-top: 1px solid #ccc;
	}
	#mobile{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 46px;
		border-radius: 3px;
	}
	.asterisk_symbol_phone{
		position: absolute;
		top: 36%;
		left: 80px;
		transform: translateY(-50%);
		color: red;
		visibility: visible;
		opacity: 1;
		transition: visibility 0s, opacity 0.2s;
	}
	#success-message {
		background-color: green;
		color: #fff;
		padding: 7px;
		position: absolute;
		z-index: 999;
	}
	.filter_doc{
		border: 1px solid silver;
		height: 26px;
		padding: 3px; 
		font-size: 13px;
	}
	.cam_icon:hover{
		color: #333 !important;
	}

	#error-message {
		background-color: #f44336;
		color: #fff;
		padding: 7px;
		position: absolute;
		z-index: 999;
	}
	.error-text{
		font-size: 14px;
	}
	#addReportFieldButton {
		display: block;
		cursor: pointer;
	}
	input[type="checkbox"] {
		margin-right: 10px;
		transform: scale(1.5);
	}

	input[type=checkbox] {
		margin: 4px 50px 0 !important;
	}

	.mt4 {
		margin-top: 1rem !important;
	}

	.form-stepper .form-stepper-completed .form-stepper-circle:hover {
		background-color: blue !important;
	}

	ul.form-stepper li a .form-stepper-circle {
		background: #fff !important;
	}
	.row_flex {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		margin-right: 0px !important;
		margin-left: 0px !important;
	}


	.report-field {
		margin-bottom: 10px;
	}
	@media (max-width: 991px) {
		.mrg_rgt {
			margin-right: 30px;
		}
	}

	@media (max-width: 480px) {
		.rowhead {
			margin-left: 0px;
			margin-right: 0px;
		}

		ul.form-stepper {
			margin-left: 10px;
			margin-right: 10px;
		}

		.mrgn_lft_rght {
			margin-left: 0px;
			margin-right: 0px;
		}

		input[type=checkbox] {
			margin: 4px 5px 0 !important;
		}
	}

	.mrgn_lft_rght {
		margin-left: 80px;
		margin-right: 80px;
	}
	#reportSection {
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.3s ease-in-out;
	}

	#reportToggleIcon {
		transition: transform 0.3s ease-in-out;
	}
	i {
		font-size: 14px !important;
	}
	input[type=file] {
		display: inline !important;
	}
	input[type="file"]::-webkit-file-upload-button {
		border-radius: 10px;
	}

	input[type="file"]::-moz-file-upload-button {
		border-radius: 10px;
	}

	input[type="file"]::file-selector-button {
		border-radius: 10px;
	}
	[type="checkbox"]+span:not(.lever) {
		padding-left: 0px !important;
	}

	[type="checkbox"]+span:not(.lever):before,
	[type="checkbox"]:not(.filled-in)+span:not(.lever):after {
		content: none !important;
	}
	.btn-browse {
		background-color: #3498db;
		color: #fff;
		cursor: pointer;
	}

	.btn-download {
		color: #333;
		cursor: pointer;
		display: block;
	}

	.btn-download:hover {
		color: #005197;
	}
	.select-wrapper {
		position: relative;
		display: inline-block;
		width: 100%;
	}
	.file-input {
		display: none;
	}
	.row12{
		display: flex; 
		justify-content: center; 
		align-items: center; 
		height: 15vh;
	}
	.crd_positi{
		position: relative;
	}
	.patent_backg{
		background: url(<?= base_url('public/assets/images/patients.jpg') ?>)no-repeat fixed;
		background-size: 100% 100%
	}
	.txt_flx2{
		position: absolute !important;
		align-items: center !important;
		margin-top: 50px !important;
	}
	#overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		display: none;
		z-index: 9998;
	}
	#custom_popup {
		position: fixed;
		top: 20%;
		left: 50%;
		transform: translate(-50%, -50%);
		width: 1170px;
		max-width: 100%;
		background-color: #3498db;
		color: #fff;
		text-align: left;
		padding: 8px;
		box-sizing: border-box;
		z-index: 9999;
	}
	#popup_message {
		padding: 10px;
		background: green;
		color: white;
		font-weight: 500;
		width: 100%;
	}
	.label1{
		line-height: 0 !important;
	}
  #avatar-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        #camera-container {
            position: absolute;
            bottom: 15px;
            right: 8px;
            background-color: lightgray;
            padding: 5px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 17px;
        }
		#camera-container-1 {
            position: absolute;
            bottom: 30px;
            right: 8px;
            background-color: lightgray;
            padding: 5px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 17px;
        }
		#camera-container-2 {
            position: absolute;
            bottom: 25px;
            right: 8px;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 5px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 17px;
        }
		label{
			font-family: 'Raleway', sans-serif;
			font-weight: 600;
			color: #786d6d; 
			font-size: 12px;
			margin-top: 14px;
			display: inline-block;
		}

		.sty {
			font-family: serif;
			font-weight: 600;
			color: #786d6d;
		}

		@media (max-width: 1116px) {
			label{
				margin-top: 8px;
				font-size: 12px;
			}
			.three_dots{
				margin-top: 9px !important;
			}
			.v_ellip{
				margin-top: -1px !important;
			}
		}
		.three_dots{
			margin-top: 15px;
			cursor: pointer;
		}
		#admission_fee{
			text-align: right;
			border:1px solid silver;
			border-radius: 3px;
		}
		#other_charges{
			text-align: right;
			border:1px solid silver;
			text-indent: 6px;
		}	
		#total_payable{
			text-align: right;
			border:1px solid silver;
			text-indent: 6px;
			font-size: large;
			border-radius: 3px;
		}
		input[type=number]:not(.browser-default){
			border-radius: 3px;
		}
		.text_align{
			box-sizing: border-box !important;
    		padding-right: 14px !important;
		}

        #camera-icon-label {
            color: #fff;
            font-size: 14px;
            cursor: pointer;
        }

        #camera-icon {
            color: #333;
        }
        #camera-icon i {
            display: block;
            margin: auto;
        }
		@media (max-width: 767px) {
            #camera-container {
                right: 0px !important;
            }
        }

  #popup_error_message {
    padding: 10px;
    background: red;
    color: white;
    font-weight: 500;
  }
	.cutom_messge_styl{
			position: absolute; 
			top: 0; 
			left: 0; 
			z-index: 1; 
			width: 100%
		}
	.txt_flx3{
		position: absolute !important;
		align-items: center !important;
		margin-top: 50px !important;
		margin-left: -28px !important;
	}
	.dr_detil{
            font-weight: 600;
        }

	#input_file {
		border: 1px solid silver;
		padding: 6px !important;
		width: 100%;
		margin-bottom: 15px;
		font-size: 14px;
		font-weight: 500;
		height: 40px;
		border-radius: 3px;
	}

	@media (max-width: 480px) {
		a {
			font-size: 1.5rem;
		}
	}
	.align_del_btn{
		display: flex !important;
		justify-content: center !important;
		margin-top: -10px !important;
	}
	.del_pop_text{
		margin-top: 12px !important;
	}
	#dshbrd_mgn{
		margin-top: 10px; 
		margin-bottom: 0px;
	}
	#lght_red{
		background: #f94e42
	}
	.margn_top{
		margin-top: 10px;
	}
	.dshbrd_sty{
		text-transform: capitalize !important;
		font-weight: bold;
		color: white !important;
	}
	.div_higt{
		height: 370px; 
		width: 100%;
	}
	.doc_heder{
		/* background: #f94e42; */
		padding: 5px;
		border-bottom: 1px dashed silver
	}
	#patnt_heder{
		background: orange;
		padding: 10px;
	}
	#dr_heder{
		background: blue;
		padding: 10px;
	}
	.bld_dnr_hedr{
		background: blue; 
		padding: 5px;
		margin-top: -10px;
	}

	

	.btn_algin:focus {
		color: #333 !important;
	}
	.btn_shaow{
		box-shadow: none;
	}
	.btn_shaow:hover{
		background: #fff;
		color: #005197;
		box-shadow: none;
	}
	.appoint_bdy{
		background: #f5eee7;
		font-family: "Poppins", sans-serif;
		font-weight: 300;
	}
	.txt_fnt{
        font-family: serif !important;
    }
	.search-button:focus{
        background: inherit;
    }
	@media (max-width: 600px) {
        #lookup {
            padding-right: 50px; /* Adjust this value for smaller screens */
        }
        .txt_indnt{
            text-indent: 5px !important;
        }
    }
	.txt_indnt{
		background: #fff !important;
		text-indent: 15px;
	}
	.indnt{
		text-indent: 12px;
	}
	.indnt8{
		text-indent: 8px;
	}
	.col_drk_gry{
		color: #939393;
		font-weight: 600;
	}
	.hedng2{
		font-weight: 600;
		font-size: 20px;
	}
	.hedng1{
		font-weight: 500;
		font-size: 20px;
	}
	.txt_indnt2{
		background: #fff !important;
		text-indent: 79px;
	}
	.back_wite{
		background: #fff !important;
	}

	button:focus {
		background: #fff !important;
		color: #0059b3 !important;
	}
	
	.sigup_btn:focus{
		background: #005197 !important;
	}
	.sigup_btn:hover{
		background: #005197 !important;
	}
	.ihve_alrdy_acc:hover{
		background: #005197 !important;
	}
	.frgt_pass_submit:hover{
		background: #005197 !important;
	}
	.frgt_pass_submit:focus{
		background: #005197 !important;
	}
	.frgt_pass_submit{
		background: #005197; 
		margin-top: 17px; 
		border-radius: 5px;
	}
	.popup_close_icon {
		position: absolute;
		top: 0px;
		right: 0px;
		cursor: pointer;
		height: 16px;
	}
	.trm_condi_bdy {
		font-family: Arial, sans-serif;
		line-height: 1.6;
		text-align: justify;
		background-color: #f5f5f5; 
	}
	p {
		margin: 0 0 0px !important;
	}
	input:focus {
		box-shadow: none !important;
	}
	.trm_condi_txt {
		text-align: center;
		margin-bottom: 30px;
		color: #004080;
		font-weight: 600;
		margin-top:10px;
	}

	.modal-button:focus {
		background: #005197 !important;
	}
	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.6);
		max-height: 100% !important;
	}

	.modal-content {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
		max-width: 400px;
	}

	@media (max-width: 768px) {
		.plus_icon_mgt {
			font-size: 38px !important;
			margin-top: 15px;
		}
		.scroll-container {
			overflow-x: auto;
			white-space: nowrap;
		}
		.v_ellip{
			margin-top: -1px !important;
		}
	}
	.inpt_area{
		border-radius: 5px !important;
	}
	a{
		text-decoration: none !important;
	}
	#mrgn_top{
		margin-top: 10%;
	}
	.forgt_card_cntnt{
		background: #f9f9f9;
		box-shadow: none; 
		box-shadow: 3px 2px 8px;
	}
	.form-control {
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}

	.text-primary {
		font-weight: 600;
		margin-top: 5px;
		font-size: 20px;
		color: black;
	}

	.midsec {
		padding: 0px 50px 0px 50px;
	}

	/*
	.card .card-content {
	  padding: 25px 25px 0px 25px;
	  border-radius: 0 0 2px 2px;
	}
	*/
	/* this code is new for reset password to give padding from bottom */
	.card .card-content {
	  padding: 25px 25px 25px 25px;
	  border-radius: 0 0 2px 2px;
	}

	.card {
		background: #ffffff;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 10px;
		border: 0;
		margin-bottom: 1rem;
	}

	
	.ihve_alrdy_acc{
		background: #005A87;
		text-transform: capitalize;
		width: 100%;
		font-weight: 500;
		margin-top: 10px;
		height: 40px;
		border-radius: 5px;
	}
	.sigup_btn{
		background: #005197;
		text-transform: capitalize;
		width: 100%;
		font-weight: 500;
		margin-top: 10px;
		height: 40px;
		border-radius: 5px;
	}
	.brdr_rdius{
		border-radius: 10px !important;
	}
	.forgot_pass_btn{
		font-size: 11px;
		float: right;
		color: #005A87; 
		margin-top: 3px;
		font-weight: 500
	}
	.flot_red{
		/* float: right; */
		margin-top: 0px;
		font-weight: 600;
		/* color:red; */
		display: flex;
		justify-content: center;
	}
	.btn_sty{
		color: #005197;
	}
	.btn_sty:hover{
		color: #005197;
	}
	.login_btn{
		background: #005197 !important;
		text-transform: capitalize;
		width: 100%;
		font-weight: 500;
		margin-top: 10px;
		height: 40px;
		border-radius: 5px;
	}
	.mandatory_phone {
		position: absolute;
		left: 78px;
		top: 40%;
		color: red;
		transform: translateY(-50%);
	}
	.asterisk_phone{
		position: absolute;
		left: 78px;
		top: 40%;
		color: red;
		transform: translateY(-50%);
	}
	img{
		display: block;
	}
	#lookup {
        background: #fff !important;
        text-indent: 15px;
        padding-left: 15px !important; /* Add padding to the left side */
        padding-right: 40px; /* Adjust this value based on your icon width and padding */
        box-sizing: border-box; /* Include padding in the width calculation */
        width: 100%;
        box-sizing: border-box;
        overflow: auto; /* Add overflow property for scrolling */
    }

    .search-button {
        position: absolute;
        top: 23px;
        right: 12px;
        height: 100%; /* Ensure the button takes the full height of the search area */
        display: flex;
        align-items: center;
        justify-content: center;
    }
	.search-area {
        position: relative;
    }
	.mandatory_gender{
		position: absolute;
		left: 8px;
		top: 40%;
		color: red;
		transform: translateY(-50%);
	}
	.mandatory_gender1{
		position: absolute;
		left: 13px;
		top: 30%;
		color: red;
		transform: translateY(-50%);
	}
	
	#accountant_name{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}

	#email{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	
	}
	.phone_mandatory{
		padding-left: 82px !important;
	}
	.mobile{
		text-indent: 75px;
	}
	.genderSelect{
		padding: 8px 7px !important;
	}
	.h4_margn {
		margin: 0rem 0 0 0 !important;
	}
	.h5_margn{
		margin: 0.093333rem 0 1.656rem 0 !important;
	}
	.selct_gndr {
		background-color: transparent !important;
		width: 100% !important;
		padding: 5px !important;
		border: 1px solid silver !important;
		border-radius: 5px !important;
		height: 40px !important;
		margin-bottom: 9px !important;
	}
	.padig{
		padding-left: 6px !important;
	}
	.btn_posi{
		bottom: 0px !important;
	}
	#pagination nav {
		background: none !important;
		box-shadow: none !important;
	}
	.sub_btn{
		text-transform: capitalize;
		font-weight: 500;
		font-size: 16px;
		bottom: 0px;
		background: #005197;
	}
	.butm{
		bottom: -8px !important;
	}
	#customsucesMsgContainer{
		padding: 10px;
		background: green;
		color: white;
		font-weight: 500;
		position: absolute;
		z-index: 999;
		width: 100%;
		display: none;
	}
	#sucesMsg{
		background: green;
		color: #fff;
		padding: 10px;
		z-index: 999;
		position: absolute;
		width: 100%;
	}
	#sucesMsgContainer{
		padding: 10px;
		background: green;
		color: white;
		font-weight: 500;
		position: absolute;
		z-index: 999;
		width: 100%;
	}
	.preloader_gif{
		background-image:url(<?= base_url('public/assets/home_image/images/status.gif') ?>);
	}
	#messageContainer{
		background-color: red;
		color: #fff;
		padding: 10px;
		position: absolute;
		z-index: 999;
		width: 100%;
		display: none;
	}
	.sub_btn_btn{
		bottom: -9px !important;
	}
	.sub_btn:focus{
		background: #005197 !important;
	}
	.sub_btn:hover{
		background: #005197 !important;
	}
	.new_user_btn{
		font-size: 11px; 
		margin-top: 3px !important;
	}
	.cret_acc_btn{
		font-size: 11px;
		color: #005A87;
		font-weight: 500; 
		margin-top: 3px;
		font-weight: 500
	}
	.login_btn_img{
		width: 100%;
		height: 50px;
		border-radius: 3px;
	}
	.login_btn:hover{
		background: #005197;
	}
	.login_btn:focus{
		background: #005197 !important;
	}
	p {
		margin: 0 0 10px;
		text-align: justify;
	}

	.txt_rgt {
		text-align: right;
	}

	.plus_icon_mgt {
		font-size: 50px !important;
		color: #005197;
		margin-top: 12px;
	}
	.plus_icon_mgt:hover{
		color: #005197 !important;
	}

	.waves-light:focus {
		background: #005197;
		color: #fff !important;
		outline: none !important;
	}
	.fa_icon{
		color: #005197; 
		font-size: 35px;
	}
	.fa_icon1{
		color: #005197; 
		font-size: 19px; 
		font-weight: 500
	}
	.mrg_botm{
		margin-bottom: 0px;
	}
	.mrgin_btm{
		margin-bottom: -3px !important;
	}
	.h6_notes{
		margin-top: 36px;
		font-size: 11px; 
		font-weight: 500;
		color: grey;
	}
	.fa_eye{
		color: red;
		margin-top: 25px;
	}
	.mrgn_botm{
		margin-bottom: 20px !important;
	}
	.h6_notes_doctor{
		margin-top: 20px;
		font-size: 11px; 
		font-weight: 500;
		color: grey;
	}
	.login_para_1{
		font-size: 11px; 
		font-weight: 500;
		color: grey; 
		margin-bottom: 6px !important;
	}
	.login_para_2{
		font-size: 11px; 
		font-weight: 500;
		color: grey;
	}
	#mrg_top{
		margin-top: 6% !important;
	}
	
	#mrg_top1{
		margin-top: 6% !important;
	}
	.log_pge_hedng{
		/* margin-top: 0px;  */
		color: black;
		font-weight: 500; 
		margin: 2px 20px 20px 20px;
	}

	
	.input_font_fam {
		padding-left: 0px !important;
		font-family: serif !important;
	}

	.dropdown-content li>a:focus {
		color: inherit !important;
	}

	.ttle {
		font-weight: 600;
		margin-top: 0px;
		margin-bottom: 0px;
		font-size: 20px;
		font-family: serif;
	}

	.brder {
		border-bottom: 1px solid lightgray;
	}

	.colr {
		color: #005197;
	}

	#input_box {
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
		color: black
	}
	.icon_styl{
		font-size: 50px !important;
		color: #005197;
		display: block;
		margin-top: 25px;
		text-align: center;
	}
	.input_box {
		border: 1px solid silver !important;
		box-shadow: none !important;
		box-sizing: border-box !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		height: 40px !important;
		border-radius: 3px !important;
	}

	#select {
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
		display: block;
	}
	.selct_gendr {
		display: block;
		border: 1px solid silver;
		border-radius: 3px;
		color: black;
		font-size: 16px;
		box-shadow: none;
		height: 40px;
		margin-bottom: 9px;
	}
	.placehldr::placeholder {
		color: #c5b9b9 !important;
	}

	textarea {
		border: 1px solid silver;
		padding: 10px;
		outline: none;
		height: 100px;
		resize: none;
		border-radius: 3px;
	}

	span {
		cursor: pointer;
	}
	.txt_align{
		text-align: center;
	}
	.txt_align_rgt{
		text-align: right;
	}
	.brd_botm{
		border-bottom: 1px dashed silver;
	}
	.h6_sty{
		font-weight: 500;
		font-size: 15px;
	}
	.h6_sty2{
		font-weight: 500;
		font-size: 14px;
		text-align: justify;
	}
	#brdr_botm_silvr{
		border-bottom: 1px solid silver;
		padding: 5px;
	}
	.img_align{
		display: flex;
    	justify-content: end;
	}
	.img_tag{
		height: 55px;
/*		width: 200px;*/
		margin-top: 2px;
	}
	.blu_colr{
		color: #005197;
	}
	.equl_mrgn{
		/*margin-right: 15px;
		margin-left: 15px;*/
		/*margin-top: 80px;*/ /* Commented because: This increase hight below admin navigation bar */
	}
	.equl_padng{
		padding-left: 15px; 
		padding-right: 15px
	}
	.readmore_area a span {
		background: #005197 !important;
	}
	.readmore_area a{
		border: 2px solid #005197 !important;
	}
	.readmore_area a::before{
		color: #005197 !important;
	}
	.plcehldr::placeholder {
		text-align: left;
	}
	.plcehldr{
		border-radius: 5px !important;
		border: 2px solid #D3D3D3 !important;
	}
	.font_weght{
		font-weight: 500;
	}
	.wdth{
		width: 100%;
	}
	.med_icon{
		color: #005197;
		box-shadow: none;
		height: 40px;
		margin-top: 15px;
		font-size: 38px !important;

	}
	#div_pad{
		padding: 5px;
	}
	.div_pad{
		padding: 5px;
	}
	.div_red_back{
		background: red;
		padding: 3px;
	}
	.h6_red_colr{
		color: red;
		font-weight: 500;
		font-size: 16px;
	}
	.h6_produ_nt_fon{
		color: white;
		font-weight: 500;
		margin-left: 20px;
	}
	.hgth_129{
		height: 129px;
	}
	.card_fnt{
		font-weight: bold;
		color: white;
		font-size: 14px;
	}
	.pdng_zero{
		padding: 0px;
	}
	.al_patient{
		color: #fff;
		text-align: center;
		font-weight: 500
	}
	.al_patient1{
		color: red;
		text-align: center;
		font-weight: 500
	}
	.h6_orng_colr{
		color: orange;
		font-weight: 500;
		font-size: 16px;
	}
	.h6_orng_colr1{
		color: orange;
		font-weight: 500;
		font-size: 14px;
	}
	.span_red_colr{
		color: red;
		font-weight: 500;
		font-size: 14px;
	}
	.span_gren_colr{
		color:green;
		font-weight:500;
		font-size:14px;
	}
	.span_sty{
		color: red;
		text-align:center;
		display: block;
	}
	.h5_align{
		font-weight: 600 !important;
		margin-top: 5px; 
		font-size: 20px !important;
		font-family: 'Raleway', sans-serif;
	}
	.h6_gren_colr{
		color: green;
		font-weight: 500;
		font-size: 14px;
	}
	.h6_gray_colr{
		color: grey; 
		font-weight: 600; 
		margin-bottom: 20px;
	}
	.col_gry{
		color: grey;
	}
	.buton_blu{
		background: #005197 !important;
		text-transform: capitalize;
		height: 40px;
		margin-left: 19px;
		font-family: sans-serif;
	}
	.color_hver:hover {
		color: blue;
	}
	.col_lgt_blu{
		color: blue;
		font-weight: 500;
		font-size: 15px;
	}
	.tooltip_widh{
		width: 134px !important;
	}
	#dotted_border {
        border-bottom: 1px dashed silver !important;
    }
	.filter_btn{
		background: #005197 !important;
		box-shadow: none;
		text-transform: capitalize;
		height: 38px;
		margin-top: 18px;
		font-family: sans-serif;
	}
	.readonly_bg {
		font-family: serif;
		color: gray;
		background-color: #f3f3f3 !important;
		cursor: pointer;
	}
	.backgrnd_colr_gray{
		background: #f2f2f2;
		pointer-events: none;
	}
	.sub_btn_blck{
		background: black;
		text-transform: capitalize;
		font-weight: 500
	}
	.col_yelw{
		color: yellow;
	}
	.col_gry{
		color: grey;
	}
	.ovrflw_content{
		overflow-x:auto;
	}
	.col_wite{
		color: #fff;
		text-decoration: none;
	}
	.disply{
		display: contents;
	}
	#cnf_pswrd{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px
	}
	#info_box{
		color: #fff !important; 
		font-size: 14px !important; 
		font-weight: 600;
		border-radius: 15px;
	}
	
	.btn_hver:focus{
		background: #005197 !important;
		color: #fff !important;
		outline: none !important;
	}
	.btn_hver1:focus{
		background: none !important;
		outline: none !important;
	}
	
	.btn_hver:hover {
		color: #fff !important;
	}
	.btn_hver_blu:hover {
		color: blue;
	}
	#doctor_name {
		border: 1px solid silver;
	}
	.info_count{
		margin-top: 0px !important; 
		font-weight: 500 !important;
		color: white !important; 
	}
	.col_gren{
		color: green;
	}
	.stus_msg{
		display: none;
	}
	.mess_stus_msg{
		position: relative;
	}
	.h5_align1{
		font-weight: 600 !important;
		margin-top: 5px;
		font-size: 12px !important;
		font-family: 'Raleway', sans-serif;
	}
	.stus_msg_bill{
		position: relative;
	}
	.chartContainer{
		height: 370px; 
		max-width: 920px; 
		margin: 0px auto;
	}
	.elipses:hover{
		color: #fff;
	}
	.bckgrnd_gren{
		background: green;
	}
	.bckgrnd_red{
		background: red;
	}
	.bckgrnd_orng{
		background: orange;
	}
	.bckgrnd_lg_blu{
		background: #4f81bc;
	}
	
	.padng{
		padding: 10px;
	}
	#suces_msg{
		padding: 10px; 
		background: green; 
		color: white; 
		font-weight: 500
	}
	#eror_msg{
		padding: 10px; 
		background: red; 
		color: white; 
		font-weight: 500
	}
	.canvasjs-chart-credit{
		color: transparent !important;
	}
	.fa-ellipsis-v:hover{
		color: #333 !important;
	}
	.col_red{
		color: red;
	}
	.col_blu{
		color: #005197 !important;
	}
	.starr{
		top: 18% !important;
		position: absolute;
	}
	.margn_tp{
		margin-top: -6px !important;
	}
	.asterisk-symbol1 {
		transform: translateY(40%) !important;
		position: absolute !important;
		top: 0% !important;
	}
	.mrgn_top{
		margin-top: 10px !important;
	}
	.mrgn{
		margin: 1.7rem 0 0.912rem 0 !important;
	}
	p a i {
		font-size: 1.5em;
		margin-right: 5px;
		color: #333;
	}
	.outer_body {
		display: flex;
		justify-content: center;
		align-items: center;
		margin: 0;
	}

	.rating {
		display: inline-block;
		font-size: 0;
	}

	.star2 {
		display: inline-block;
		font-size: 30px;
		color: #ccc;
		cursor: pointer;
		transition: color 0.3s;
		margin-right: 5px;
	}

	.star2:before {
		content: '\2605';
	}
	.star2.active {
		color: #ffa500;
	}
	#review_title{
		border: 1px solid silver;
		text-indent: 10px;
		border-radius: 3px;
	}
	#review_title:focus{
		border-bottom: 1px solid silver !important;
	}
	.input:focus{
		border-bottom: 1px solid silver !important;
	}
		#review_image{
		border: 1px solid silver;
		padding: 6px;
		width: 100%;
		margin-bottom: 15px;
		font-size: 14px;
		font-weight: 500;
		border-radius: 3px;
	}
	.alert-danger{
		background-color: red !important;
		color: #fff !important;
	}
	.alert-success{
		background-color: green !important;
		color: #fff !important;
	}
	.alert{
		padding: 9px !important;
	}
	sub{
		color: red;
		font-size: 20px;
	}
	.asterisk:focus{
		border-bottom: 1px solid silver;
		outline: none !important;
	}
	.alert{
		border-radius: 0px !important;
	}
	
	.mandatry_title {
		text-align: center;
		font-size: 14px;
		padding-bottom: 20px;
	}
	.star{
		color: red;
		font-size: 20px;
	}
	#search_donors li:first-child {
		width: 300px
	}
	#search_donors {
		display: flex;
	}
	.col_ornge{
		color: orange;
	}
	.patient_back{
		background: url(<?= base_url('public/assets/images/pat.jpg') ?>)no-repeat fixed;
		background-size: 100% 100%
	}
	.petnt_backg{
		background: url(<?= base_url('public/assets/images/patients.jpg') ?>)no-repeat fixed;
		background-size: 100% 100%
	}
	.row11{
		display: flex; 
		justify-content: center; 
		align-items: center; 
		height: 15vh;
		margin-bottom: -20px;
	}
	.presc_top_mrgn{
		margin-top: 33px;
	}
	.my-custom-popup {
		position: fixed;
		top: 20%;
		left: 50%;
		transform: translate(-50%, -50%);
		width: 1170px;
		max-width: 100%;
		background-color: #3498db;
		color: #fff;
		text-align: left;
		padding: 8px;
		box-sizing: border-box;
		z-index: 9999;
	}
	.custom-close-button {
		position: absolute;
		top: 10px;
		right: 10px;
		cursor: pointer;
	}
	.upr_header {
		background: #fff !important;
		height: 29px !important;
	}
	.higt{
		height: 4rem !important;
	}
	.header_tbl {
		border: 1px solid #9d9d9d;
		text-indent: 4px;
		width: 50%;
		font-size: 12px;
		height: 25px;
		border-radius: 5px;
		background: #fff;
	}
	.img_icon{
		width: 50px; 
		border-radius: 50%
	}
	.lght_gren{
		background: #139547;
	}
	.close {
		color: #aaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
		cursor: pointer;
	}
	.modal-content button {
		background-color: #005197;
		color: white;
		padding: 10px 20px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		border: 1px solid #005197;
	}

	.modal-content button:hover {
		background-color: #fff;
		color: #005197;
	}

	#myModal .modal-content {
		float: none !important;
	}

	.close:hover,
	.close:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
	}
	#patient_image {
		width: 100px;
		height: 100px;
	}

	h6 {
		font-weight: 600 !important;
		font-size: 13px;
		color: #333;
	}
	@media (max-width: 1366px) {
		.tble_alignment{
			padding: 20px !important;
		}
		.text-container_mange_doc {
			width: 1300px !important;
		}
	}
	.td, th{
		padding: 15px 10px !important;
	}
	.emty_value_align{
		color: #fff;
		margin-top: -6px;
	}
	td,
    th {
      border-radius: 0 !important;
    }
	#clear-input {
		position: absolute;
		top: 44%;
		right: 13px !important;
		transform: translateY(-50%);
		cursor: pointer;
		font-size: 18px;
		font-weight: 600;
	}
	.serch_area{
		padding-right: 34px !important;
	}

	h5 {
		font-weight: 600;
		margin-top: 5px;
		font-size: 20px;
	}
	#login_id_with_image {
		background-size: 100% 100%;
		box-shadow: 3px 2px 8px;
		background: #f9f9f9;
	}
	.admin_login_back {
		background: url(<?= base_url('public/assets/images/hos2.jpg') ?>)no-repeat fixed;
		background-size: 100% 100%
	}
	#email_error{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	/*.reltiv{
		position: relative;
	}*/
	.bld_bnk_backg{
		background: url(<?= base_url('public/assets/images/blied.jpg') ?>)no-repeat fixed;
		background-size: 100% 100%
	}

	.input-container {
		position: relative;
	}
	.h4_mrhn{
		margin: 0 0 0 0 !important;
	}
	.appoint_inner {
		margin-top: 20px;
	}
	.bdy_backgrd{
		background: url(<?= base_url('public/assets/images/back.png') ?>)no-repeat fixed;
		background-size: 100% 100%
	}

	.txt_hver {
		color: #fff;
		font-size: 14px !important;
	}
	.account-settings .user-profile {
		margin: 0 0 1rem 0;
		padding-bottom: 1rem;
		text-align: center;
	}
	.account-settings .about {
		margin: 2rem 0 0 0;
		text-align: center;
	}

	.account-settings .about h5 {
		margin: 0 0 15px 0;
		color: #007ae1;
	}

	.account-settings .about p {
		font-size: 0.825rem;
	}

	.account-settings .user-profile .user-avatar {
		margin: 0 0 1rem 0;
	}

	.account-settings .user-profile .user-avatar img {
		width: 150px !important;
		height: 150px !important;
		-webkit-border-radius: 100px !important;
		-moz-border-radius: 100px !important;
		border-radius: 100px !important;
		border: 4px solid #005197 !important;
	}

	.account-settings .user-profile h5.user-name {
		margin: 0 0 0.5rem 0;
	}

	.account-settings .user-profile h6.user-email {
		margin: 0;
		font-size: 0.8rem;
		font-weight: 400;
		color: #9fa8b9;
	}
	.bdy {
		margin: 0;
		padding-top: 40px;
		color: #2e323c;
		background: #f5f6fa;
		position: relative;
		height: 100%;
	}
	.pres_bdy_back {
		background: url(<?= base_url('public/assets/images/blood5.jpg') ?>)no-repeat fixed !important;
		background-size: 100% 100% !important;
	}
	.selected {
		background-color: #005197;
		color: #fff;
	}
	.break-word {
		overflow-wrap: normal;
		word-wrap: anywhere;
	}
	.depart_widh {
		text-align: justify !important;
		display: flex;
		justify-content: center;
	}
	.text-container {
		width: 400px;
		font-size: 13px !important;
	}
	.custom_img {
		width: 100px;
		height: 100px;
		border: 1px solid silver;
		border-radius: 3px;
		margin-top: 14px;
	}
	.tp_non{
		margin-top: 0px !important;
	}
	.text-container_depart {
		font-size: 13px !important;
	}
	.text-container_appoint {
		width: 602px;
		font-size: 13px !important;
	}
	.input_area {
      border: 1px solid #D3D3D3 !important;
      text-indent: 8px;
      font-family: serif;
    }
	#data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #data-table th {
      background-color: #005197;
      color: #fff;
      padding: 7px;
      text-align: center;
      font-size: 15px;
      border: 1px solid #fff;
    }
    #data-table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    #data-table tbody tr:nth-child(odd) {
      background-color: #fff;
    }
    #data-table td {
      padding: 12px;
      border: 1px solid #ddd;
    }
	#blood_unit{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	#blood_price{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	#total_blood_price{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
		background-color: #e9e9e9 !important;
	}
    #data-table tbody tr:hover {
      background-color: #dcdcdc;
    }
	@media (max-width: 768px) {
		.smll_scren_mrgn{
			margin: 0 0 15px 0 !important;
		}
		.table-container {
			overflow-x: auto;
		}
		.plus_icon_mgt {
			font-size: 38px !important;
			margin-top: 15px;
		}

		.text-container_mange_doc {
			white-space: normal;
			word-wrap: break-word !important;
		}
			.fnt_size {
			font-size: 13px;
		}

		.text-container_depart {
			white-space: normal;
			overflow-wrap: break-word !important;
			word-break: break-all !important;

		}

		.table {
			width: 100%;
			display: block;
			white-space: nowrap;
		}

		.table th,
		.table td {
			min-width: 150px;
			padding: 8px;
		}

		.scroll-container {
			overflow-x: auto;
			white-space: nowrap;
		}

		.txt_break-at300 {
			width: 150px !important;
		}
	}
	.row_mrgn_tp {
		margin-top: 25px;
	}
	#customMessageContainer {
		padding: 10px;
		position: absolute;
		z-index: 999;
		width: 100%;
		display: none;
	}
	#customMessageContainer.success {
		background: green;
		color: white;
		font-weight: 500;
	}
	#customMessageContainer.error {
		background: red;
		color: white;
		font-weight: 500;
	}
	@media (max-width: 1115px) {
		td,
		th {
			font-size: 14px !important;
		}
		.fnt_size {
			font-size: 11px;
		}
	}
	.text-container_mange_doc {
		font-size: 13px !important;
		width: 1320px !important;
	}
	.pdng{
		padding:12px
	}
	@media(max-width:767px ){
		.date_div{
			margin-bottom: 7px;
		}
	}
	.dt_custm{
		height: 24px !important;
		font-size: 13px !important; 
		border: 1px solid silver !important;
		text-indent: 6px !important;
		border-radius: 3px !important;
	}
	.date_div{
		margin-top: 6px;
	}
	.date_div_container{
		border: 1px solid silver; 
		margin-top: 14px;
		border-radius: 3px;
	}
	.h6_record {
		position: relative;
		top: 7px;
		left: 50%;
		transform: translate(-50%, -50%);
	}
	@media only screen and (min-width: 993px) {
		.container {
			width: 90% !important;
		}
	}
	.text-container_tody_appoint {
		width: 150px;
		font-size: 13px !important;
	}
	/*.appoint {
		background-color: #f9f9f9;
		margin-top: 40px;
	}*/
	.text-container_mange_patient {
		width: 150px;
		font-size: 13px !important;
	}
	.h5_inside {
		color: red;
		font-weight: 600
	}

	.h5_heding {
		font-family: serif;
		font-weight: 600 !important;
		font-size: 18px !important;
	}
	.for_got_align{
		font-weight: 500;
		color: black;
		text-align: center;
		margin-top: 25px;
	}
	.all_patent_hding{
		color: black;
		text-align: center;
		font-weight: 500;
	}
	.para_styl{
		font-size: 10px;
		text-align: center;
		color: gray;
	}
	.fnt_sze{
		font-size: 20px;
	}
	.para_sty2{
		margin-top: 5px !important;
		font-size: 13px;
		text-align: center;
		color: gray;
	}
	p a {
		font-size: 0.8em;
		text-decoration: none;
		align-items: center;
		justify-content: center;
		color: gray;
	}
	.bck_to_logn{
		text-decoration: none;
		display: flex;
		align-items: center;
		justify-content: center;
		color: gray;
	}
	.flex{
		display: flex;
	}
	
	.asterisk-symbol {
		position: absolute;
		top: unset !important;
		transform: unset !important;
		margin-top: 5px;
		left: 6px !important;
		color: red;
		visibility: visible;
		opacity: 1;
		transition: visibility 0s, opacity 0.2s;
	}


	.asterisk-symbol2{
		position: absolute;
		left: 12px !important;
	}
	#status {
		width: 200px;
		height: 200px;
		position: absolute !important;
		left: 50%;
		top: 50%;
		background-image: url("<?= base_url('public/assets/home_image/images/status.gif') ?>");
		background-repeat: no-repeat;
		background-position: center;
		margin: -216px 0 0 -100px !important;
		z-index: 999 !important;
	}
	.mandatory {
		position: absolute;
		left: 10px;
		top: 40%;
		color: red;
		transform: translateY(-50%);
	}
	.asterisk-symbol-fogot{
		position: absolute;
		top: 30%;
		left: 6px;
		transform: translateY(-50%);
		color: red;
		visibility: visible;
		opacity: 1;
		transition: visibility 0s, opacity 0.2s;
	}
	@media (max-width: 768px) {
		.scroll-container {
			overflow-x: auto;
			white-space: nowrap;
		}
	}
	.colour_hver:focus {
		color: gray !important;
	}
	.pagination li.active a {
		color: white !important;
		background: #005197 !important;
		border: 1px solid #005197 !important;
	}
	.pagination li.active {
		background: none !important;
	}
	.pagination a {
		color: black;
		font-weight: 500;
		border: 1px solid black;
		padding: 2px 5px;
		margin-left: 2px;
		border-radius: 3px;
	}
	.bck_blu{
		background: #005197;
	}
	.fnt_sze_24{
		font-size: 24px !important;
	}
	.mrgn_tp_bt_13{
		margin-bottom: 13px; 
		margin-top: 13px;
	}
	.pagination li a {
		color: #005197 !important;
		border: 1px solid #005197 !important;
	}
	.col_blck{
		color: #333 !important;
	}
	.pagination li a:focus {
		color: black;
	}
	.asterisk-hidden .asterisk-symbol {
		visibility: hidden;
		opacity: 0;
	}
	.btn-flat:focus {
		background: #fff !important;
	}
	.col_blck{
		color: black;
	}
	.btn-floating:hover {
		background: none;
		color: black;
	}
	.btn-floating:focus {
		color: #333 !important;
	}
	.event {
		pointer-events: none !important;
	}
	.txt_placehldr::placeholder{
		color: lightgray !important;
		font-family: sans-serif !important;
	}

	input::placeholder {
		font-size: 14px !important;
	}
	.plachldr_algn::placeholder{
		font-family: inherit;
		text-align: left;
		text-indent: 5px;
	}
	#clear-input {
		position: absolute;
		top: 44%;
		right: 10px;
		transform: translateY(-50%);
		cursor: pointer;
	}
	.success {
		background-color: #2ecc71;
	}
	.disp_none{
		display: none;
	}
	.error {
		background-color: #e74c3c;
	}
	#customsucesMsg{
		padding: 10px; 
		background: green;
		color: white;
		font-weight: 500; 
		display: flex;
		position: absolute;
		z-index: 999;
		width: 100%;
	}
	.iti {
		width: 100%;
		margin-bottom: 0px !important;
	}
	#search_doctor {
		display: flex;
	}
	#appointment_fee{
		text-align: right;
		border:1px solid silver;
		border-radius: 3px;
	}
	#doctor_fee{
		text-align: right;
		border:1px solid silver;
		text-indent: 6px;
	}
	.doctor_fee{
		text-align: left !important;
		border:1px solid silver;
		text-indent: 0px !important;
	}
	#total_payable{
		text-align: right;
		border:1px solid silver;
		text-indent: 6px;
	}
	#search_doctor_fee {
		display: flex;
	}
	#search_doctor li:first-child {
		width: 250px
	}
	#search_doctor_fee li:first-child {
		width: 250px
	}
	#doctor_filter {
		width: 180px !important;
		padding-top: 8px;
		padding-bottom: 8px;
	}
	#doctor_filter li a {
		color: grey;
		font-size: 14px;
		font-weight: 500;
	}
	#profile_pic {
		/*width: 40px;
		height: 40px;*/
		border-radius: 100%;
		border: 1px solid silver
	}
	
	.profile_pic-1{
		width: 150px !important;
		height: 150px !important;
		-webkit-border-radius: 100px !important;
		-moz-border-radius: 100px !important;
		border-radius: 100px !important;
		border: 4px solid #005197 !important;
	}
	td {
		font-size: 15px;
		font-weight: 500
	}
	table tr td {
		font-weight: 500;
		font-size: 15px;
	}
	.tooltip {
		position: relative;
		display: inline-block;
	}
	.tooltip1 .tooltiptext1 {
		width: 90px !important;
		margin-left: -45px !important;
	}
	.hidden {
		display: none;
	}
	.tooltip .tooltiptext {
		visibility: hidden;
		width: 140px;
		background-color: #005197;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;
		position: absolute;
		z-index: 1;
		top: 100%;
		left: 50%;
		margin-left: -70px;
		opacity: 0;
		transition: opacity 1s;
	}
	.tooltip:hover .tooltiptext {
		visibility: visible;
		opacity: 1;
	}
	#income_dropdown,
	#medical_income,
	#patients_dropdown {
		width: 200px !important;
		padding-top: 8px;
		padding-bottom: 8px;
	}
	#income_dropdown a,
	#medical_income a,
	#patients_dropdown a {
		color: grey;
		font-size: 16px;
		font-weight: 500
	}
	tr td {
		font-weight: 500;
		font-size: 14px;
	}
	.dsh_crd_red{
		font-weight: 500;
		font-size: 15px;
		color: white;
	}
	.colour_hver:hover {
		color: gray;
	}
	.valid_err {
		position: absolute;
		top: 81%;
		left: 0%;
		font-size: 12px;
	}
	.valid_err2 {
		position: absolute;
		top: 81%;
		left: 3%;
		font-size: 12px;
	}
	.valid_err3 {
		position: absolute;
		/* top: 81%; */
		left: 0%;
		font-size: 12px;
		margin-top: 42px;
	}
	.valid_err1 {
		position: absolute;
		/* top: 81%; */
		left: 0%;
		font-size: 12px;
		margin-top: 38px;
	}
	.txt_area_valid_err {
		position: absolute;
		top: 93%;
		left: 0%;
		font-size: 12px;
	}
	.txt_area_valid_err1 {
		position: absolute;
		top: 95%;
		left: 1.5%;
		font-size: 12px;
	}
	.chng_pss_valid{
		position: absolute;
		top: 89%;
		left: 1%;
		font-size: 12px;
	}
	.chng_pss_valid1{
		position: absolute;
		top: 87%;
		left: 3%;
		font-size: 12px;
	}
	.valid_err_phn {
		position: absolute;
		top: 97%;
		left: 0%;
		font-size: 12px;
	}
	.valid_err_upl {
		position: absolute;
		top: 70%;
		left: 0%;
		font-size: 12px;
	}
	select{
		/*margin: 0 0 8px 0 !important;*/
		margin: 0 0 0px 0 !important;
	}
	.link_hver:hover {
		color: blue !important;
	}
	.link_hver:focus {
		color: blue !important;
	}
	.link_hver{
		font-size: 14px !important;
	}
	.down {
		min-width: 200px !important;
		left: 615.094px !important;
	}
	.action_dropdown {
		width: 167px !important;
	}
	#med_acc_name{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	#med_acc_email{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	#password{
		border: 1px solid silver;
		box-shadow: none;
		box-sizing: border-box;
		padding-left: 10px;
		padding-right: 10px;
		height: 40px;
		border-radius: 3px;
	}
	.password-field {
		padding-right: 30px;
	}
	.toggle-password {
		display: flex;
		padding-right: 10px;
		padding-top: 38px;
		right: 5px;
		transform: translateY(-50%);
		cursor: pointer;
		align-items: center;
		position: absolute;
		height: 100%;
		top: 0;
		right: 0;
		cursor: pointer;
	}
	.image {
		width: 100%;
		position: relative;
	}
</style>