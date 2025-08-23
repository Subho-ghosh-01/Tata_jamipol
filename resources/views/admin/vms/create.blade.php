<?php
use App\Division;
use App\Department;
use App\UserLogin;


?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.vendor_esic_details.index')}}">List of Vehicle Gate Pass Management
            System
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Gate Pass Management System</li>
@endsection
@if(Session::get('user_sub_typeSession') == 4)
    return redirect('admin/dashboard');
@else


    @section('content')



        <iframe id="myIframe" src="{{route('vms_ifream.create', ['user_id' => $id])}}" style="border: none; overflow: hidden;"
            width="101%" height="" scrolling="no"></iframe>
        <center>
            <div class="classic-10" id="iframe-loader"></div>
        </center></iframe>

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
        // Parent window script
        const iframe = document.getElementById('myIframe');
        const loader = document.getElementById('iframe-loader');

        let scrollPos = window.pageYOffset || document.documentElement.scrollTop;

        // Handle iframe load
        iframe.onload = function () {
            loader.style.display = 'none';
            iframe.style.display = 'block';
            updateIframeHeight();

            // Restore scroll position after iframe loaded
            window.scrollTo(0, scrollPos);
        };

        // Function to update iframe height based on content
        function updateIframeHeight() {
            try {
                const body = iframe.contentWindow.document.body;
                const html = iframe.contentWindow.document.documentElement;
                const height = Math.max(
                    body.scrollHeight,
                    body.offsetHeight,
                    html.clientHeight,
                    html.scrollHeight,
                    html.offsetHeight
                );
                iframe.style.height = height + 50 + 'px';
            } catch (e) {
                console.warn('Could not access iframe content. Possibly cross-origin.');
            }
        }

        // Listen for messages from iframe
        window.addEventListener('message', function (e) {
            if (e.data.action === 'keepScroll') {
                scrollPos = window.pageYOffset || document.documentElement.scrollTop;
            } else if (e.data.action === 'contentChanged') {
                // Update iframe height when content changes
                updateIframeHeight();
                // Restore scroll position
                window.scrollTo(0, scrollPos);
            }
        });

        // Example usage with a button
        document.getElementById('someButton').addEventListener('click', function () {
            scrollPos = window.pageYOffset || document.documentElement.scrollTop;
            // Reload iframe or other action
            iframe.src = iframe.src; // example to reload iframe
        });

        // Iframe content script (only works if same-origin)
        document.addEventListener('DOMContentLoaded', function () {
            // Watch for DOM changes that might affect height
            const observer = new MutationObserver(function () {
                if (window.parent) {
                    window.parent.postMessage({ action: 'contentChanged' }, '*');
                }
            });

            observer.observe(document.body, {
                attributes: true,
                childList: true,
                subtree: true,
                characterData: true
            });

            // Notify parent when buttons are clicked
            document.addEventListener('click', function (e) {
                if (e.target.tagName === 'BUTTON' && window.parent) {
                    window.parent.postMessage({ action: 'keepScroll' }, '*');
                }
            });
        });

    </script>

    <script>// Whenever a button is clicked inside iframe content:
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function (e) {
                // Notify parent to keep scroll position or prevent scroll jump
                if (window.parent) {
                    window.parent.postMessage({ action: 'keepScroll' }, '*');
                }
            });
        });
    </script>

@endsection