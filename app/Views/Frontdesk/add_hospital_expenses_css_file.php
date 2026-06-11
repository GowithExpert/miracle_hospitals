<style>
   

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    /* Style the form input fields and labels */
    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    .input-group input[type="text"],
    .input-group input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    /* Style the "Save" button */
    .button-group {
      text-align: center;
      margin-top: 40px;
    }

    button {
      padding: 10px 20px;
      background-color: #005197;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    button:hover {
      background-color: #005197 !important;
      color: #fff;
    }

    /* Style the table container */
    .table-container {
      margin-top: 30px;
    }

    /* Style the table */
    #data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Style table headers */
    #data-table th {
      background-color: #005197;
      color: #fff;
      padding: 7px;
      text-align: center;
      font-size: 15px;
      border: 1px solid #fff;
    }

    /* Style alternating table rows */
    #data-table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    #data-table tbody tr:nth-child(odd) {
      background-color: #fff;
    }

    /* Style table cells */
    #data-table td {
      padding: 12px;
      border: 1px solid #ddd;
    }

    /* Hover effect on table rows */
    #data-table tbody tr:hover {
      background-color: #dcdcdc;
    }

    .input_area {
      border: 1px solid #D3D3D3 !important;
      /* border-radius: 5px !important; */
      text-indent: 8px;
      font-family: serif;
    }

    @media (max-width: 768px) {
      .scroll-container {
        overflow-x: auto;
        white-space: nowrap;
      }

      .fnt_size {
        font-size: 13px;
      }
    }

    @media (max-width: 1115px) {
      .fnt_size {
        font-size: 11px;
      }
    }

    .row {
      margin-top: 25px;
    }

    .col-lg-2,
    .col-md-2,
    .col-sm-12 {
      padding-right: 0px !important;
      padding-left: 6px !important;
    }

    .txt_algn {
      text-align: right;
    }

    ::placeholder {
      text-align: left !important;
    }

    input:focus {
      box-shadow: none !important;
    }

    td,
    th {
      border-radius: 0 !important;
    }

    button:focus {
      background: #005197 !important;
    }

    .fnt_size {
      display: flex;
      justify-content: center;
    }

    .asterisk-symbol {
      position: absolute;
      top: 55%;
      left: 8px;
      transform: translateY(-50%);
      color: red;
      visibility: visible;
      opacity: 1;
      transition: visibility 0s, opacity 0.2s;
      font-size: 17px;
    }

    .asterisk-hidden .asterisk-symbol {
      visibility: hidden;
      opacity: 0;
    }

    .validation_error_msg {
      font-size: 11px;
      /* Adjust the font size as needed */
      color: red;
      position: absolute;
    }

    input {
      box-sizing: border-box !important;
      padding-right: 5px !important;
    }

    .date_inpt {
      text-indent: 3px !important;
    }
  </style>