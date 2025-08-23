<?php 
use App\Department;
use App\UserLogin;
use App\ShutdownChild;
use App\RenewPermit;
use App\Location;
use App\PowerCutting;
use App\Divisions;
use App\PowerClearence;

?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}">List Permit</a></li>
@endsection                        
@section('content')
<form action="{{ route('admin.power-getting-update') }}" method="post" enctype="multipart/form-data"> 
    @csrf
    <div class="form-group-row">
        <div class="col-sm-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
            @endif
        </div>
    </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Power Cutting Sl. No.</label>
            <div class="col-sm-10">
            	<input type="text" class="form-control" value="<?php echo @$P[0]->power_clearance_number ?>" readonly>
            </div>
        </div>
        <input type="text" name="PCID" value="{{@$P[0]->pc_id}}">
        @foreach($P as $key  => $value)

            <?php $month  = date('m-Y', strtotime($P[$key]->created_at));
	            $abb = DB::table('divisions')->where('id',$P[$key]->division_id)->first();
	            $oldPSerial = @$abb->abbreviation .'/'. $month .'/'.$P[$key]->serial_no;
            ?>

            <fieldset class="border p-2">
        		<input type="text" name="permitID[]" value="{{$P[$key]->id}}">
         		<legend  class="w-auto"><?= $oldPSerial ?></legend>
	            <div class="form-group row">
	            	<label for="form-control-label" class="col-sm-2 col-form-label">Select Voltage Level </label>
	                <div class="col-sm-10">
						<select class="form-control" id="voltagelevel" name="vlevel[]" required>
				            <option value="">Select Voltage </option>
		                    <option value=".132KV" <?php if('.132KV' == $P[$key]->vlevel) echo 'selected'; ?>>132KV</option>
		                    <option value=".33KV" <?php if('.33KV' == $P[$key]->vlevel) echo 'selected'; ?>>33KV</option>
		                    <option value=".11KV" <?php if('.11KV' == $P[$key]->vlevel) echo 'selected'; ?>>11KV</option>
		                    <option value=".LT" <?php if('.LT' == $P[$key]->vlevel) echo 'selected'; ?>>LT</option>
			        	</select>
	                </div>
            	</div>

            	<div class="form-group row">
	                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Issuer Name</label>
	                    <div class="col-sm-4">
	                        <select class="form-control" readonly>
	                        @if($P[$key]->issuer_power)
	                            <?php @$isspower = UserLogin::where('id',$P[$key]->issuer_power)->first(); ?>
	                            <option value="{{$isspower->id}}">{{$isspower->name }}</option>
	                        @endif
	                        </select>
	                    </div>
	                    <div class="col-sm-3">
	                        <select class="form-control"  readonly>
	                        @if($P[$key]->electrical_license_issuer)
	                            <option value="{{ $P[$key]->electrical_license_issuer}}"> {{ $P[$key]->electrical_license_issuer }}</option>
	                        @endif
	                    	</select>
	                    </div>
	                    <div class="col-sm-3">
	                        <select class="form-control"  readonly>
	                        @if($P[$key]->validity_date_issuer)
	                            <option value="<?= $P[$key]->validity_date_issuer ?>"> <?= $P[$key]->validity_date_issuer ?></option>
	                        @endif
	                        </select>
	                    </div>
	            </div>
	            <div class="form-group row">
		            <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Receiver Name</label>
		            <div class="col-sm-4">
		                <select class="form-control" id="receiverpower" name="rec_power[]"  required>
		                    <option value="">Power Clearance Receiver</option>
		                </select>
		            </div>
		            <div class="col-sm-3">
		                <select class="form-control" id="license_numberREC" required name="electrical_license_rec[]">
		                    <option value="">Electrical license</option>
		                </select>
		            </div>
		            <div class="col-sm-3">
		                <select class="form-control" id="validity_dateREC" name="validity_date_rec[]" required> 
		                    <option value="">Validity Date</option>
		                </select>
		            </div>
        		</div>																											
	            <div class="form-group row">
                	<label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>
	                <div class="col-sm-10">
	                    <table class="table table-bordered">
	                        <thead>
	                        	<tr> 
		                            <th>Permit Number </th> 
		                            <th>Name of the Equipment</th>
	                                <th>Equipment Lock No.</th>
	                                <th>Location</th>
	                                <th>Box No</th>   
	                                <th>Caution Tag No</th>
		                        </tr>
		                     </thead>
	                        <?php $powerCLS  = PowerClearence::where('permit_id',$P[$key]->id)->get(); ?>
	                        @if($powerCLS->count() > 0)
	                            @foreach($powerCLS as $key1 => $value1)
	                                <tbody>
	                                    <tr>
	                                        <td><input type="text" class="form-control" readonly value="{{$oldPSerial}}"></td>
	                                        <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->equipment }}"></td>
	                                        <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->positive_isolation_no  }}"></td>
	                                        <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->location }}"></td>
	                                    	<td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->box_no }}"></td>
	                                        <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->caution_no }}"></td>
	                                    </tr>
	                                </tbody>
	                            @endforeach
	                        @endif
	                    </table>
	                </div>
            	</div>
	            <div class="form-group row">
	                <label for="form-control-label" class="col-sm-2 col-form-label">Comment of Power Cutting </label>
	                <div class="col-sm-10">
	                    <textarea class="form-control" readonly>{{$P[$key]->power_cutting_remarks}}</textarea>
	                </div>
	            </div>

	            @if($P[$key]->return_status == "PPg")
	            <div class="form-group row">
	                <label for="form-control-label" class="col-sm-2 col-form-label">Personal Lock Removed </label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" readonly value="Yes">
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="form-control-label" class="col-sm-2 col-form-label">Status </label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" readonly value="Permit Returned">
	                </div>
	            </div>
	            <div class="form-group row">
	                <div class="col-sm-12">
	                    <table class="table table-bordered">
	                        <tbody>
	                            <tr>
	                                <div class="col-sm-10"><td>Men and Material Removed.</td></div>
	                                <div class="col-sm-2"><td>{{ ($P[$key]->pg_ins1) }} </td></div>
	                            </tr>
	                            <tr><div class="col-sm-10">
	                                <td>All the Work Permit(in Form EHSMSM/WORKS/446/4007)issued to work 
	                                on the equipment mentioned below have been cancelled prior to filling up this form for getting power.</td>
	                                </div>
	                                <div class="col-sm-2"><td>{{ ($P[$key]->pg_ins2) }}</td></div>
	                            </tr>      
	                            <tr>
	                                <div class="col-sm-10"><td>Temporary Earthing Removed from all the points.</td></div>
	                                <div class="col-sm-2"><td>{{ ($P[$key]->pg_ins3) }}</td></div>
	                            </tr>      
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	            
	            <div class="form-group row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tbody>
                            	<tr>
                                    <div class="col-sm-8"><td>(1) Removal of Executing Agency Lock No.?</td></div>
                                    <div class="col-sm-4"><td><input {{ ($P[$key]->return_status == 'PPg') ? "required" : "" }} type="checkbox" class="form-control-check" name="exe_lock[]"></td></div>
                                </tr><tr>
                                    <div class="col-sm-8"><td>(2) Removal of Working Agency Lock No.?</td></div>
                                    <div class="col-sm-4"><td><input {{ ($P[$key]->return_status == 'PPg') ? "required" : "" }} type="checkbox" class="form-control-check" name="work_lock[]"></td></div>
                                </tr>
                                <tr>
                                    <div class="col-sm-8"><td>(3) Equipment Lock Removed?</td></div>
                                    <div class="col-sm-4"><td><input {{ ($P[$key]->return_status == 'PPg') ? "required" : "" }} type="checkbox" class="form-control-check" name="q1[]"></td></div>
                                </tr>
                                <tr><div class="col-sm-8"><td>(4) Removal of Tag?</td></div>
                                    <div class="col-sm-4"><td><input {{ ($P[$key]->return_status == 'PPg') ? "required" : "" }} type="checkbox" class="form-control-check" name="q2[]"></td></div>
                                </tr>      
                                <tr>
                                    <div class="col-sm-8"><td>(5) Power restored?</td></div>
                                    <div class="col-sm-4"><td><input {{ ($P[$key]->return_status == 'PPg') ? "required" : "" }} type="checkbox" class="form-control-check" name="q3[]"></td></div>
                                </tr> 
                                <tr>
                                    <div class="col-sm-8"><td>(6) Others.</td></div>
                                    <div class="col-sm-4"><td><input  type="checkbox" class="form-control-check" name="q4[]" id="OTHER"></td></div>
                                </tr>
                                <tr>
                                	<td id="show_specify" style="display: none">
					                    <textarea class="form-control"  id="specify_others" name="q5_others[]"></textarea>
				                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
	            @else
            	<div class="form-group row">
	                <label for="form-control-label" class="col-sm-2 col-form-label">Status </label>
	                <div class="col-sm-10">
	                    <input type="text" class="form-control" readonly value="Permit Not Returned">
	                </div>
	            </div>
	            @endif
            </fieldset>
        @endforeach
        <br>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Comment of Power Getting </label>
            <div class="col-sm-10">
                <textarea name="comment_power_cutting" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <input type="submit" name="button" class="btn btn-primary" value="Submit">
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script type="text/javascript">
    $('#voltagelevel').on('change',function(){ 
        var vlevel = $(this).val();
        $('#receiverpower').html("");
        $('#license_numberREC').html("");
        $('#validity_dateREC').html("");
        $.ajax({
            type:'GET',
            url:"{{route('admin.sendvoltagelevelreciver')}}/" + vlevel,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                // console.log(data);
                $("#receiverpower").html('<option value="">--Select--</option>');
                for(var i=0;i<data.length;i++){
                    $("#receiverpower").append("<option value='"+data[i].userid+"'>" + data[i].name +"</option>");
                }
            }
        });
    });
    $('#receiverpower').on('change',function(){ 
        var id = $(this).val();
        $('#license_numberREC').html("");
        $('#validity_dateREC').html("");
        // alert(id);
        $.ajax({
            type:'GET',
            url:"{{route('admin.recevier_electrical_license')}}/" + id,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                for(var i=0;i<data.length;i++){
                    $("#license_numberREC").append('<option value="'+data[i].electrical_license+'" >'+data[i].electrical_license+'</option>');
                    $("#validity_dateREC").append('<option value="'+data[i].validity_date+'" >'+data[i].validity_date+'</option>');
                }
            }
        });
    });

	$('#OTHER').click(function(){
	if($(this).is(':checked'))
	{
	    $('#show_specify').show();
	    $('#specify_others').prop('required', true);
	}
	else
	{
	    $('#specify_others').prop('required', false);
	    $('#show_specify').hide();
	}
	});
</script>
@endsection