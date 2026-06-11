<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    #yourPreloaderID {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.blurry-background {
    filter: blur(1px);
}

.loading-container {
    position: relative;
    width: 100px;
    height: 100px;
}

.loading-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    color: #005197;
}

.loading-circle {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 8px solid #3498db;
    border-top: 8px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
</head>
<body>
    <div id="yourPreloaderID">
        <div class="loading-container">
            <div class="loading-text">Loading...</div>
            <div class="loading-circle"></div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Show the preloader initially and add the blurry background styles
        var preloader = $("#yourPreloaderID");
        preloader.show();
        $("upr_div").addClass("blurry-background");

        // Hide the preloader and remove the blurry background styles after the page has fully loaded
        $(window).on("load", function() {
            preloader.hide();
            $("upr_div").removeClass("blurry-background");
        });
    });
</script>
</body>
</html>