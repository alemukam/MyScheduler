$(document).ready(function() {
    // 0. onload - make "requests in approval" section not visible
    $('#div_pendingMem').hide();
    $('#div_pendingGr, #div_rejectedGr').hide();
    var fadeOutSpeed = 100;

    // 1. Moderator buttons
    // 1.1. Display published groups
    $('#btn_activeGr').click(function() {
        if (!$('#div_activeGr').is(':visible')) {

            // hide pending and rejected group requests
            $('#div_pendingGr, #div_rejectedGr').fadeOut(fadeOutSpeed, function() {
                $('#btn_pendingGr, #btn_rejectedGr').removeClass('active');

                $('#div_activeGr').show();
                $('#btn_activeGr').addClass('active');
            });
        }
    });

    // 1.2. Display pending group requests
    $('#btn_pendingGr').click(function() {
        if (!$('#div_pendingGr').is(':visible')) {

            // hide approved groups and rejected group requests
            $('#div_activeGr, #div_rejectedGr').fadeOut(fadeOutSpeed, function() {
                $('#btn_activeGr, #btn_rejectedGr').removeClass('active');

                $('#div_pendingGr').show();
                $('#btn_pendingGr').addClass('active');
            });
        }
    });

    // 1.3. Display rejected requests
    $('#btn_rejectedGr').click(function() {
        if (!$('#div_rejectedGr').is(':visible')) {

            // hide approved groups and pending group requests
            $('#div_activeGr, #div_pendingGr').fadeOut(fadeOutSpeed, function() {
                $('#btn_activeGr, #btn_pendingGr').removeClass('active');

                $('#div_rejectedGr').show();
                $('#btn_rejectedGr').addClass('active');
            });
        }
    });



    // 2. Membership buttons
    // 2.1. Display acvite group membership
    $('#btn_activeMem').click(function() {
        if (!$('#div_activeMem').is(':visible')) {

            // hide the pending membership requests
            $('#div_pendingMem').fadeOut(fadeOutSpeed, function() {
                $('#btn_pendingMem').removeClass('active'); // section "requests in approval" becomes not active

                $('#div_activeMem').show(); // show the active membership
                $('#btn_activeMem').addClass('active'); // make the active membership section active
            });
        }
    });

    // 2.2. Display requests in the approval stage
    $('#btn_pendingMem').click(function() {
        if (!$('#div_pendingMem').is(':visible')) {

            // hide the active membership section active
            $('#div_activeMem').fadeOut(fadeOutSpeed, function() {
                $('#btn_activeMem').removeClass('active'); // section "active membership" becomes not active

                $('#div_pendingMem').fadeIn(); // show the pending membership requests
                $('#btn_pendingMem').addClass('active'); // make the "requests in approval" section active
            });
        }
    });
});