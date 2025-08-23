<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('visitor_gate_pass')->where('id', $id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id', @$gatepassv->department)->first();
@$division_p = Division::where('id', @$gatepassv->division_id)->first();
@$approver = UserLogin::where('id', @$gatepassv->approver)->first();

?>



<style>
  .p {
    //padding-left:100px;
    padding-bottom: 2px;
    font-size: 20px;
  }

  .t {

    font-size: 20px;
  }

  .float-container {
    border: 3px solid #fff;
    padding: 20px;
  }

  .float-child {
    width: 50%;
    float: left;
    padding: 20px;
    //border: 2px solid red;
  }

  .solid {
    border-style: double;

    border-width: 2px;
    width: 1000px;
    height: 1050px;
  }

  * {
    box-sizing: border-box;
  }

  /* Create three equal columns that floats next to each other */
  .column {
    float: left;
    width: 33.33%;
    padding: 10px;
    //height: 300px; /* Should be removed. Only for demonstration */
  }
</style>
<div class="solid"> <br>
  <div class="row">
    <div class="column">
      <img src="<?php echo e(URL::to('images/logo-cin1.png')); ?>" style="float:left;width:180px;">
    </div>
    <div class="column">
      <div style="text-align:center;">
        <h1>Visitor Gate Pass </h1>
      </div>
    </div>
    <div class="column">
      <h5 style="text-align:right;">VGP No: <?php echo e(@$gatepassv->full_sl); ?> </h5>
    </div>
  </div>
  <div class="float-container">

    <div class="float-child">
      <div class="green">
        <table class="t1">

          <tr style="padding-bottom: 5px;">
            <th style="border-style: solid;"><a target="_blank"><img
                  src="https://wps.jamipol.com/documents/clm_pics/<?php echo e(@$gatepassv->upload_photo); ?>" width="150px"
                  height="100px"></a>
            </th>
          </tr>

          <tr>
            <th class="p">Name :</th>
            <td class="t"><?php echo e(@$gatepassv->visitor_name); ?></td>
          </tr>

          <tr>
            <th class="p">Company Name :</th>
            <td class="t"><?php echo e(@$gatepassv->visitor_company); ?></td>
          </tr>
          <tr>
            <th class="p">Visitor Email :</th>
            <td class="t"> <?php echo e(@$gatepassv->visitor_email); ?></td>
          </tr>
          <tr>
            <th class="p">Visit Date :</th>
            <td class="t"> <?php echo e(@$gatepassv->from_date); ?></td>
          </tr>
          <tr>
            <th class="p">Proof of Identity :</th>
            <td class="t"><?php echo e(@$gatepassv->id_proof_type); ?></td>
          </tr>
          <tr>
            <th class="p">Unique Identification Number :</th>
            <td class="t"><?php echo e(@$gatepassv->id_number); ?></td>
          </tr>
          <tr>
            <th class="p">Visit Till Date :</th>
            <td class="t"> <?php echo e(@$gatepassv->to_date); ?></td>
          </tr>
          <tr>
            <th class="p">Valid From : </th>
            <td class="t"><?php echo e(date('h:i A', strtotime(@$gatepassv->from_time))); ?></td>
          </tr>
          <tr>
            <th class="p">Valid Till : </th>
            <td class="t"><?php echo e(date('h:i A', strtotime(@$gatepassv->to_time))); ?></td>
          </tr>
          <tr>
            <th class="p">Contact No : </th>
            <td class="t"><?php echo e(@$gatepassv->visitor_mobile_no); ?></td>
          </tr>
          <tr>
            <th class="p">
              <div>
                <h4><b>To Meet : </b></h4>
              </div>
            </th>
            <td>
              <div>
                <h4><b><?php echo e(@$approver->name); ?> </b></h4>
              </div>
            </td>
          </tr>
        </table>
        <?php if(@$gatepassv->any_material != 'No'): ?>
          <h4 style="">
            <?php if(@$gatepassv->returnable != 'No'): ?>
          Retuenable Material :
          <?php else: ?> Retuenable Material :
          <?php endif; ?>

          </h4>
          <div style="">
            <table border="1">
            <thead style="border-style: double;">
              <th>Material Name</th>
              <th>Material Identification No</th>
              <th>Returnable/Non Returnable</th>
              <th>Purpose of Material Entry</th>
            </thead>
            <tbody style="border-style: double;">
              <?php
        $test = unserialize($gatepassv->material_name);
        $test_id = unserialize($gatepassv->material_identification_no);
        $returnable = unserialize($gatepassv->returnable);
        $propose_of_entry = unserialize($gatepassv->propose_of_entry);
        $count = count($test);

        for ($i = 0; $i < $count; $i++) {
            ?>

              <tr class="gatepass" id="gatepass">

              <td><?php echo e(@$test[$i]); ?></td>
              <td><?php echo e(@$test_id[$i]); ?></td>
              <td><?php echo e(@$returnable[$i]); ?></td>
              <td><?php echo e(@$propose_of_entry[$i]); ?></td>

              </tr>
              <?php
        }
            ?>

            </tbody>
            </table>
          </div>
    <?php endif; ?>
        <br>
        <?php if(@$gatepassv->visitor_any_vehicle != 'No'): ?>
      <h4 style="">Vehicle Details:</h4>

      <div style="">
        <table border="1" style="border-style: double;">
        <thead style="border-style: double;">

          <th>Driving Mode</th>

          <?php if(@$gatepassv->driving_mode != 'self'): ?>
        <th>Driver Name</th>
      <?php endif; ?>
          <th>Vehicle No</th>
          <th>Driving licence No</th>
        </thead>
        <tbody style="border-style: double;">
          <td><?php echo e(@$gatepassv->driving_mode); ?></td>
          <?php if(@$gatepassv->driving_mode != 'self'): ?>
        <td><?php echo e(@$gatepassv->driver_name); ?></td>
      <?php endif; ?>
          <td><?php echo e(@$gatepassv->vehicle_no); ?></td>
          <td><?php echo e(@$gatepassv->dl_no); ?></td>

        </tbody>
        </table>
      </div>
    <?php endif; ?>
        <br>
        <h4 style="">Appointments:</h4>

        <div style="">
          <table border="1" style="border-style: double;">
            <thead style="border-style: double;">

              <th>EMP Name</th>
              <th>Dept</th>
              <th>Start Time</th>
              <th>End Time</th>
            </thead>
            <tbody style="border-style: double;">

              <td><?php echo e(@$approver->name); ?> </td>
              <td><?php echo e(@$department_p->department_name); ?></td>
              <td><?php echo e(date('h:i A', strtotime(@$gatepassv->from))); ?></td>
              <td><?php echo e(date('h:i A', strtotime(@$gatepassv->to_time))); ?></td>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="float-child">
      <div class="blue">
        <div><b>
            <h3>Instruction :</h3>
          </b>
          <p style="font-size:20px;">1.Visitor's Pass is non transferable and not permitted for children under 18 years
            of age.<br>
            2.All Safety Rules/Norms like wearing Safety helmet/shoes must be fpllowed.speed limit inside the Works is
            16 Kmph,ensure Wearing of seat belt while driving.<br>
            3.In case of any accident,injury,illness or death during the course of the visit to the establishment,the
            visitor undertakes not to claim any compensation or damage.<br>
            4.Unauthorized collection of information or documents anduse thereof shall make visitor liable for
            prosecution.<br>
            5.Photo/video graphy inside plant is prohibited.<br>
            6.Gate Pass must br duty coutersigned by the concerned person ans submitted at gate before exit.<br>
            7.visitor is responsible for his own safety and belongings inside plant.<br>
            8.Emergency contact number:- +91 7209138937</p>
        </div>
      </div>
      <br>
      <br>
      <br>
      <style>
        #block_container {
          display: flex;

        }

        #bloc2 {
          padding-left: 180px;
          padding-bottom: 2px;
        }
      </style>
      <div id="block_container">
        <div id="bloc1">Authorised Signatory</div>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <div id="bloc2">Visitor's Signature</div>
      </div>


    </div>

  </div>

</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
  $(document).ready(function (e) {
    $(".hide").each(function (e) {
      $(this).closest('tr').remove();

    });
    setTimeout(function () {
      window.print();
    }, 200);
  })
</script>
<?php echo $__env->make('admin.app2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/printg.blade.php ENDPATH**/ ?>