// ✅ Load Axios with CSRF token support
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ✅ Import Echo and Pusher for Laravel real-time broadcasting
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// ✅ Initialize Echo only if all required variables are defined
try {
    const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
    const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1';
    const pusherHost = import.meta.env.VITE_PUSHER_HOST || `ws-${pusherCluster}.pusher.com`;
    const pusherPort = import.meta.env.VITE_PUSHER_PORT ? parseInt(import.meta.env.VITE_PUSHER_PORT) : 443;
    const scheme = import.meta.env.VITE_PUSHER_SCHEME ?? 'https';

    if (!pusherKey) {
        console.warn("⚠️ VITE_PUSHER_APP_KEY is missing. Real-time features will not work.");
    } else {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            wsHost: pusherHost,
            wsPort: pusherPort,
            wssPort: pusherPort,
            forceTLS: scheme === 'https',
            encrypted: true,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
        });

        console.log("✅ Echo initialized for real-time broadcasting");
    }
} catch (e) {
    console.error("❌ Echo initialization failed:", e);
}
