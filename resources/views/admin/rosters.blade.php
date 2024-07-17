<x-admin.admin-layout>
    <x-slot name="title">{{ auth()->user()->tenant_name }} - Shift Roster</x-slot>
    <livewire:styles />

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">


                <!-- Add Form -->
                <livewire:edit-roster />


                <div class="row">
                    <div class="col-sm-12">

                        <h3>Shift Roster</h3>

                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid support-ticket">
            <div class="row">

                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="">
                                        <a class="btn btn-primary mb-3" href="{{ route('rosters.create') }}" target="_blank">Add <i class="fa fa-plus"></i></a>
                                        <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <livewire:roster-list />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- Container-fluid Ends -->
    </div>

    <livewire:scripts />
</x-admin.admin-layout>





<!-- Get Ward wise departments -->
<script>
    $("select[name='ward_id']").change( function(e) {
        e.preventDefault();

        var model_id = $(this).val();
        var url = "{{ route('wards.departments', ':model_id') }}";

        $.ajax({
            url: url.replace(':model_id', model_id),
            type: 'GET',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            success: function(data, textStatus, jqXHR)
            {
                if (!data.error)
                {
                    $("select[name='department_id']").html(data.departmentHtml);
                } else {
                    swal("Error!", data.error, "error");
                }
            },
            error: function(error, jqXHR, textStatus, errorThrown) {
                swal("Error!", "Some thing went wrong", "error");
            },
        });
    });
</script>
