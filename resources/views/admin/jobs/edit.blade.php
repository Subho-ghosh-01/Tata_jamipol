<?php
use App\Division;
use App\Department;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.job.index')}}">List of Job Category</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Job Category</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
@section('content')
<form action="{{route('admin.job.update',$job->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Job Category Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="job_title" id="" value="{{$job->job_title}}">
            <input type="hidden" name="job_id" value="{{$job->id}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP No</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="swp_number"  id="" value="{{$job->swp_number}}">
        </div>
    </div>
    <!-- <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP File</label>
        <div class="col-sm-10">
            <input type="file" class="form-control-file" name="swp_file"  id="">
        </div>
    </div> -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">High Risk Activity?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="high_risk" @if($job->high_risk == "on") {{"checked"}} @endif value="on">&nbsp; Yes
            <input type="radio"  name="high_risk" @if($job->high_risk == "off") {{"checked"}} @endif value="off">&nbsp;No  
            <!-- <input type="checkbox" class="form-check-label" name="high_risk"  id="high_risk">&nbsp;Yes -->
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Required?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="power_clearance" @if($job->power_clearance == 'on') {{'checked'}} @endif value="on">&nbsp; Yes
            <input type="radio"  name="power_clearance" @if($job->power_clearance == 'off') {{'checked'}} @endif value="off">&nbsp; No 
            <!-- <input type="checkbox" class="form-check-label" name="power_clearance"  id="power_clearance" @if($job->power_clearance == 'on') {{'checked'}} @endif>&nbsp;Yes -->
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space Job?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="confined_space" @if($job->confined_space == 'on') {{'checked'}} @endif value="on">&nbsp; Yes
            <input type="radio"  name="confined_space" @if($job->confined_space == 'off') {{'checked'}} @endif  value="off">&nbsp; No        
         <!-- <input type="checkbox" class="form-control-lable" name="confined_space"  id="confined_space" @if($job->confined_space == 'on') {{'checked'}} @endif>&nbsp;Yes -->
        </div>
    </div>

    <!-- Multiple File Show -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Swp Files</label>
        <div class="col-sm-3">
            <ul>
                @if($swp_files->count() > 0)
                    @foreach($swp_files as $s)
                        <li><a href="{{url('')}}/{{$s->swp_file}}" target="_blank"><img src="{{ URL::to('public/images/pdf_download.png')}}"></a><a href="#" onclick="deleteRecord('{{$s->id}}')">Delete</a></li>
                    @endforeach
                @endif   
            </ul>                  
        </div>
    </div>
	
	<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Swp File Upload</label>
			<div class="col-sm-10">
				<input type="file" name="swp_files[]" multiple>
			</div>
	</div>

    <!-- North -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">North</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="north-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'North')
                            <tr class="north" id="">
                                <input type="hidden" name="n_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                                <td><input type="text" class="form-control" name="north_hazarde[]"   required id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                                <td><input type="text" class="form-control" name="north_precaution[]"   required id="" value="{{$hazared_all[$key]->precaution}}"></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-north" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div> 
    </div>
    <!-- North end -->

    <!-- south -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">South</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="south-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'South')
                            <tr class="south" id="">
                                <input type="hidden" name="s_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                                <td><input type="text" class="form-control" name="south_hazarde[]"   required required id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                                <td><input type="text" class="form-control" name="south_precaution[]" required required   id="" value="{{$hazared_all[$key]->precaution}}"></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-south" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div> 
    </div>
    <!-- South  End -->

    <!-- East -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">East</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="east-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'East')
                            <tr class="east" id="">
                                <input type="hidden" name="e_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                                <td><input type="text" class="form-control" name="east_hazarde[]" required  id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                                <td><input type="text" class="form-control" name="east_precaution[]" required  id=""  value="{{$hazared_all[$key]->precaution}}"></td>
                            </tr>      
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-east" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div>
    </div>
    <!-- East  End -->

    <!-- West -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">West</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="west-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'West')
                        <tr class="west" id="">
                            <input type="hidden" name="w_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                            <td><input type="text" class="form-control" name="west_hazarde[]" requiredrequired  id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                            <td><input type="text" class="form-control" name="west_precaution[]" requiredrequired  id="" value="{{$hazared_all[$key]->precaution}}"></td>
                        </tr>
                        @endif
                    @endforeach                  
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-west" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div>
    </div>
    <!-- Wast  End -->

    <!-- Top -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Top</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="top-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'Top')
                        <tr class="top" id="">
                            <input type="hidden" name="t_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                            <td><input type="text" class="form-control" name="top_hazarde[]"  required id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                            <td><input type="text" class="form-control" name="top_precaution[]"required   id="" value="{{$hazared_all[$key]->precaution}}"></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div> 
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-top" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div>
    </div>
    <!-- Top   End -->

    <!-- Bottom -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Bottom</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="bottom-append">
                    @foreach($hazared_all as $key => $value)
                        @if($hazared_all[$key]->direction == 'Bottom')
                            <tr class="bottom" id="">
                                <input type="hidden" name="b_uni_id[]"  value="{{$hazared_all[$key]->id}}">
                                <td><input type="text" class="form-control" name="bottom_hazard[]"   required id="" value="{{$hazared_all[$key]->hazarde}}"></td>
                                <td><input type="text" class="form-control" name="bottom_precaution[]"  required id="" value="{{$hazared_all[$key]->precaution}}"></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-bottom" class="btn btn-primary btn-sm">+</button>&nbsp;
            <!-- <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button> -->
        </div>
    </div>
    <!--Bottom -->

