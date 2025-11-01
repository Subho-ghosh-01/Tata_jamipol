
    const GOOGLE_API_KEY = 'AIzaSyD03PoKXpDLz6V843-0ohNb6nDB8w90Tns'; // Replace with your Google API key
    let recorder, audioBlob, audioURL, audioElement;
        const selectedLang = () => $('#language-selector').val();

    let recognition;
    let currentLang = 'en'; // Default language (can be changed dynamically)

    if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;

    // Detect language automatically by switching language dynamically based on transcription
    recognition.onresult = async function (event) {
        let interimTranscription = '';
    let finalTranscription = '';
    for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcript = event.results[i][0].transcript;
    if (event.results[i].isFinal) {
        finalTranscription = transcript;
    $('#transcription').text(finalTranscription);
    // Automatically detect the language of the final transcription
    detectLanguage(finalTranscription);
                    } else {
        interimTranscription += transcript;
    $('#transcription').text(interimTranscription);
                    }
                }
            };

    recognition.onerror = function (event) {
        console.error('Speech recognition error', event.error);
            };
        }

    // Start recording
    $('#start-btn').click(async function (e) {
        e.preventDefault(); // Prevent page refresh
        $('#transcription').html('');

    const lang = selectedLang();
    if (lang === '' || lang === '--Please choose a language to start speaking--') {
        alert('Please select a language before starting the recording.');
    return;
            }
    currentLang = lang;
    recognition.lang = currentLang; // Set initial language for recognition
    const stream = await navigator.mediaDevices.getUserMedia({audio: true });
    recorder = RecordRTC(stream, {type: 'audio' });
    recorder.startRecording();
        $('#transcription').text('Recording...');

    $('#start-btn').prop('disabled', true);
    $('#stop-btn').prop('disabled', false);

    $('#stop-btn').show(true);
    $('#start-btn').hide(true);
    recognition.start();
        });

    // Stop recording and process audio
    $('#stop-btn').click(async function () {
        $('#stop-btn').hide(true);
    $('#start-btn').show(true);

            await recorder.stopRecording(() => {
        audioBlob = recorder.getBlob();
    $('#transcription').text('Transcribing...');
    setFileInput(audioBlob);
    $('#loader').show();
    sendAudioToGoogleSpeechAPI(audioBlob);
            });
    recognition.stop();
    $('#stop-btn').prop('disabled', true);
    $('#download-audio').show(true);
    $('#play-audio').show(true);
    $('#start-btn').prop('disabled', false);
        });

        // Set Audio in <input type="file">
        function setFileInput(blob) {
            const file = new File([blob], "recording.wav", {type: 'audio/wav' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $('#audio-file-input')[0].files = dataTransfer.files;
        }

        // Download recorded audio
        $('#download-audio').click(function () {
            if (!audioBlob) {
            alert("No audio to download!");
        return;
            }
        const url = URL.createObjectURL(audioBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'recording.wav';
        a.click();
        });

        // Play and pause recorded audio
        $('#play-audio').click(function () {
            if (!audioBlob) {
            alert("No audio to play!");
        return;
            }

        if (!audioElement) {
            audioURL = URL.createObjectURL(audioBlob);
        audioElement = new Audio(audioURL);
        audioElement.play();
        $(this).html('<i class="fas fa-pause-circle btn-icon"></i> Pause Voice');
        audioElement.onended = function () {
            $('#play-audio').html('<i class="fas fa-play-circle btn-icon"></i> Play Voice');
        audioElement = null;
                };
            } else if (audioElement.paused) {
            audioElement.play();
        $(this).html('<i class="fas fa-pause-circle btn-icon"></i> Pause Voice');
            } else {
            audioElement.pause();
        $(this).html('<i class="fas fa-play-circle btn-icon"></i> Play Voice');
            }
        });

        // Send recorded audio to Google Speech-to-Text API
        async function sendAudioToGoogleSpeechAPI(audioBlob) {
            const formData = new FormData();
        formData.append('audio', audioBlob);

        const response = await fetch(`https://speech.googleapis.com/v1p1beta1/speech:recognize?key=${GOOGLE_API_KEY}`, {
            method: 'POST',
        headers: {'Content-Type': 'application/json' },
        body: JSON.stringify({
            config: {
            encoding: 'WEBM_OPUS',
        sampleRateHertz: 48000,
        languageCode: currentLang
                    },
        audio: {content: await blobToBase64(audioBlob) }
                })
            });

        const data = await response.json();
        if (data && data.results && data.results[0]) {
            const transcript = data.results[0].alternatives[0].transcript;

        $('#transcription').text(transcript);
        translateText(transcript);
            } else {
            alert("Could not transcribe the audio.");
        $('#transcription').text("Transcription failed.");
        $('.loader').hide();
            }
        }

        // Detect language using Google Translate API
        async function detectLanguage(text) {
            try {
                const response = await fetch(`https://translation.googleapis.com/language/translate/v2/detect?key=${GOOGLE_API_KEY}`, {
            method: 'POST',
        headers: {'Content-Type': 'application/json' },
        body: JSON.stringify({q: text })
                });

        const data = await response.json();
        const detectedLang = data.data.detections[0][0].language;

        if (detectedLang !== currentLang) {
            currentLang = detectedLang;
        console.log("Detected language: " + currentLang);
                }

        translateText(text);
            } catch (error) {
            console.error('Language detection error:', error);
            }
        }

        // Translate text
        async function translateText(text) {
            try {
                const response = await fetch(`https://translation.googleapis.com/language/translate/v2?key=${GOOGLE_API_KEY}`, {
            method: 'POST',
        headers: {'Content-Type': 'application/json' },
        body: JSON.stringify({
            q: text,
        target: 'en' // Target language can be dynamic or fixed
                    })
                });

        const data = await response.json();
        if (data && data.data && data.data.translations) {
                    const translatedText = data.data.translations[0].translatedText;
        $('.loader').hide();
        $('#translation').val(translatedText);
                } else {
            alert("Could not translate the text.");
        $('.loader').hide();
                }
            } catch (error) {
            console.error('Translation failed:', error);
        alert("An error occurred while translating. Please try again later.");
        $('.loader').hide();
            }
        }

        // Utility: Convert Blob to Base64
        function blobToBase64(blob) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result.split(',')[1]);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
            });
        }
