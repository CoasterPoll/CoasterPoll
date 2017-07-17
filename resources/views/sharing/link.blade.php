@extends('layouts.app')

@section('title')
    {{ $link->title }}
@endsection

@section('content')
    <div class="row">
        <div class="col-9">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('links') }}">Links</a></li>
                @if($link->linkable_type == 'ChaseH\Models\Coasters\Coaster')
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->park->getLink() }}">{{ $link->linkable->park->short }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->getLink() }}">{{ $link->linkable->name }}</a></li>
                @endif
                @if($link->linkable_type == 'ChaseH\Models\Coasters\Park')
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->getLink() }}">{{ $link->linkable->short }}</a></li>
                @endif
                @if($link->linkable_type == 'ChaseH\Models\Coasters\Manufacturer')
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->getLink() }}">{{ $link->linkable->abbreviation }}</a></li>
                @endif
                <li class="breadcrumb-item active" id="breadcrumb-title">{{ $link->title }}</li>
            </ol>
            <div class="card card-block">
                <form action="{{ route('links.edit.post') }}" method="post" id="link-form">
                    <h1 class="lead" id="link-title"><a href="{{ $link->out() }}" target="_blank" id="link-a">{{ $link->title }}</a></h1>
                    <fieldset class="hidden form-group {{ $errors->has('title') ? 'has-danger' : '' }}" id="title-fieldset">
                        <label for="title" class="sr-only">Title</label>
                        <input type="text" class="form-control form-control-lg" name="title" id="title" placeholder="An interesting title perhaps?" tabindex="1" value="{{ old('input') }}">
                        @if($errors->has('title'))
                            <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                        @endif
                    </fieldset>
                    <fieldset class="hidden form-group {{ $errors->has('link') ? 'has-danger' : '' }}" id="link-fieldset">
                        <label for="link" class="sr-only">Link</label>
                        <input type="url" class="form-control form-control-feedback" name="link" id="link" tabindex="2" placeholder="Got a cool link to share?">
                        @if($errors->has('link'))
                            <div class="form-control-feedback">{{ $errors->first('link') }}</div>
                        @endif
                    </fieldset>
                    <small class="text-muted">Posted by
                        <a href="{{ $link->getPoster()->getProfileLink() }}">{{ $link->getPoster()->handle }}</a>
                        at
                        <span title="{{ $link->created_at->toDateTimeString() }}">{{ $link->created_at->diffForHumans() }}@if($link->updated_at->between($link->created_at, $link->created_at->copy()->addMinutes(5)))* @endif</span>
                    </small>
                    @null($link->body)
                        <div id="link-body">
                            {!! $link->body() !!}
                        </div>
                        <hr>
                    @endnull
                    <fieldset class="hidden form-group {{ $errors->has('body') ? 'has-danger' : '' }}" id="body-fieldset">
                        <label for="body" class="sr-only">body</label>
                        <textarea class="form-control" name="body" id="body" rows="8" tabindex="3">{{ $link->body }}</textarea>
                        @if($errors->has('link'))
                            <div class="form-control-feedback">{{ $errors->first('body') }}</div>
                        @endif
                    </fieldset>
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $link->id }}">
                    <ul class="list-unstyled list-inline thumb-group small mr-3" style="display: inline">
                        <li class="list-inline-item"><a role="button" class="thumb-up" data-thing="L{{ $link->id }}" data-direction="1"><i class="fa fa-thumbs-up @auth(){{ $link->getVoteClass('up') }}@endauth"></i></a></li>
                        <li class="thumb-score list-inline-item">{{ $link->score }}</li>
                        <li class="list-inline-item"><a role="button" class="thumb-up" data-thing="L{{ $link->id }}" data-direction="-1"><i class="fa fa-thumbs-down @auth(){{ $link->getVoteClass('down') }}@endauth"></i></a></li>
                    </ul>
                    <a href="{{ url($link->getLink()) }}" class="small card-link">Permalink</a>
                    @link($link)
                        <a class="small card-link" role="button" id="edit-btn">Edit</a>
                        <a class="small card-link hidden" role="button" id="submit-btn">Save</a>
                    @endlink
                    @auth
                        <a class="card-link small report-link-btn text-danger" data-link="{{ $link->id }}" role="button" id="report-link">
                            Report
                            @if(Auth::user()->can('Can moderate comments') && $link->reports->count() > 0)
                                <span class="badge badge-warning">{{ $link->reports->count() }}</span>
                            @endif
                        </a>
                    @endauth
                </form>
            </div>
            @can('Can comment')
                <div id="reply-block" class="mt-3">
                    <form action="{{ route('comment.post') }}" method="post" id="link-comment-form">
                        <fieldset class="form-group">
                            <label for="link_comment-body" class="sr-only">Comment</label>
                            <textarea name="body" class="form-control" rows="6" id="link_comment-body"></textarea>
                        </fieldset>
                        <fieldset class="form-group">
                            {{ csrf_field() }}
                            <input type="hidden" name="commentable" value="{{ $link->getCid() }}">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-comment"></i> Comment</button>
                        </fieldset>
                    </form>
                </div>
            @endcan
            <div id="comments">
                @include('sharing.partials._comment', ['comments' => $comments])
                {{ $comments->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
        <div class="col-3">
            @include('sharing.sidebar')
            <div class="card card-outline-warning mt-3">
                <div class="card-header">
                    <h4 class="card-title">Reports</h4>
                </div>
                <div class="card-block">
                    <table class="table table-sm">
                        @foreach($link->reports as $report)
                            <tr>
                                <td>{{ $report->reason }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @link($link)
        <script src="{{ config('app.cdn') }}/js/showdown.min.js"></script>
    @endlink
    <script>
        @link($link)
        var converter = new showdown.Converter();
        converter.setFlavor('github');

        $('#edit-btn').on('click', function() {
            convertToForm();
        });
        $('#submit-btn').on('click', function(e) {
            e.preventDefault();
            convertToLink();
            $.post({
                url: "{{ route('links.edit.post') }}",
                data: $('#link-form').serialize(),
                success: function(resp) {
                    $('#link-a').text(resp.title);
                    $('#link-a').prop('href', resp.link);
                    $('#link-body').html(converter.makeHtml(resp.body));
                }
            });

        });
        function convertToForm() {
            $('#title-fieldset').removeClass('hidden').find('#title').val($('#link-a').text());
            $('#link-fieldset').removeClass('hidden').find('#link').val($('#link-a').prop('href'));
            $('#body-fieldset').removeClass('hidden');
            $('#link-title').addClass('hidden');
            $('#link-body').addClass('hidden');

            $('#edit-btn').addClass('hidden');
            $('#submit-btn').removeClass('hidden');
        }
        function convertToLink() {
            $('#title-fieldset').addClass('hidden');
            $('#link-a').text($('#title').val());
            $('#breadcrumb-title').text($('#title').val());
            $('#link-fieldset').addClass('hidden');
            $('#link-a').prop('href', $('#link').val());
            $('#body-fieldset').addClass('hidden');
            $('#link-title').removeClass('hidden');
            $('#link-body').removeClass('hidden').html(converter.makeHtml($('#body').val()));

            $('#edit-btn').removeClass('hidden');
            $('#submit-btn').addClass('hidden');
        }
        @endlink
        @can('Can comment')
        $('.reply-to-comment').on('click', function() {
            var button = $(this);
            button.toggleClass('strong');
            var parent = $(this).parent('.comment');
            if(parent.data('hasReplyForm') === true) {
                parent.data('hasReplyForm', false);
                parent.find('form').remove();
            } else {
                parent.data('hasReplyForm', true);
                var form = $('#link-comment-form').clone();
                form.append("<input type='hidden' name='parent' value='"+parent.data('comment')+"'>");
                form.find('.btn-primary').attr('onclick', "submitNewComment(event)");
                button.after(form);
            }
        });
        function submitNewComment(event) {
            event.preventDefault();
            var form = $(event.target).closest('form');
            var data = form.serializeArray();
            realdata = {};
            $(data).each(function(i, field) {
                realdata[field.name] = field.value;
            });

            form.addClass('hidden');

            var comment = form.closest('.comment').clone();
            var parent = form.closest('.comment');

            $.post({
                url: "{{ route('comment.post') }}",
                data: form.serialize(),
                success: function(resp) {
                    window.id = resp.id;
                    window.newbody = resp.body;
                }
            });

            // Remove child comments
            comment.find('.comment').remove();

            // Update with new values
            comment.find('.card-text').html(converter.makeHtml(realdata.body));
            comment.find('h4').text("{{ \Illuminate\Support\Facades\Auth::user()->handle }}");
            comment.attr('data-comment', window.id);
            console.log(comment);
            parent.append(comment);
        }
        @endcan
    </script>
@endsection