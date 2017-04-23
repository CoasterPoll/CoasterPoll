@extends('layouts.app')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Your Notifications</h2>
                </div>
                <div class="card-block" id="notifications-card" data-count="{{ $notifications->count() }}">
                    @if($notifications->count() > 0)
                        <table class="table table-sm">
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr @if($notification->read_at == null)class="table-info"@endif>
                                        <th>
                                            <a href="{{ $notification->data['link'] }}">
                                                @if($notification->read_at == null)<i class="fa fa-circle-o text-info unread-dot"></i> @endif
                                                {{ $notification->data['title'] }}
                                            </a>
                                        <td>{{ $notification->data['body'] }}</td>
                                        <td>
                                            @if($notification->read_at !== null)
                                                <button type="button" class="btn btn-outline-danger delete-btn btn-sm" data-id="{{ $notification->id }}"><i class="fa fa-trash-o"></i></button>
                                            @else
                                                <a href="{{ $notification->data['link'] }}" class="btn btn-outline-info btn-sm notification-link" data-notification="{{ $notification->id }}"><i class="fa fa-link"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3 class="lead text-center">You're all caught up!</h3>
                        <p class="text-center">If you get any notifications, they'll end up here.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.delete-btn').on('click', function() {
            var btn = $(this);
            var id = btn.data('id');
            $.post({
                url: "{{ route('notifications.delete') }}",
                method: "DELETE",
                data: {
                    id: id
                },
                success: function(res) {
                    toastr.success(res.message);
                    btn.closest('tr').fadeOut('fast');
                    var card = $('#notifications-card');
                    card.data('count', (card.data('count') - 1));
                    noMoreNotifications();
                },
                error: function() {
                    toastr.error("Could not find that message.");
                }
            });
        });
        $('.notification-link').on('mouseup', function() {
            var item = $(this);
            $.post({
                url: "/user/notifications/mark",
                data: {
                    id: item.data('notification')
                }
            });
        });
        function noMoreNotifications() {
            var card = $('#notifications-card');
            if(card.data('count') == 0) {
                $('.table').fadeOut('slow');
                card.html('<h3 class="lead text-center">You\'re all caught up!</h3>' +
                          '<p class="text-center">If you get any notifications, they\'ll end up here.</p>');
            }
        }
    </script>
@endsection