<!-------------------- Code for Multiple Division,Department,Section Buttom ------------------------->
    <input type="hidden" id="increment" name="increment" value="0">
@if(Session::get('user_sub_typeSession') == 3)
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Choose Multiple Details<span style="color:red;font-size: 22px;">*</span></label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>#</th>
                            <th>Division Name</th>
                            <th>Department Name</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody class="items_list" id="items_list">
                        <?php $count =  1; ?>
                        @foreach($alldivisions as $key => $value)
                        <tr>
                            <td class="sl">{{$count++}}</td>
                            <input type="hidden" name="old_multipledivision_id[]"  value="{{$alldivisions[$key]->id}}"> 
                            <td><select class="form-control rec checkValue" id="division_id" name="division_id[]"  onchange="getDepartment(this,this.value,<?php echo $alldivisions[$key]->id ?>)">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option <?php if($division->id == $alldivisions[$key]->division_id) { echo "selected"; } ?> value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td><select class="form-control rec department_id<?php echo $alldivisions[$key]->id ?>" id="department_id" name="department_id[]">
                                <option value="">Select Department</option>
                                @if($departments->count() > 0)
                                    @foreach($departments as $department)
                                        <option <?php if($department->id == $alldivisions[$key]->department_id) { echo "selected"; } ?> value="{{@$department->id}}">{{@$department->department_name}}</option>
                                    @endforeach
                                @endif
                                </select>
                            </td>
                            <td>
                                <a href="{{ route('admin.deletedivision',$alldivisions[$key]->id) }}" title="DELETE"  class="tab-index"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="sl">{{$count++}}</td>
                            <td><select class="form-control checkValue" id="division_id" name="division_id[]"  onchange="getDepartment(this,this.value,0)">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td><select class="form-control department_id0" id="department_id" name="department_id[]">
                                <option value="">Select Department</option>
                                @if($departments->count() > 0)
                                    @foreach($departments as $department)
                                        <option value="{{@$department->id}}">{{@$department->department_name}}</option>
                                    @endforeach
                                @endif
                                </select>
                            </td>
                            
                            <td>
                                <a href="javascript:void(0)" title="ADD" onclick="addItems(this)" class="tab-index"><i class="fa fa-2x fa-plus-circle"></i></a>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
@else
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Choose Multiple Details<span style="color:red;font-size: 22px;">*</span></label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>#</th>
                            <th>Division Name</th>
                            <th>Department Name</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody class="items_list" id="items_list">
                        @foreach($alldivisions as $key => $value)
                        <tr>
                            <td class="sl">1</td>
                            <input type="hidden" name="old_multipledivision_id[]"  value="{{$alldivisions[$key]->id}}"> 
                            <td><select class="form-control rec checkValue" id="division_id" name="division_id[]"  onchange="getDepartment(this,this.value,<?php echo $alldivisions[$key]->id ?>)">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option <?php if($division->id == @$alldivisions[$key]->division_id) { echo "selected"; } ?> value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td><select class="form-control department_id<?php echo $alldivisions[$key]->id ?>" id="department_id" name="department_id[]">
                                 @if($departments->count() > 0)
                                    @foreach($departments as $department)
                                        <option <?php if($department->id == $alldivisions[$key]->department_id) { echo "selected"; } ?> value="{{@$department->id}}">{{@$department->department_name}}</option>
                                    @endforeach
                                @endif
                                </select>
                            </td>
                            <td>
                                <a href="javascript:void(0)" title="ADD" onclick="addItems(this)" class="tab-index"><i class="fa fa-2x fa-plus-circle"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endif
<!--------------------End Code for Multiple Division,Department,Section Buttom ------------------------->

                    
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary"  onclick="return form_validate()" value="Update Job">
        </div>
    </div>
