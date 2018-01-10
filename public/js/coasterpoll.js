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
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
}
$('.thumb-up').on('click', function() {
    var btn = $(this);
    vote(btn);
});
$('.thumb-down').on('click', function() {
    var btn = $(this);
    vote(btn);
});
function vote(btn) {
    var thing = btn.data('thing');
    var direction = btn.data('direction');
    $.post({
        url: "/links/vote",
        data: {
            thing: thing,
            direction: direction
        },
        success: function(resp) {
            if(resp.vote.direction > 0) {
                btn.closest('.thumb-group').find('.fa-thumbs-up').addClass('text-success');
                btn.closest('.thumb-group').find('.fa-thumbs-down').removeClass('text-success');
            } else if(resp.vote.direction < 0) {
                btn.closest('.thumb-group').find('.fa-thumbs-up').removeClass('text-success');
                btn.closest('.thumb-group').find('.fa-thumbs-down').addClass('text-success');
            } else {
                btn.closest('.thumb-group').find('.fa-thumbs-up').removeClass('text-success');
                btn.closest('.thumb-group').find('.fa-thumbs-down').removeClass('text-success');
            }

            btn.closest('.thumb-group').find('.thumb-score').text(resp.score);
        },
        error: function(resp) {
            if(resp.status === 401) {
                bootbox.alert("You'll need to sign in first.");
            }
        }
    })
}
$('.report-link-btn').on('mousedown', function() {
    var btn = $(this);
    window.getreportstimer = setTimeout(function() {
        getReports(btn);
    }, 1500);
}).on('mouseup', function() {
    clearTimeout(window.getreportstimer);
    var btn = $(this);

    if(event.altKey) {
        getReports(btn);
        return;
    }

    bootbox.prompt({
        title: "What's wrong with this?",
        callback: function(result) {
            if(result === null) {
                return;
            }
            var link = btn.data('link');
            var comment = btn.data('comment');
            if (link === undefined) {
                link = "";
            }
            if (comment === undefined) {
                comment = "";
            }
            $.post({
                url: "/links/report",
                data: {
                    link: link,
                    reason: result,
                    comment: comment
                },
                success: function (resp) {
                    toastr.success(resp.message);
                },
                error: function (resp) {
                    if (resp.status === 422) {
                        toastr.error(resp.responseJSON.reason[0]);
                    }

                    toastr.error(resp.statusText);
                }
            })
        },
        buttons: {
            confirm: {
                label: "Report",
                className: "btn-primary"
            },
            cancel: {
                label: "Nevermind!",
                className: "btn-secondary"
            }
        }
    })
});

function getReports(btn) {
    $.get({
        url: "/links/reports",
        data: {
            link: btn.data('link'),
            comment: btn.data('comment')
        },
        success: function(resp) {
            var reports = "";
            $.each(resp, function(thing) {
                reports = reports + "<li>" + resp[thing].reason + "</li>";
            });

            var bb = bootbox.dialog({
                message: "<ul>" + reports + "</ul>",
                buttons: {
                    cancel: {
                        label: "Done",
                        className: "btn-secondary",
                        callback: function() {
                            reports = "";
                            bb.hide();
                        }
                    }
                },
                onEscape: true,
                backdrop: true
            })
        },
        error: function(resp) {
            if(resp.status === 403) {
                console.log("You don't really need that.");
            }
        }
    });
}