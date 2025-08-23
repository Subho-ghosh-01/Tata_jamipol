<?php
use App\Division;
use App\Department;
use App\UserLogin;
$user_data = UserLogin::where('id', Session::get('user_idSession'))->first();
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="">Vendor Form</a></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('admin.vendor.store')); ?>" method="post" autocomplete="off" enctype="multipart/form-data"
        id="quickForm">
        <?php echo csrf_field(); ?>
        <div class="form-group-row">
            <div class="col-sm-12">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if(session()->has('message')): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    title: '<?php echo e(session('message')); ?>'
                });
            </script>
        <?php endif; ?>



        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Company Name <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="company_name" value="<?php echo e(@$user_data->company_name); ?>">
                <input type="hidden" class="form-control" name="id" value="<?=Session::get('user_idSession')?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Mobile Number <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <!--<input type="text"  class="form-control rec" name="mobile_no" id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength="10" minlength="10">-->
                <input type="number" class="form-control" name="mobile_no" value="<?php echo e($user_data->mobile_no); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Emergency Contact Number
                <span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <!--<input type="text"  class="form-control rec" name="mobile_no" id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength="10" minlength="10">-->
                <input type="number" class="form-control rec" name="emergency_mobile_no"
                    value="<?php echo e(@$user_data->emergency_contact_no); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Email ID <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <!--<input type="text"  class="form-control rec" name="mobile_no" id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" maxlength="10" minlength="10">-->
                <input type="text" class="form-control" name="" value="<?=Session::get('email')?>" disabled>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Landing No <span></span></label>
            <div class="col-sm-10">
                <input type="number" class="form-control " name="landing_no" value="<?php echo e(@$user_data->landing_no); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Proprietor/MD name <span
                    style="color:rgb(255, 0, 0);font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="md_name" value="<?php echo e(@$user_data->md_name); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">GSTN <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="GSTN" value="<?php echo e(@$user_data->GSTN); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">PAN of the Organization <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="pan_of_the_orgination"
                    value="<?php echo e(@$user_data->pan_of_the_orgination); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">ESIC Code <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="esci_code" maxlength="20"
                    value="<?php echo e(@$user_data->esci_code); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">EISC Document (Max.2 MB)<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="esic_document" value="<?php echo e(@$user_data->esci_document); ?>"
                    required accept="application/pdf" />
            </div>
        </div>
        <div class="form-group row">
            <label for="wcp_no" class="col-sm-2 col-form-label">
                Workman Compensation No <small style="color:red" class="text">(Non-Mandatory
                    Field)</small>
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="wcp_no" id="wcp_no" value="<?php echo e(@$user_data->wcp_no); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="wcp_validity" class="col-sm-2 col-form-label">
                Workman Compensation Validity <small style="color:red;" class="text">(Non-Mandatory
                    Field)</small>
            </label>
            <div class="col-sm-10">
                <input type="date" class="form-control" name="wcp_validity" id="wcp_validity"
                    value="<?php echo e(@$user_data->wcp_validity); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="wcp_document" class="col-sm-2 col-form-label">
                Workman Compensation Document (Max.2 MB pdf) <small style="color:red;" class="text">(Non-Mandatory
                    Field)</small>
            </label>
            <div class="col-sm-10">
                <input type="file" class="form-control" name="wcp_document" id="wcp_document" accept="application/pdf" />
            </div>
        </div>


        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">EPF Code <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="epf_code" value="<?php echo e(@$user_data->epf_code); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Location <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <!-- <input type="text"  class="form-control rec" name="location"> -->

                <select class="form-control rec" name="location">
                    <option value="">--Select--</option>
                    <option value="Jharkhand" <?php if(@$user_data->location == 'Jharkhand'): ?> <?php echo e('selected'); ?><?php endif; ?>>Jharkhand
                    </option>
                    <option value="Karnataka" <?php if(@$user_data->location == 'Karnataka'): ?> <?php echo e('selected'); ?><?php endif; ?>>Karnataka
                    </option>
                    <option value="Odisha" <?php if(@$user_data->location == 'Odisha'): ?> <?php echo e('selected'); ?><?php endif; ?>>Odisha</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Contract Type <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="contract_type" id="">
                    <option value="">--Select--</option>
                    <option value="service provider" <?php if(@$user_data->contract_type == 'service provider'): ?><?php echo e('selected'); ?><?php endif; ?>>Service provider</option>
                    <option value="supplier" <?php if(@$user_data->contract_type == 'supplier'): ?><?php echo e('selected'); ?><?php endif; ?>>
                        Supplier</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Nature of Work <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <select class="form-control rec" name="nature_of_work" id="">
                    <option value="">--Select--</option>
                    <option value="High" <?php if(@$user_data->nature_of_work == 'High'): ?> <?php echo e('selected'); ?><?php endif; ?>>High</option>
                    <option value="Low" <?php if(@$user_data->nature_of_work == 'Low'): ?> <?php echo e('selected'); ?><?php endif; ?>> Low</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Labour Capacity <span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="lobour_capacity" value="<?php echo e(@$user_data->lobour_capacity); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Labour License No </label>
            <div class="col-sm-10">
                <input type="text" class="form-control " name="lobour_license_no"
                    value="<?php echo e(@$user_data->lobour_license_no); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Labour License Validity</label>
            <div class="col-sm-10">
                <input type="date" class="form-control " name="labour_license_validity"
                    value="<?php echo e(@$user_data->labour_license_validity); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Labour License Document</label>
            <div class="col-sm-10">
                <input type="file" class="form-control " name="lobour_license_document">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">EC Policy<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="ec_policy" value="<?php echo e(@$user_data->ec_policy); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">EC Policy Document<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="ec_policy_document">
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">PO Number<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control rec" name="po_number" value="<?php echo e(@$user_data->po_number); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">PO Document<span
                    style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="file" class="form-control rec" name="po_number_document">
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-15">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1">
                    <label class="custom-control-label" for="exampleCheck1">I agree to the <a
                            href="https://wps.jamipol.com/documents/clm_pics/Data Privacy Notice.pdf" target="_blank">terms
                            and conditions </a>.</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <input type="submit" name="submit" class="btn btn-primary" value="Submit" onclick="return form_validate();">
            </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript">

        $('#quickForm').validate({
            rules: {
                mobile_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                terms: {
                    required: true
                },
                lobour_license_no: {
                    required: function () {
                        return parseInt($('[name="lobour_capacity"]').val()) >= 10;
                    }
                },
                labour_license_validity: {
                    required: function () {
                        return parseInt($('[name="lobour_capacity"]').val()) >= 10;
                    }
                },
                lobour_license_document: {
                    required: function () {
                        return parseInt($('[name="lobour_capacity"]').val()) >= 10;
                    }
                }
            },
            messages: {
                mobile_no: {
                    required: "Please provide a Mobile Number",
                    minlength: "Your Mobile Number must be at least 10 digit long"
                },
                terms: "Please accept our terms",
                lobour_license_no: "Labour License No is required when Labour Capacity is 10 or more",
                labour_license_validity: "Labour License Validity is required when Labour Capacity is 10 or more",
                lobour_license_document: "Labour License Document is required when Labour Capacity is 10 or more"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        // Add change event to update validation when labour capacity changes
        $('[name="lobour_capacity"]').on('change keyup', function () {
            $('#quickForm').validate().element('[name="lobour_license_no"]');
            $('#quickForm').validate().element('[name="labour_license_validity"]');
            $('#quickForm').validate().element('[name="lobour_license_document"]');
        });

    </script>

    <script>
        /* $(function() {
             $('#Employee').show(); 
             $('#u_typ
         </div>e').change(function(){
                 if($('#u_type').val() == 1) {
                     $('#Employee').show(); 
                     $('#sup').hide(); 

                 } else {
                     $('#Employee').hide(); 
                     $('#sup').show(); 

                 } 
             });
         });*/





        // get the Department data
        $('#division_id').on('change', function () {
            var division_ID = $(this).val();
            $("#department_id").html('<option value="">--Select--</option>');
            if (division_ID) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "<?php echo e(route('admin.user.department')); ?>/" + division_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for (var i = 0; i < data.length; i++) {
                            $("#department_id").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                        }
                    }
                });
            } else {
                $('#department_id').html('<option value="null">Select Department</option>');
            }
        });

        //add supervisor
        $("#add_sup").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            $('#append_sup').append(`<input type="text" class="form-control remove_tr" name="supervisor[]" id="supervisor_id">&nbsp;`);
        });
        //Remove Confined To Click 
        $("#sub_sup").on("click", function (e) {
            if ($('.remove_tr').length > 1) {
                $(".remove_tr:last").remove();
            }
        });


        //gate pass Details to add
        $("#btn-add").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            // console.log(count);
            $('#append_gatepass').append(`<tr class="gatepass">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><input type="text" class="form-control" name="employee[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><input type="text" class="form-control" name="gatepass[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><input type="text" class="form-control" name="designation[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><input type="text" class="form-control" name="age[]"></td> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><input type="date" class="form-control start_date" name="expirydate[]"  value=""></td>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </tr>`);
        });

        //Remove Top Click
        $("#btn-remove").on("click", function (e) {
            if ($('.gatepass').length > 1) {
                $(".gatepass:last").remove();
            }
        });


        //Append code
        $("#btn-add-vendor").on("click", function (e) {
            var count = $(".appendrow").length + 1;
            count = count - 1;
            // i++;
            // console.log(count);
            $('#dataview').append(`<tr class="appendrow">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><input type="text" class="form-control" name="supervisor_ven[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><input type="text" class="form-control" name="electrical_license_ven[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><input type="date" class="form-control" name="license_validity_ven[]"></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><table style="width: 180px;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr><td><span>132KV</span></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><input type="radio" name="v132kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="radio" name="v132kv_ven[`+ count + `]" value="no">&nbsp; No
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr><td><span>33KV</span></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><input type="radio" name="v33kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="radio" name="v33kv_ven[`+ count + `]" value="no">&nbsp; No
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr><td><span>11KV</span></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><input type="radio" name="v11kv_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="radio" name="v11kv_ven[`+ count + `]" value="no">&nbsp; No
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr><td><span>LT</span></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><input type="radio" name="vlt_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <input type="radio" name="vlt_ven[`+ count + `]" value="no">&nbsp; No
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </table>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label class="form-check-label">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" class="" name="issue_power_ven[`+ count + `]"  checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label class="form-check-label">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" class=""  name="issue_power_ven[`+ count + `]" value="no">&nbsp; No  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label class="form-check-label">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" class="" name="rec_power_ven[`+ count + `]" checked value="yes">&nbsp; Yes
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <label class="form-check-label">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="radio" class=""  name="rec_power_ven[`+ count + `]" value="no">&nbsp; No  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </tr>`);
        });

        //Remove 
        $("#btn-remove-vendor").on("click", function (e) {
            if ($('.appendrow').length > 1) {
                $(".appendrow:last").remove();
            }
        });
        function ElectricalSupervisoryEmployee(items) {
            if (items != "") {
                if (items == 'yes') {
                    $("#Electrical_Yes").show();
                }
                else if (items == 'no') {
                    $("#Electrical_Yes").hide();
                }
            }
        }
        function ElectricalSupervisoryVendor(items) {
            if (items != "") {
                if (items == 'yes') {
                    $("#Electrical-Vendor").show();
                }
                else if (items == 'no') {
                    $("#Electrical-Vendor").hide();
                }
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_details.blade.php ENDPATH**/ ?>