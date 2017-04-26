<script src="https://www.google.com/recaptcha/api.js" async></script>
<script>
    function forceSubmit() {
        $('#message').focus();
    }
    $(document).on('ready', function() {
        window.setTimeout(function() {
            grecaptcha.execute();
        }, 500);
    });
</script>