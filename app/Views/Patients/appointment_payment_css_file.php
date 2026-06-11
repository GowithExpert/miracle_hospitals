<style>

.appoint_bdy {
    background: #f5eee7;
    font-family: "Poppins", sans-serif;
    font-weight: 300;
}
.card{
    line-height: 2.36 !important;
}

.container {
    height: 100vh;
}

.card {
    border: none;
}

.card-header {
    padding: .5rem 1rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, .03);
    border-bottom: none;
}

.btn-light:focus {
    color: #212529;
    background-color: #e2e6ea;
    border-color: #dae0e5;
    box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, .5);
}

.form-control {
    height: 50px;
    border: 2px solid #eee;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #039be5;
    outline: 0;
    box-shadow: none;
}

.input {
    position: relative;
}

.input i {
    position: absolute;
    top: 16px;
    left: 11px;
    color: #989898;
}

.input input {
    text-indent: 25px;
}

.card-text {
    font-size: 13px;
    margin-left: 6px;
}

.certificate-text {
    font-size: 12px;
}

.billing {
    font-size: 11px;
}  

.super-price {
    top: 0px;
    font-size: 15px;
}

.super-month {
    font-size: 11px;
}

.line {
    color: #bfbdbd;
}

.free-button {
    background: #1565c0;
    height: 52px;
    font-size: 15px;
    border-radius: 8px;
}

.payment-card-body {
    flex: 1 1 auto;
    padding: 24px 1rem !important;
}
.txt_dec{
    font-weight: 600;
    font-size: 20px;
    text-align: center !important;
}
.align-items-center{
    font-size: 15px;
}
.d-flex{
    font-size: 15px;
}
.small_txt{
    font-size: 11px;
}
.btn-light:hover {
    color: #212529;
    background-color: lightgray;
    border-color: #DAE0E5;
}
.selected-option {
    background-color: rgb(224, 227, 231);
    color: #212529; /* Add this line to improve text visibility */
    border-color: #DAE0E5;
}
.brd_btm{
    border-bottom: 1px solid lightgray !important;
}
.asterisk-symbol {
		position: absolute;
		top: 36%;
		left: 6px;
		transform: translateY(-50%);
		color: red;
		visibility: visible;
		opacity: 1;
		transition: visibility 0s, opacity 0.2s;
	}
    .asterisk-hidden .asterisk-symbol {
		visibility: hidden;
		opacity: 0;
	}
    .back_act{
        /* position: absolute; */
    }
    #backButton{
        border: none;
        font-size: 28px;
        background: none !important;
        position: absolute;
        margin-top: -6px;
    }
    #backButton:focus{
        border: none !important;
        outline: none !important;
        background: none !important;
    }
    @media (max-width: 767px) {
        .summry_div{
            margin-top: 20px !important;
        }
        .img_posit{
        position: absolute;
        left: 80% !important;
    }
        .img_posit_1{
            position: absolute;
            left: 50% !important;
        }
    }
    .summry_div{
        margin-top: 62px !important;
    }
    .brd_botm{
        border-bottom: 1px solid lightgray !important;
    }
    .img_posit{
        position: absolute;
        left: 85%;
    }
    .img_posit_1{
        position: absolute;
        left: 66%;
    }
    .marg_tp{
        margin-top: 62px !important;
    }
    .msg_conten{
        display: none; 
        background-color: red; 
        padding: 10px; 
        margin-top: 10px;
        position: absolute;
        z-index: 999;
        color: #fff;
    }
    *::-webkit-scrollbar {
        width: 0.6em;
    }
    *::-webkit-scrollbar-thumb {
        background-color: #005197;
    }
</style>