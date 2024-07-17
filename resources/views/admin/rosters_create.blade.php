<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Create Roster</x-slot>
    <livewire:styles />

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">

                            <div class="card-header pb-0">
                                <h3>Add Shift</h3>
                            </div>

                            <div class="card-body">

                                <livewire:add-roster />

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


<livewire:scripts />
</x-admin.admin-layout>

