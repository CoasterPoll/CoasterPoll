@extends('layouts.admin')

@section('title')
    Contact
@endsection

@section('content')
    <div class="row">
        <div class=" @if($contact->contactable == null) col-md-8 offset-md-2 @else col-md-6 @endif">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">New Message From {{ $contact->name }}</h2>
                </div>
                <div class="card-block">
                    <table class="table-sm table">
                        <tr>
                            <th>Sender</th>
                            <td>{{ $contact->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        </tr>
                        @if($contact->user)
                            <tr>
                                <th>User</th>
                                <td>{{ $contact->user->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Sent At</th>
                            <td>{{ $contact->created_at }}</td>
                        </tr>
                    </table>
                    <p>{{ $contact->message }}</p>
                    @if($contact->extra !== null)
                        <hr>
                        <table class="table table-sm">
                            <tbody>
                                @foreach($contact->extra as $key => $item)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        @if(gettype($item) == "array")
                                            <th>{{ implode(", ", $item) }}</th>
                                        @else
                                            <th>{{ $item }}</th>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        @if($contact->contactable != null)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">The <em>Original</em></h2>
                    </div>
                    <div class="card-block">
                        <a href="{{ $contact->contactable->getLink() }}">View in Context</a>
                        <table class="table table-sm">
                            <tbody>
                                @foreach($contact->contactable->toArray() as $key => $item)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        <td>{{ $item }} @if(str_contains($key, '_id') && isset($contact->contactable->{str_replace("_id", "", $key)}->name)) ({{ $contact->contactable->{str_replace("_id", "", $key)}->name }}) @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection