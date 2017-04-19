<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-block">
            <form action="{{ route('coasters.park.update') }}" method="post">
                <fieldset class="form-group">
                    <label for="name">Park Name</label>
                    <input type="text" id="name" class="form-control" name="name" value="{{ $park->name }}">
                </fieldset>
                <fieldset class="form-group">
                    <label for="short">Short Name</label>
                    <input type="text" id="short" class="form-control" name="short" value="{{ $park->short }}">
                    <p class="text-muted">Must not contain spaces. Should not be changed once set. All coaster links rely on it.</p>
                </fieldset>
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="city">City</label>
                            <input type="text" id="city" class="form-control" name="city" value="{{ $park->city }}">
                        </div>
                        <div class="col-sm-4">
                            <label for="city">Country</label>
                            <input type="text" id="country" class="form-control" name="country" value="{{ $park->country }}">
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="website">Website</label>
                            <input type="url" id="website" class="form-control" name="website" value="{{ $park->website }}">
                            <p class="text-muted">The full URL.</p>
                        </div>
                        <div class="col-sm-4">
                            <label for="rcdb_id">RCDB ID</label>
                            <input type="number" id="rcdb_id" class="form-control" name="rcdb_id" value="{{ $park->rcdb_id }}">
                            <p class="text-muted">Only the numbers in the URL (before the .htm).</p>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group">
                    <input type="hidden" name="park" value="{{ $park->id }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>