@extends('layouts.admin')

@section('title')
    Cache Control
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('admin.site.cache.post') }}" method="post">
                {{ csrf_field() }}
                <table class="table">
                    <tr>
                        <th>Permissions</th>
                        <td>All Permission based records (perm-role, roles)</td>
                        <td><button type="submit" name="cache" value="permissions" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Statistics</th>
                        <td>Statistics everywhere. Forget em.</td>
                        <td><button type="submit" name="cache" value="statistics" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Coasters</th>
                        <td>Most of the cache body.</td>
                        <td><button type="submit" name="cache" value="coasters" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Content</th>
                        <td>CMS pages and page indexes.</td>
                        <td><button type="submit" name="cache" value="content" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>User Functions</th>
                        <td>Notifications, etc.</td>
                        <td><button type="submit" name="cache" value="user_funct" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Links</th>
                        <td>Links above and below.</td>
                        <td><button type="submit" name="cache" value="links" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Subscriptions</th>
                        <td>Subscription status (inactive).</td>
                        <td><button type="submit" name="cache" value="subscriptions" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                    <tr>
                        <th>Site</th>
                        <td>Internal entries.</td>
                        <td><button type="submit" name="cache" value="site" class="btn btn-outline-danger">Clear</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection