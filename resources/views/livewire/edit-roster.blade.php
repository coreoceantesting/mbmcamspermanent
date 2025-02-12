<div>
    @push('styles')
        <style>
            .modal-bg {
                background: rgba(0, 0, 0, 0.5);
            }
        </style>
    @endpush

    <div class="modal fade {{ $is_modal_open ? 'show d-block modal-bg' : '' }}" id="addtask" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Shift From {{ Carbon\Carbon::parse($from_date)->toDateString() }} - To {{ Carbon\Carbon::parse($to_date)->toDateString() }}</h4>
                    <button type="button" class="btn btn-light" wire:click="$set('is_modal_open', false)" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        @foreach ($date_ranges as $key => $date_range)
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ $date_range->toDateString() }} - ({{ strtoupper($date_range->format('D')) }}) Shift</label>
                                    <select class="form-select @if ($errors->has('editable_dates.{{ $key }}')) is-invalid @endif" name="editable_dates.{{ $key }}" wire:model.defer="editable_dates.{{ $key }}">
                                        <option value="">----</option>
                                        <option value="wo">WEEK OFF</option>
                                        <option value="no">NIGHT OFF</option>
                                        <option value="rem">REMOVE SHIFT</option>
                                        @foreach ($shiftLists as $shiftList)
                                            <option value="{{ $shiftList->id }}">{{ $shiftList->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('editable_dates.{{ $key }}'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('editable_dates.' . $key) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" wire:click="$set('is_modal_open', false)" data-dismiss="modal">Close</button>
                    <button type="submit" wire:click="update()" class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>

            <div wire:loading.flex style="position: absolute;
                width: 100%;
                height: 100%;
                justify-content: center;
                align-items: center;
                background: rgba(245,245,251,0.6);
                z-index: 9;
                pointer-events: none;
                font-size: 20px;
                top: 0;
                left: 0;
                align-content: center;
                text-align: center;
                font-weight: 600">Loading...
            </div>
        </div>
    </div>
</div>
