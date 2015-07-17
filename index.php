<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <title>WEBRTC camera</title>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.red-blue.min.css">
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        #canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: scroll;
        }
            video {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
            video + div {
                position: absolute;
                width: 100%;
                height: 2px;
                border: 1px solid #ddd;
                background: #fff;
                pointer-events: none;
            }
        #log {
            position: fixed;
            top: 0;
            left: 0;
            display: block;
            background: #fff;
            padding: 0 .5em;
            width: 100%;
        }
        #capture {
            position: fixed;
            bottom: 1em;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
<body>
    <div id="canvas"><video></video><div></div></div>
    <span id="log"></span>
    <button id="capture" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
        <i class="material-icons">camera</i>
    </button>

    <script>
        try
        {
            screen.orientation.lock('portrait');
        }
        catch (e)
        {
            alert('화면 고정을 실패했습니다.\n화면 회전 기능을 수동으로 꺼주세요.');
        }

        var camera = document.querySelector('video');
        camera.height = window.innerHeight * 1.5;
        camera.ground = camera.nextElementSibling;
        camera.canvas = camera.parentNode;
        camera.addEventListener('contextmenu', e => {
            e.preventDefault();
        });
        (camera.adjustGround = () => {
            camera.ground.style.top = camera.height / 2 + 'px';
        })();

        navigator.mediaDevices.getUserMedia({
            video: true,
            // Chrome 46.0.2457.0 does not support below constraint
            // video: {
            //     width: 640,
            //     height: 480,
            //     facingMode: 'environment',
            // },
        }).then(stream => {
            camera.src = URL.createObjectURL(stream);
            camera.play();
        }).catch(err => {
            alert(err);
            if (err.name == 'NotFoundError')
            {
                alert('사용가능한 카메라가 없습니다!');
            }
            else
            {
                alert('카메라 접근 권한을 허용해주세요');
            }
        });
        camera.addEventListener('loadedmetadata', e => {
            // Chrome needs EventCallback to play
            camera.play();
        });

        camera.addEventListener('touchstart', e => {
            camera.zoom = undefined;
        });
        camera.addEventListener('touchmove', e => {
            if (e.changedTouches.length == 2)
            {
                var dx = e.changedTouches[0].clientX - e.changedTouches[1].clientX,
                    dy = e.changedTouches[0].clientY - e.changedTouches[1].clientY;
                var zoom = Math.sqrt(dx * dx + dy * dy);
                if (typeof camera.zoom === 'undefined')
                {
                    camera.zoom = zoom;
                    camera.zoomBase = camera.height / window.innerHeight;
                }
                zoom = Math.max(1, Math.min(camera.zoomBase * (1 + (zoom - camera.zoom) / 300), 2));
                camera.height = window.innerHeight * zoom;
                camera.adjustGround();
            }
        });

        var log = document.querySelector('#log');
        window.addEventListener('deviceorientation', e => {
            if (!e.alpha)
            {
                return;
            }
            var height = 160;
            var thetaHorizon = e.beta / 180 * Math.PI;
            var length = Math.tan(thetaHorizon) * height;
            log.innerHTML = [
                'alpha: ' + e.alpha.toFixed(10),
                'beta: ' + e.beta.toFixed(10),
                'gamma: ' + e.gamma.toFixed(10),
                '물제와의 거리: ' + length,
                '현재 배율: ' + camera.height / window.innerHeight,
            ].join('<br>');
        });
        log.addEventListener('click', e => {
            log.remove();
        });

        document.querySelector('#capture').addEventListener('click', e => {
            camera.pause();
            var zoom = camera.height / window.innerHeight;
            var canvas = document.createElement('canvas');
            canvas.width = camera.canvas.clientWidth;
            canvas.height = camera.canvas.clientHeight;
            canvas.getContext('2d').drawImage(camera,
                (camera.clientWidth - canvas.width) / 2 / zoom, camera.canvas.scrollTop / zoom,
                canvas.width / zoom, canvas.height / zoom,
                0, 0, canvas.width, canvas.height);
            canvas.style.position = 'fixed';
            canvas.style.top = canvas.style.left = 0;
            canvas.style.background = '#fff';
            document.querySelector('body').appendChild(canvas);
        });
    </script>
</html>