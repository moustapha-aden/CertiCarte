@php
    $fileInputLabel = $fileInputLabel ?? 'Choisir une photo';
    $previewInitialSrc = $previewInitialSrc ?? null;
    $previewAlt = $previewAlt ?? 'Preview';
    $hasPreview = !empty($previewInitialSrc);
    $photoError = $errors->has('photo');
@endphp

{{-- Photo Section --}}
<div class="pb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
        </svg>
        <span>Photo de Profil</span>
    </h3>

    <div class="flex flex-col sm:flex-row sm:items-start gap-6">
        <div class="shrink-0">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden relative">
                <img id="preview" class="w-full h-full object-cover {{ $hasPreview ? '' : 'hidden' }}"
                    @if($previewInitialSrc) src="{{ $previewInitialSrc }}" @endif
                    alt="{{ $previewAlt }}">
                <svg id="preview-placeholder" class="w-12 h-12 text-gray-400 {{ $hasPreview ? 'hidden' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
        <div class="flex-1 space-y-4">
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $fileInputLabel }}
                </label>
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 {{ $photoError ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0">
                <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB. La webcam nécessite HTTPS ou localhost.</p>
                @if ($photoError)
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('photo') }}</p>
                @endif
            </div>
            <div>
                <button type="button" id="open-webcam-btn"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 13v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2"></path>
                    </svg>
                    Prendre une photo
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Webcam Modal --}}
<div id="webcamModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    onclick="if(event.target === this) closeWebcamModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 flex-shrink-0">
            <h3 class="text-xl font-bold text-gray-900">Prendre une photo</h3>
            <button type="button" onclick="closeWebcamModal()"
                class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        {{-- Body --}}
        <div class="p-4 sm:p-6 space-y-4 overflow-y-auto flex-1">
            <div id="webcam-error" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Caméra indisponible ou accès refusé. Vérifiez que vous utilisez HTTPS ou localhost et que vous avez autorisé l'accès à la caméra.</span>
            </div>
            <div id="webcam-camera-select-wrap" class="hidden">
                <label for="webcam-camera-select" class="block text-sm font-medium text-gray-700 mb-1">Caméra</label>
                <select id="webcam-camera-select" class="block w-full rounded-lg border border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                    <option value="">Chargement...</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Préférez la webcam intégrée pour la photo d'identité.</p>
            </div>
            <div id="webcam-video-wrap" class="relative bg-gray-900 rounded-xl overflow-hidden border-2 border-gray-200 shadow-inner aspect-square">
                <video id="webcamVideo" class="w-full h-full object-cover" style="transform: scaleX(-1);" autoplay playsinline muted></video>
            </div>
            <canvas id="webcamCanvas" class="hidden"></canvas>
        </div>
        {{-- Footer --}}
        <div class="p-4 sm:p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3 flex-shrink-0">
            <button type="button" onclick="closeWebcamModal()"
                class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Fermer
            </button>
            <button type="button" id="capture-btn" onclick="captureFromWebcam()"
                class="px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Capturer
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    let webcamStream = null;

    function stopWebcamStream() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(function(track) { track.stop(); });
            webcamStream = null;
        }
    }

    function closeWebcamModal() {
        stopWebcamStream();
        var modal = document.getElementById('webcamModal');
        var video = document.getElementById('webcamVideo');
        if (modal) modal.classList.add('hidden');
        if (video) {
            video.srcObject = null;
        }
        document.getElementById('webcam-error').classList.add('hidden');
        document.getElementById('webcam-video-wrap').classList.remove('hidden');
        var selectWrap = document.getElementById('webcam-camera-select-wrap');
        if (selectWrap) selectWrap.classList.add('hidden');
    }

    function startWebcamStream(deviceId) {
        var video = document.getElementById('webcamVideo');
        var errorEl = document.getElementById('webcam-error');
        var videoWrap = document.getElementById('webcam-video-wrap');
        var selectEl = document.getElementById('webcam-camera-select');
        var selectWrap = document.getElementById('webcam-camera-select-wrap');

        stopWebcamStream();
        errorEl.classList.add('hidden');
        videoWrap.classList.remove('hidden');

        var integratedKeywords = ['integrated', 'built-in', 'builtin', 'facetime', 'webcam', 'integrated camera', 'caméra intégrée'];
        function isIntegratedLabel(label) {
            if (!label) return false;
            var lower = label.toLowerCase();
            return integratedKeywords.some(function(k) { return lower.indexOf(k) !== -1; });
        }
        function sortVideoInputs(devices) {
            return devices.slice().sort(function(a, b) {
                var aInt = isIntegratedLabel(a.label);
                var bInt = isIntegratedLabel(b.label);
                if (aInt && !bInt) return -1;
                if (!aInt && bInt) return 1;
                return 0;
            });
        }

        var constraints = {
            video: deviceId
                ? { deviceId: { exact: deviceId } }
                : { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
        };

        return navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                webcamStream = stream;
                video.srcObject = stream;
                video.play().catch(function() {});

                if (selectEl && navigator.mediaDevices.enumerateDevices) {
                    navigator.mediaDevices.enumerateDevices().then(function(devices) {
                        var videoInputs = devices.filter(function(d) { return d.kind === 'videoinput'; });
                        videoInputs = sortVideoInputs(videoInputs);
                        var currentDeviceId = stream.getVideoTracks()[0] && stream.getVideoTracks()[0].getSettings().deviceId;
                        var firstIntegratedId = null;
                        for (var i = 0; i < videoInputs.length; i++) {
                            if (isIntegratedLabel(videoInputs[i].label)) {
                                firstIntegratedId = videoInputs[i].deviceId;
                                break;
                            }
                        }
                        var preferredId = currentDeviceId || firstIntegratedId || (videoInputs[0] && videoInputs[0].deviceId);
                        selectEl.innerHTML = '';
                        videoInputs.forEach(function(device) {
                            var opt = document.createElement('option');
                            opt.value = device.deviceId;
                            opt.textContent = device.label || ('Caméra ' + (selectEl.options.length + 1));
                            if (device.deviceId === preferredId) opt.selected = true;
                            selectEl.appendChild(opt);
                        });
                        if (videoInputs.length > 1 && selectWrap) {
                            selectWrap.classList.remove('hidden');
                        }
                    });
                }
                return stream;
            })
            .catch(function(err) {
                console.error('getUserMedia error:', err);
                errorEl.classList.remove('hidden');
                videoWrap.classList.add('hidden');
                throw err;
            });
    }

    function openWebcamModal() {
        var modal = document.getElementById('webcamModal');
        var video = document.getElementById('webcamVideo');
        var errorEl = document.getElementById('webcam-error');
        var videoWrap = document.getElementById('webcam-video-wrap');
        var selectEl = document.getElementById('webcam-camera-select');
        var selectWrap = document.getElementById('webcam-camera-select-wrap');

        errorEl.classList.add('hidden');
        videoWrap.classList.remove('hidden');
        if (selectWrap) selectWrap.classList.add('hidden');
        if (selectEl) selectEl.innerHTML = '<option value="">Chargement...</option>';
        modal.classList.remove('hidden');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            errorEl.classList.remove('hidden');
            videoWrap.classList.add('hidden');
            return;
        }

        startWebcamStream(null).catch(function() {});
    }

    function onWebcamCameraChange() {
        var selectEl = document.getElementById('webcam-camera-select');
        var deviceId = selectEl && selectEl.value ? selectEl.value : null;
        if (deviceId) {
            startWebcamStream(deviceId).catch(function() {});
        }
    }

    function captureFromWebcam() {
        var video = document.getElementById('webcamVideo');
        var canvas = document.getElementById('webcamCanvas');
        var input = document.getElementById('photo');
        var preview = document.getElementById('preview');
        var placeholder = document.getElementById('preview-placeholder');

        if (!video || !video.srcObject || video.readyState < 2) return;

        var w = video.videoWidth;
        var h = video.videoHeight;
        canvas.width = w;
        canvas.height = h;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, w, h);

        canvas.toBlob(function(blob) {
            if (!blob) return;
            var file = new File([blob], 'webcam-capture.jpg', { type: 'image/jpeg' });
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;

            var url = URL.createObjectURL(blob);
            preview.src = url;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            closeWebcamModal();
        }, 'image/jpeg', 0.9);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var photoInput = document.getElementById('photo');
        var preview = document.getElementById('preview');
        var placeholder = document.getElementById('preview-placeholder');
        var openBtn = document.getElementById('open-webcam-btn');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        preview.src = ev.target.result;
                        preview.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (openBtn) {
            openBtn.addEventListener('click', openWebcamModal);
        }

        var cameraSelect = document.getElementById('webcam-camera-select');
        if (cameraSelect) {
            cameraSelect.addEventListener('change', onWebcamCameraChange);
        }

        window.closeWebcamModal = closeWebcamModal;
        window.captureFromWebcam = captureFromWebcam;
    });
})();
</script>
