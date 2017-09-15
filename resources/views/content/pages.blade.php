@extends('layouts.admin')

@section('title')
    Pages
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-8 offset-2 text-right">
            <a href="{{ route('admin.content.page') }}" class="btn btn-primary"><i class="fa fa-pencil"></i> New</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <table class="table table-striped">
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <th><a href="{{ route('admin.content.page', ['page' => $page->id]) }}" class="link-unstyled">{{ $page->title }}</a></th>
                            <td>{{ $page->subtitle }}</td>
                            <td>{{ $page->updated_at }}</td>
                            <td><a href="{{ route('content', ['page' => $page->url]) }}"><i class="fa fa-eye"></i> View</a></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th><a href="{{ route('admin.general.homepage') }}" class="link-unstyled">Homepage</a></th>
                        <td>The thing people see at <a href="{{ config('app.url') }}">{{ config('app.url') }}/</a></td>
                        <td>{{ \Carbon\Carbon::parse($last_updated)->toDateTimeString() }}</td>
                        <td><a href="{{ route('home') }}"><i class="fa fa-eye"></i> View</a></td>
                    </tr>
                </tfoot>
            </table>
            {{ $pages->links() }}
        </div>
    </div>
@endsection