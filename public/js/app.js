$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).on('ready', function() {
    var _timer = window.setTimeout(updatePageViews, 4000);
});
function updatePageViews() {
    var perfData = window.performance.timing;
    var pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
    $.post({
        url: "/analytics/view",
        data: {
            page: window.location.pathname,
            time: pageLoadTime,
            query: window.location.search,
            hash: window.location.hash,
            referrer: document.referrer
        }
    })
}
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
$('.confirm-form').on('click', function(e) {
    e.preventDefault();
    var btn = $(this);
    bootbox.confirm({
        message: "Are you sure?",
        buttons: {
            confirm: {
                label: "Do it.",
                className: "btn-primary"
            },
            cancel: {
                label: "Nevermind!",
                className: "btn-secondary"
            }
        },
        callback: function(result) {
            if(result) {
                btn.off('click').click();
            }
        }
    })
});
$('.fade-on-collapse').on('click', function() {
    $('.nav-fadable').toggleClass('nav-transparent');
});
$('#notifications-dropdown').on('shown.bs.dropdown', function() {
    $('.nav-fadable').removeClass('nav-transparent');
}).on('hidden.bs.dropdown', function() {
    $('.nav-fadable').addClass('nav-transparent');
});
$('.notification').on('mouseenter', function() {
    var hover = $(this);
    var dropdown = $('#notifications-dropdown');
    if(hover.data('notification') !== "") {
        window._notification_count = (dropdown.data('count') - 1.0);
        dropdown.data('count', _notification_count);
        window.setTimeout(markNotificationRead(hover), 2000);
    }
});
function markNotificationRead(item) {
    $.post({
        url: "/user/notifications/mark",
        data: {
            id: item.data('notification')
        },
        success: function() {
            item.find('.unread-dot').hide();
            item.data('notification', "");

            if(_notification_count == 0) {
                $('#notifications-icon').addClass('fa-bell-o').removeClass('fa-bell text-warning');
                $('#notification-badge').hide();
            }
        }
    })
}
function getCookie(key) {
    var cookies = document.cookie.split('; ');
    for (var i = 0, parts; (parts = cookies[i] && cookies[i].split('=')); i++) {
        if (decode(parts.shift()) === key) {
            return decode(parts.join('='));
        }
    }
    return null;
}
function decode(s) {
    return decodeURIComponent(s.replace(/\+/g, ' '));
}