</form>
@endsection
@endif
@section('scripts')
<script>

    function deleteRecord(id){
        // alert(id);
        let  choice = confirm("Are you sure want to delete the record permanently?");
        if(choice){
            $.ajax({
                type:'GET',
                url:"{{route('admin.deleteSwpFile')}}/" + id,
                contentType:'application/json',
                dataType:"HTML",
                success:function(data){ 
                    alert("Your file is deleted!");
                
                }
            });
        }           
    }

    //Append North Click
    $("#btn-add-north").on("click", function (e) {
            // var count = $(".north").length + 1;
            // console.log(count);
            $('#north-append').append(`<tr class="north" id="">
                    <td style="display:none;"><input type="text" name="n_uni_id[]" value=""></td>
                    <td><input type="text" class="form-control" name="north_hazarde[]"  id="" required></td>
                    <td><input type="text" class="form-control" name="north_precaution[]"  id="" required></td>
                    </tr>`);
    });

    //Append South Click
    $("#btn-add-south").on("click", function (e) {
            // var count = $(".north").length + 1;
            // console.log(count);
            $('#south-append').append(`<tr class="south" id="">
                    <td style="display:none;"><input type="text" name="s_uni_id[]"  value="">
                    <td><input type="text" class="form-control" name="south_hazarde[]" required></td>
                    <td><input type="text" class="form-control" name="south_precaution[]" required></td>
                </tr>`);
    });

    //Append East Click
    $("#btn-add-east").on("click", function (e) {
        // var count = $(".east").length + 1;
        // console.log(count);
        $('#east-append').append(`<tr class="east" id="">
                    <td style="display:none;"><input type="text" name="e_uni_id[]"  value="">
                    <td><input type="text" class="form-control" name="east_hazarde[]"  id="" required></td>
                    <td><input type="text" class="form-control" name="east_precaution[]"  id="" required></td>
                </tr>`);
    });

    //Append West Click
    $("#btn-add-west").on("click", function (e) {
            var count = $(".west").length + 1;
            // console.log(count);
            $('#west-append').append(`<tr class="west" id="">
                        <td style="display:none;"><input type="text" name="w_uni_id[]"  value="">
                        <td><input type="text" class="form-control" name="west_hazarde[]"  id="" required ></td>
                        <td><input type="text" class="form-control" name="west_precaution[]"  id="" required ></td>
            </tr>`);
    });
    
    //Append Top Click
    $("#btn-add-top").on("click", function (e) {
            var count = $(".top").length + 1;
            // console.log(count);
            $('#top-append').append(`<tr class="top" id="">
                        <td style="display:none;"><input type="text" name="t_uni_id[]"  value="">
                        <td><input type="text" class="form-control" name="top_hazarde[]" required></td>
                        <td><input type="text" class="form-control" name="top_precaution[]" required></td>
                    </tr>`);
    });

    //Append Buttom Click
    $("#btn-add-bottom").on("click", function (e) {
        $('#bottom-append').append(`<tr class="bottom" id="">
            <td style="display:none;"><input type="text" name="b_uni_id[]" value="">
            <td><input type="text" class="form-control" name="bottom_hazard[]" required></td>
            <td><input type="text" class="form-control" name="bottom_precaution[]" required></td>
        </tr>`);
    });

    function addItems(th)
    {  
        var incrementjquery = $("#increment").val();
        incrementjquery++;
        $("#increment").val(incrementjquery);
        var datas ='';
        var datas='<tr><td class="sl">1</td>';
                datas +='<td><select class="form-control rec checkValue" id="division_id" name="division_id[]" onchange="getDepartment(this,this.value,'+incrementjquery+')"><option value="">Select Division</option>';
                            @if($divisions->count() > 0)
                                 @foreach($divisions as $division)
                            datas +='<option value="{{@$division->id}}">{{@$division->name}}</option>';
                                 @endforeach
                            @endif
                    datas +='</select></td>';
                datas +='<td><select class="form-control rec department_id'+incrementjquery+'"  name="department_id[]"><option value="">Select Department</option>';
                    datas +='</select></td>';

                    datas +='<td><a href="javascript:void(0)" title="ADD" onclick="addItems(this)"  class="tab-index"><i class="fa fa-plus-circle fa-2x"></i></a></td></tr>';
    
        $(th).find('> i').addClass('fa-plus-circle');
        $(th).find('> i').removeClass('fa-times-circle');
        $(th).attr('onclick','$(this).closest("tr").remove();var incrementjquery = $("#increment").val();incrementjquery--;$("#increment").val(incrementjquery);setSl();');        
        $(th).attr('title','DELETE'); 
        $("#items_list").append(datas);                                  
        setSl();
    }
    function setSl()
    {
        var i=0;
        $(".sl").each(function(e){
            i++;
            $(this).html(i);
        })
    }

    function getDepartment(th,divisionID,unique) {
        // console.log(unique);
        $(".department_id"+unique).html('<option value="">--Select--</option>');
        if(divisionID!="")
        {
            var c= 0;
            $(".checkValue").each(function(e){
                if($(this).val()==divisionID)
                {
                    c++;
                }
            })
            if(c==1)
            {
                if(divisionID)
                {
                    $.ajaxSetup({
                        headers:{
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'GET',
                        url:"{{route('admin.job.department')}}/" + divisionID,
                        contentType:'application/json',
                        dataType:"json",
                        success:function(data){
                            console.log(data);
                            for(var i=0;i<data.length;i++){
                                $(th).closest('tr').find('.department_id'+unique).append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                            }
                        }
                    });
                }else{
                    $('.department_id'+unique).html('<option value="">Select Department</option>');
                }
            }
            else{
                alert("This Division Already Added.");
                $(th).val("");
                $(th).closest('tr').find('.department_id'+unique).val("");
            }
        }
    }
    
</script>
@endsection