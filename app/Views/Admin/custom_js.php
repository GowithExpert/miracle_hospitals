<script type="text/javascript">
    count_medical_income();

    function count_medical_income(type = "all") {
        if (type == 'all') {
            $('#show_medical_heading').text('All Incomes');
        } else if (type == 'today') {
            $('#show_medical_heading').text('Today Incomes');
        } else if (type == 'yesterday') {
            $('#show_medical_heading').text('Yesterday Incomes');
        } else if (type == 'last_30_days') {
            $('#show_medical_heading').text('Last 30days Incomes');
        } else {
            $('#show_medical_heading').text('All Incomes');
        }
        $.ajax({
            type: 'ajax',
            method: 'GET',
            url: '<?= site_url('Admin/count_medical_income/'); ?>' + type,
            beforeSend: function(data) {
                $('#show_medical_income').text('Loading..');
            },
            success: function(data) {
                $('#show_medical_income').html(data);
            },
            error: function() {
                $('#show_medical_income').text('0');
            }
        });
    }




    count_patients();

    function count_patients(type = "all") {
        if (type == 'all') {
            $('#show_patient_heading').text('All Patients');
        } else if (type == 'today') {
            $('#show_patient_heading').text('Today Patients');
        } else if (type == 'yesterday') {
            $('#show_patient_heading').text('Yesterday Patients');
        } else if (type == 'last_30_days') {
            $('#show_patient_heading').text('Last 30days Patients');
        } else {
            $('#show_patient_heading').text('All Patients');
        }
        $.ajax({
            type: 'ajax',
            method: 'GET',
            url: '<?= site_url('Admin/count_patients/'); ?>' + type,
            beforeSend: function(data) {
                $('#show_patient').text('Loading..');
            },
            success: function(data) {
                $('#show_patient').html(data);
            },
            error: function() {
                $('#show_patient').text('0');
            }
        });
    }

    //Count Income 
    count_income();

    function count_income(type = "all") {
        if (type == 'all') {
            $('#show_income_heading').text('All Income');
        } else if (type == 'today') {
            $('#show_income_heading').text('Today Income');
        } else if (type == 'yesterday') {
            $('#show_income_heading').text('Yesterday Income');
        } else if (type == 'last_30_days') {
            $('#show_income_heading').text('Last 30days Income');
        } else {
            $('#show_income_heading').text('All Income');
        }
        $.ajax({
            type: 'ajax',
            method: 'GET',
            url: '<?= site_url('Admin/count_income/'); ?>' + type,
            beforeSend: function(data) {
                $('#show_income').text('Loading..');
            },
            success: function(data) {
                $('#show_income').html(data);
            },
            error: function() {
                $('#show_income').text('0');
            }
        });
    }
    //Count Income 


    //Chart Dashboard
    window.onload = function() {

        var options = {
            animationEnabled: true,
            title: {
                text: "Hospital Income & Patients Status"
            },
            data: [{
                type: "doughnut",
                innerRadius: "40%",
                showInLegend: true,
                legendText: "{label}",
                indexLabel: "{label}:",
                dataPoints: [{
                        label: 'Total Medical Earning',
                        y: <?= $chart_data['ch_medical_earning']; ?>
                    },
                    {
                        label: 'Total Patient Earning',
                        y: <?= $chart_data['ch_patient_earning']; ?>
                    },
                    {
                        label: 'Total Patient Visit',
                        y: <?= $chart_data['total_patients']; ?>
                    },
                    {
                        label: 'Total Appointment',
                        y: <?= $chart_data['total_appointment']; ?>
                    }

                ]
            }]
        };
        $("#chartContainer").CanvasJSChart(options);

    }
    //Chart Dashboard        
</script>