@if(isset($ridden_coasters[$ridden_coaster_id]))
    <button type="button" class="btn btn-sm btn-success _ridden-btn" data-id="{{ $ridden_coaster_id }}" has-ridden="true">
        <i class="fa fa-check-square-o"></i> Ridden
    </button>
@else
    <button type="button" class="btn btn-sm btn-outline-success _ridden-btn" data-id="{{ $ridden_coaster_id }}" has-ridden="false">
        <i class="fa fa-square-o"></i> Ridden
    </button>
@endif