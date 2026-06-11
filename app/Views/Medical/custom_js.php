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
<script type="text/javascript">
    count_customers();

    function count_customers(type = "all") {
        if (type == 'all') {
            $('#show_customer_heading').text('All Customers');
        } else if (type == 'today') {
            $('#show_customer_heading').text('Today Customers');
        } else if (type == 'yesterday') {
            $('#show_customer_heading').text('Yesterday Customers');
        } else if (type == 'last_30_days') {
            $('#show_customer_heading').text('Last 30days Customers');
        } else {
            $('#show_customer_heading').text('All Customers');
        }
        $.ajax({
            type: 'ajax',
            method: 'GET',
            url: '<?= site_url('Medical_Accountant/count_customers/'); ?>' + type,
            beforeSend: function(data) {
                $('#show_customers').text('Loading..');
            },
            success: function(data) {
                $('#show_customers').html(data);
            },
            error: function() {
                $('#show_customers').text('0');
            }
        });
    }


    count_income();

    function count_income(type = "all") {
        if (type == 'all') {
            $('#show_income_heading').text('All Incomes');
        } else if (type == 'today') {
            $('#show_income_heading').text('Today Incomes');
        } else if (type == 'yesterday') {
            $('#show_income_heading').text('Yesterday Incomes');
        } else if (type == 'last_30_days') {
            $('#show_income_heading').text('Last 30days Incomes');
        } else {
            $('#show_income_heading').text('All Incomes');
        }
        $.ajax({
            type: 'ajax',
            method: 'GET',
            url: '<?= site_url('Medical_Accountant/count_income/'); ?>' + type,
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


    //Dashboard 

    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            theme: "dark2", // "light2", "dark1", "dark2"
            animationEnabled: true, // change to true
            exportEnabled: true,
            title: {
                text: "Last 7 days Customers"
            },
            data: [{
                // Change type to "bar", "area", "spline", "pie",etc.
                type: "column",

                dataPoints: [{
                        label: 'Today Customer',
                        y: <?= $chart_data['ch_today_order']; ?>
                    },
                    {
                        label: 'Yesterday Customer',
                        y: <?= $chart_data['ch_yesterday_order']; ?>
                    },
                    {
                        label: '3rd Days Customer',
                        y: <?= $chart_data['ch_last_3_days_order']; ?>
                    },
                    {
                        label: '4rd Days Customer',
                        y: <?= $chart_data['ch_last_4_days_order']; ?>
                    },
                    {
                        label: '5rd Days Customer',
                        y: <?= $chart_data['ch_last_5_days_order']; ?>
                    },
                    {
                        label: '6rd Days Customer',
                        y: <?= $chart_data['ch_last_6_days_order']; ?>
                    },
                    {
                        label: '7rd Days Customer',
                        y: <?= $chart_data['ch_last_7_days_order']; ?>
                    },

                ]
            }]
        });
        chart.render();

    }
    //Dashboard        
</script>