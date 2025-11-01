<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered shadow-sm rounded vmsDataTable">
        <thead class="table">
            <tr>
                <th style="white-space: nowrap;">#️⃣</th>
                <th style="white-space: nowrap;">📄 Full Sl.</th>
                <th style="white-space: nowrap;">👤 Owner</th>
                <th style="white-space: nowrap;">🚘 Reg. No</th>
                <th style="white-space: nowrap;">🚗 Vehicle Type</th>
                <th style="white-space: nowrap;">📌 Status</th>
                <th style="white-space: nowrap;">🗓 Applied On</th>
                <th style="white-space: nowrap;">⚙ Action</th>
            </tr>
        </thead>
        <tbody class="text-nowrap">
            @foreach($vmsData as $item)
                @php
                    $isExpired = false;
                    $today = \Carbon\Carbon::today();
                    foreach (['license_valid_to', 'insurance_valid_to', 'puc_valid_to'] as $field) {
                        if ($item->$field && \Carbon\Carbon::parse($item->$field)->lt($today)) {
                            $isExpired = true;
                            break;
                        }
                    }
                @endphp
                <tr @if($isExpired) class="position-relative" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->full_sl }}</td>
                    <td>{{ $item->vehicle_owner_name }}</td>
                    <td>{{ $item->vehicle_registration_no }}</td>
                    <td>{{ ucfirst($item->vehicle_type) }}</td>
                    <td>
                        @if($item->return_status && strtolower($item->return_status) == 'pending_with_safety')
                            ⚠️ Pending for Safety
                        @elseif($item->return_status && strtolower($item->return_status) == 'approve')
                            ✅ Returned
                        @endif
                    </td>


                    <td>{{ $item->created_at }}</td>
                    <td class="text-nowrap">
                        @php
                            // Default action button
                            if ($item->return_status && strtolower($item->return_status) == 'pending_with_safety' && Session::get('clm_role') == 'Safety_dept') {
                                $actionLabel = '⏳ Action';
                                $actionClass = 'btn-warning';
                            } elseif ($item->return_status && strtolower($item->return_status) == 'approve') {
                                $actionLabel = 'Details';
                                $actionClass = 'btn-info';
                            } elseif ($item->return_status && strtolower($item->return_status) == 'reject') {
                                $actionLabel = 'Edit';
                                $actionClass = 'btn-info';
                            } else {
                                $actionLabel = 'Deatils';
                                $actionClass = 'btn-primary';
                            }


                        @endphp

                        {{-- Edit / Follow-up Button --}}
                        <a href="{{ url('/vms/edit_return/' . $item->id) }}"
                            class="btn btn-sm rounded-pill px-3 ms-2 {{ $actionClass }}">
                            {{ $actionLabel }}
                        </a>
                        {{-- Surrender Button --}}
                        @if(strtolower($item->status) == 'approve' && (!$item->return_status) && Session::get('user_idSession') == $item->created_by)
                            <button class="btn btn-danger btn-sm rounded-pill px-3 ms-2" data-bs-toggle="modal"
                                data-bs-target="#returnPassModal" data-id="{{ $item->id }}">
                                🔁 Surrender
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>