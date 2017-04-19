$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('._ridden-btn').on('click', function() {
    var btn = $(this);
    submitRide(btn);
});
function riddenBtn(event) {
    submitRide($(event));
}
function toggleRiddenBtns(btn) {
    if(btn.attr('has-ridden') === 'false') {
        btn.find('i').removeClass('fa-square-o').addClass('fa-check-square-o');
        btn.addClass('btn-success').removeClass('btn-outline-success');
        btn.attr('has-ridden', 'true');
    } else {
        btn.find('i').addClass('fa-square-o').removeClass('fa-check-square-o');
        btn.removeClass('btn-success').addClass('btn-outline-success');
        btn.attr('has-ridden', 'false');
    }
}
function submitRide(btn) {
    var ridden = btn.attr('has-ridden');

    // Update UI, we're doing it early so it feels faster
    toggleRiddenBtns(btn);

    $.post({
        url: "/track/ridden",
        data: {
            ridden: ridden,
            coaster: btn.data('id')
        },
        success: function(res) {
            toastr.success(res.message);
        },
        error: function(res) {
            toastr.error(res.statusText);

            // Whoops, gotta change it back. Made a mistake.
            toggleRiddenBtns(btn);
        }
    });
}