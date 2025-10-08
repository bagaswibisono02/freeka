import _ from 'lodash';
import axios from 'axios';

window._ = _;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Matikan log console Pusher global (termasuk dari Chatify)
window.Pusher = window.Pusher || {};
window.Pusher.logToConsole = false;

// Atasi fallback log langsung (patch global console)
if (window.console && window.console.log) {
    const originalLog = console.log;
    console.log = function (...args) {
        if (
            args.length &&
            typeof args[0] === 'string' &&
            args[0].includes('Pusher')
        ) {
            return; // Abaikan log yang mengandung "Pusher"
        }
        originalLog.apply(console, args);
    };
}
