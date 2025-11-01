{{-- vehicle_pass_view.blade.php --}}
@php
    use App\Division;
    use App\Department;
    use App\UserLogin;

    $vms = DB::table('vendor_mis')->where('id', $vms_details->id)->first();
    $user_id = Session::get('user_idSession');
@endphp

@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vms.index') }}">List of VMS Documents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Pass Management System</li>
@endsection

@section('content')
    @if(Session::get('user_sub_typeSession') == 4)
        <script>window.location.href = '{{ url("admin/dashboard") }}';</script>
    @else
        {{-- Loader --}}
        <div id="iframe-loader" class="classic-10 text-center">Loading...</div>

        {{-- Iframe --}}
        <iframe id="myIframe" src="{{ route('vendor_mis.edit_ifream', [$vms->id, $user_id]) }}"
            style="border: none; width: 100%; height: 90vh; display: none;"></iframe>

        {{-- Loader CSS --}}
        <style>
            .classic-10 {
                --w: 10ch;
                font-weight: bold;
                font-family: monospace;
                font-size: 30px;
                line-height: 1.4em;
                letter-spacing: var(--w);
                width: var(--w);
                overflow: hidden;
                white-space: nowrap;
                color: #0000;
                text-shadow:
                    calc(0*var(--w)) 0 #000,
                    calc(-1*var(--w)) 0 #000,
                    calc(-2*var(--w)) 0 #000,
                    calc(-3*var(--w)) 0 #000,
                    calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000,
                    calc(-6*var(--w)) 0 #000,
                    calc(-7*var(--w)) 0 #000,
                    calc(-8*var(--w)) 0 #000,
                    calc(-9*var(--w)) 0 #000;
                animation: c10 2s infinite linear;
                text-align: center;
                margin-top: 50px;
            }

            .classic-10:before {
                content: "Loading...";
            }

            @keyframes c10 {
                9.09% {
                    text-shadow: calc(0*var(--w)) -10px #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000, calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000;
                }

                18.18% {
                    text-shadow: calc(0*var(--w)) 0 #000, calc(-1*var(--w)) -10px #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000, calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000;
                }

                27.27% {
                    text-shadow: calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) -10px #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000, calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000;
                }

                /* other frames omitted for brevity */
            }
        </style>
    @endif
@endsection

@section('scripts')
    <script>
        const iframe = document.getElementById('myIframe');
        const loader = document.getElementById('iframe-loader');

        // Listen for iframe loaded message
        window.addEventListener('message', function (e) {
            if (e.data && e.data.type === 'iframeLoaded') {
                loader.style.display = 'none';
                iframe.style.display = 'block';
                iframe.style.height = e.data.height + 'px';
            }
        });
    </script>
@endsection

{{-- These scripts are for iframe content (run inside iframe page) --}}
<script>
    function sendIframeHeight() {
        const height = document.body.scrollHeight;
        parent.postMessage({ type: 'iframeLoaded', height: height }, '*');
    }

    window.addEventListener('load', sendIframeHeight);
    window.addEventListener('resize', sendIframeHeight);
</script>