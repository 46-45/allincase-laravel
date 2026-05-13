<!-- Priority Tickets-->
<div class="modal fade sprukopriority" data-bs-backdrop="static" id="addpriority" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" ></h5>
                <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="priority_form" name="priority_form">
                @csrf
                @honeypot
                <input type="hidden" name="priority_id" id="priority_id" value="">
                @csrf
                <div class="modal-body">

                    <div class="custom-controls-stacked d-md-flex" >
                        <select class="form-control select2_modalpriority selectingpriority" data-placeholder="{{lang('Select Priority')}}" name="priority_user_id" id="priority" >

                        </select>
                    </div>
                    <span id="PriorityError" class="text-danger"></span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="pribtnsave" >{{lang('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End priority Tickets  -->
