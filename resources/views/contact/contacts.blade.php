@extends('layouts.admin')

@section('title')
    All Contact Messages
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Contact Us Messages</h2>
                </div>
                <div class="card-block">
                    <table class="table table-striped">
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                    <td>{{ str_limit($contact->message) }}</td>
                                    <td>{{ $contact->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $contacts->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection