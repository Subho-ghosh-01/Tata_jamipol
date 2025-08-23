<?php
use App\Division;
use App\Department;
use App\UserLogin;


?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.vendor_esic_details.index')}}">List of SILO Tanker</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">SILO Tanker Management System</li>
@endsection
@if(Session::get('user_sub_typeSession') == 4)
    return redirect('admin/dashboard');
@else


    @section('content')

        <div style="width: 100%; overflow: hidden;">
            <iframe id="myIframe" src="{{ url('vendor_silo/create_ifream/' . $id) }}"
                style="border: none; width: 101%; height: 100%; overflow: auto; margin-right: -17px;">
            </iframe>
        </div>
        <center>
            <div class="classic-10" id="iframe-loader"></div>
        </center>

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
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000;
                animation: c10 2s infinite linear;
            }

            .classic-10:before {
                content: "Loading...";
            }

            @keyframes c10 {
                9.09% {
                    text-shadow:
                        calc(0*var(--w)) -10px #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                18.18% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) -10px #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                27.27% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) -10px #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                36.36% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) -10px #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                45.45% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) -10px #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                54.54% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) -10px #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                63.63% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) -10px #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                72.72% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) -10px #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
                }

                81.81% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) -10px #000, calc(-9*var(--w)) 0 #000
                }

                90.90% {
                    text-shadow:
                        calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                        calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) -10px #000
                }
            }
        </style>
    @endsection
@endif
@section('scripts')
    <script>
        const iframe = document.getElementById('myIframe');
        const loader = document.getElementById('iframe-loader');

        // Listen for message from iframe
        window.addEventListener('message', function (e) {
            if (e.data && e.data.type === 'iframeLoaded') {
                loader.style.display = 'none';
                iframe.style.display = 'block';
                iframe.style.height = e.data.height + 'px';
            }
        });
    </script>
@endsection
<script>
    function sendIframeHeight() {
        const height = document.body.scrollHeight;
        parent.postMessage({ type: 'iframeLoaded', height: height }, '*');
    }

    window.addEventListener('load', sendIframeHeight);
    window.addEventListener('resize', sendIframeHeight);
</script>