function initGame(){

    var engine = new Engine;

    (function() {

        const BASENAME = 'FloorIt! - HTML';
        const DEBUG_ENABLED = false;
        const INDETERMINATE_STATUS_STEP_MS = 100;

        var container = document.getElementById('container');
        var canvas = document.getElementById('canvas');
        var statusProgress = document.getElementById('status-progress');
        var statusProgressInner = document.getElementById('status-progress-inner');
        var statusIndeterminate = document.getElementById('status-indeterminate');
        var statusNotice = document.getElementById('status-notice');

        var initializing = true;
        var statusMode = 'hidden';
        var indeterminiateStatusAnimationId = 0;

        setStatusMode('indeterminate');
        engine.setCanvas(canvas);

        function setStatusMode(mode) {

            if (statusMode === mode || !initializing)
                return;
            [statusProgress, statusIndeterminate, statusNotice].forEach(elem => {
                elem.style.display = 'none';
            });
            if (indeterminiateStatusAnimationId !== 0) {
                cancelAnimationFrame(indeterminiateStatusAnimationId);
                indeterminiateStatusAnimationId = 0;
            }
            switch (mode) {
                case 'progress':
                    statusProgress.style.display = 'block';
                    break;
                case 'indeterminate':
                    statusIndeterminate.style.display = 'block';
                    indeterminiateStatusAnimationId = requestAnimationFrame(animateStatusIndeterminate);
                    break;
                case 'notice':
                    statusNotice.style.display = 'block';
                    break;
                case 'hidden':
                    break;
                default:
                    throw new Error("Invalid status mode");
            }
            statusMode = mode;
        }

        function animateStatusIndeterminate(ms) {
            var i = Math.floor(ms / INDETERMINATE_STATUS_STEP_MS % 8);
            if (statusIndeterminate.children[i].style.borderTopColor == '') {
                Array.prototype.slice.call(statusIndeterminate.children).forEach(child => {
                    child.style.borderTopColor = '';
                });
                statusIndeterminate.children[i].style.borderTopColor = '#dfdfdf';
            }
            requestAnimationFrame(animateStatusIndeterminate);
        }

        function setStatusNotice(text) {

            while (statusNotice.lastChild) {
                statusNotice.removeChild(statusNotice.lastChild);
            }
            var lines = text.split('\n');
            lines.forEach((line, index) => {
                statusNotice.appendChild(document.createTextNode(line));
                statusNotice.appendChild(document.createElement('br'));
            });
        };

        engine.setProgressFunc((current, total) => {

            if (total > 0) {
                statusProgressInner.style.width = current/total * 100 + '%';
                setStatusMode('progress');
                if (current === total) {
                    // wait for progress bar animation
                    setTimeout(() => {
                        setStatusMode('indeterminate');
                    }, 500);
                }
            } else {
                setStatusMode('indeterminate');
            }
        });

        if (DEBUG_ENABLED) {
            var outputRoot = document.getElementById("output-panel");
            var outputScroll = document.getElementById("output-scroll");
            var OUTPUT_MSG_COUNT_MAX = 400;

            document.getElementById('output-clear').addEventListener('click', () => {
                while (outputScroll.firstChild) {
                    outputScroll.firstChild.remove();
                }
            });

            outputRoot.style.display = 'block';

            function print(text) {
                while (outputScroll.childElementCount >= OUTPUT_MSG_COUNT_MAX) {
                    outputScroll.firstChild.remove();
                }
                var msg = document.createElement("div");
                if (String.prototype.trim.call(text).startsWith("**ERROR**")) {
                    msg.style.color = "#d44";
                } else if (String.prototype.trim.call(text).startsWith("**WARNING**")) {
                    msg.style.color = "#ccc000";
                } else if (String.prototype.trim.call(text).startsWith("**SCRIPT ERROR**")) {
                    msg.style.color = "#c6d";
                }
                msg.textContent = text;
                var scrollToBottom = outputScroll.scrollHeight - (outputScroll.clientHeight + outputScroll.scrollTop) < 10;
                outputScroll.appendChild(msg);
                if (scrollToBottom) {
                    outputScroll.scrollTop = outputScroll.scrollHeight;
                }
            };

            function printError(text) {
                if (!String.prototype.trim.call(text).startsWith('**ERROR**: ')) {
                    text = '**ERROR**: ' + text;
                }
                print(text);
            }

            engine.setStdoutFunc(text => {
                print(text);
                console.log(text);
            });

            engine.setStderrFunc(text => {
                printError(text);
                console.warn(text);
            });
        }

        engine.startGame(BASENAME + '.pck').then(() => {
            setStatusMode('hidden');
            initializing = false;
        }, err => {
            if (DEBUG_ENABLED) {
                printError(err.message);
                console.warn(err);
            }
            setStatusNotice(err.message);
            setStatusMode('notice');
            initializing = false;
        });
    })();
}