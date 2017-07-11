 <form action="{{ route('coasters.coaster.update') }}" method="post" enctype="multipart/form-data">
     <div class="row">
        <div class="col-md-6 offset-md-1">
            <div class="card card-block" id="main-card">
                <fieldset class="form-group">
                    <label for="name">Coaster Name</label>
                    <input type="text" id="name" class="form-control" name="name" @isset($coaster->name)value="{{ $coaster->name }}" @endisset>
                </fieldset>
                <fieldset class="form-group">
                    <label for="slug">Short Name</label>
                    <input type="text" id="slug" class="form-control" name="slug" @isset($coaster->slug)value="{{ $coaster->slug }}" @endisset>
                    <p class="text-muted">Must not contain spaces. Should not be changed once set. All coaster links rely on it.</p>
                </fieldset>
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="park">Park</label>
                            <select name="park" class="form-control" id="park">
                                @foreach($parks as $park)
                                    <option value="{{ $park->id }}" @if(isset($coaster->park) && $coaster->park->id == $park->id) selected @endif >{{ $park->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="manufacturer">Manufacturer</label>
                            <select name="manufacturer" class="form-control" id="manufacturer">
                                @foreach($manufacturers as $manufacturer)
                                    <option value="{{ $manufacturer->id }}" @if(isset($coaster->manufacturer) && $coaster->manufacturer->id == $manufacturer->id) selected @endif >{{ $manufacturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" @if(isset($coaster->type) && $coaster->type->id == $type->id) selected @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="rcdb_id">RCDB ID</label>
                            <input type="number" id="rcdb_id" class="form-control" name="rcdb_id" @isset($coaster->rcdb_id)value="{{ $coaster->rcdb_id }}" @endisset>
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
                    <input type="text" name="img_url" value="@if(isset($coaster) && $coaster->hasImg()) {{ $coaster->getImg() }} @endif " id="img_url" class="form-control">
                </fieldset>
                <fieldset class="form-group">
                    @isset($coaster->id)
                        <input type="hidden" name="coaster" value="{{ $coaster->id }}">
                    @endisset
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    <button type="submit" class="btn btn-danger confirm-form" name="delete" value="true"><i class="fa fa-trash"></i> Hide</button>
                </fieldset>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-block" id="categories-card" style="overflow-y: scroll;">
                <table class="table table-sm">
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <th>{{ $category->name }}</th>
                                <td class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]}" value="{{ $category->id }}"
                                           @if(isset($coaster) && $coaster->categories->contains('id', $category->id)) checked @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>