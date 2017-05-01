<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-block">
            <form action="{{ route('coasters.manufacturer.update') }}" method="post" enctype="multipart/form-data">
                <fieldset class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" name="name" @isset($manufacturer->name)value="{{ $manufacturer->name }}" @endisset>
                </fieldset>
                <fieldset class="form-group">
                    <label for="abbreviation">Abbreviation/Short Name</label>
                    <input type="text" id="abbreviation" class="form-control" name="abbreviation" @isset($manufacturer->abbreviation)value="{{ $manufacturer->abbreviation }}" @endisset>
                    <p class="text-muted">Must not contain spaces. Should not be changed once set. Links rely on it.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" class="form-control" name="location" @isset($manufacturer->location)value="{{ $manufacturer->location }}" @endisset>
                </fieldset>
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-8">
                            <label for="website">Website</label>
                            <input type="url" id="website" class="form-control" name="website" @isset($manufacturer->website)value="{{ $manufacturer->website }}" @endisset>
                            <p class="text-muted">The full URL.</p>
                        </div>
                        <div class="col-sm-4">
                            <label for="rcdb_id">RCDB ID</label>
                            <input type="number" id="rcdb_id" class="form-control" name="rcdb_id" @isset($manufacturer->rcdb_id)value="{{ $manufacturer->rcdb_id }}" @endisset>
                            <p class="text-muted">Only the numbers in the URL (before the .htm).</p>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control-file" id="photo" name="photo">
                </fieldset>
                <fieldset class="form-group">
                    <label for="img_url">Image URL</label>
                    <input type="text" name="img_url" value="@if($manufacturer->hasImg()) {{ $manufacturer->getImg() }} @endif " id="img_url" class="form-control">
                </fieldset>
                <fieldset class="form-group">
                    @isset($manufacturer->id)
                        <input type="hidden" name="manufacturer" value="{{ $manufacturer->id }}">
                    @endisset
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="submit" class="btn btn-danger confirm-form" name="delete" value="true"><i class="fa fa-trash"></i> Hide</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>