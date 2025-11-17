<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Absensi Real-time</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Rajdhani', sans-serif;
            box-sizing: border-box;
        }
        .orbitron { font-family: 'Orbitron', monospace; }

        body {
            background: linear-gradient(135deg, #0c0c1d 0%, #1a1a2e 50%, #16213e 100%);
            position: relative;
            overflow-x: hidden;
            color: #ffffff;
            min-height: 100vh;
        }

        /* ... (Animasi Grid, Partikel tetap sama) ... */
        .cyber-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 120%;
            height: 120%;
            background-image:
                linear-gradient(rgba(0, 255, 255, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridFloat 30s ease-in-out infinite;
            opacity: 0.4;
            z-index: 0;
        }

        @keyframes gridFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(30px, -20px) rotate(0.5deg); }
            50% { transform: translate(-20px, 30px) rotate(-0.3deg); }
            75% { transform: translate(20px, -10px) rotate(0.2deg); }
        }

        /* Partikel mengambang */
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(100, 229, 229, 0.6);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) translateX(0px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) translateX(100px); opacity: 0; }
        }


        /* Neon effects */
        .neon-cyan {
            color: #00ffff;
            text-shadow:
                0 0 5px rgba(0, 255, 255, 0.5),
                0 0 10px rgba(0, 255, 255, 0.4),
                0 0 20px rgba(0, 255, 255, 0.3),
                0 0 40px rgba(0, 255, 255, 0.1);
        }

        .neon-pink {
            color: #ff00ff;
            text-shadow:
                0 0 5px rgba(255, 0, 255, 0.5),
                0 0 10px rgba(255, 0, 255, 0.4),
                0 0 20px rgba(255, 0, 255, 0.2);
        }

        .neon-green {
            color: #00ff00;
            text-shadow:
                0 0 5px rgba(0, 255, 0, 0.5),
                0 0 10px rgba(0, 255, 0, 0.4),
                0 0 20px rgba(0, 255, 0, 0.2);
        }

        /* PERUBAHAN: Warna Neon Kuning untuk Warning */
        .neon-yellow {
            color: #ffff00;
            text-shadow:
                0 0 5px rgba(255, 255, 0, 0.5),
                0 0 10px rgba(255, 255, 0, 0.4),
                0 0 20px rgba(255, 255, 0, 0.2);
        }

        .neon-orange {
            color: #ff6600;
            text-shadow:
                0 0 5px rgba(255, 102, 0, 0.5),
                0 0 10px rgba(255, 102, 0, 0.4),
                0 0 20px rgba(255, 102, 0, 0.2);
        }

        /* ... (Holo Card & Shimmer tetap sama) ... */
        .holo-card {
            background: linear-gradient(135deg, rgba(15, 15, 35, 0.9) 0%, rgba(25, 25, 45, 0.8) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(0, 255, 255, 0.4);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .holo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
            z-index: 1;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* ... (Logo, Header, Scanner tetap sama) ... */
        .school-logo {
            filter: drop-shadow(0 0 10px rgba(0, 255, 255, 0.3));
            transition: all 0.3s ease;
            animation: logoGlow 4s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            0% { filter: drop-shadow(0 0 10px rgba(0, 255, 255, 0.3)); }
            100% { filter: drop-shadow(0 0 20px rgba(0, 255, 255, 0.5)) drop-shadow(0 0 30px rgba(255, 0, 255, 0.2)); }
        }

        .school-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 25px rgba(0, 255, 255, 0.6));
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .school-info {
            text-align: left;
            flex: 1;
            min-width: 0;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            .school-info {
                text-align: center;
            }
            .school-logo {
                width: 80px !important;
                height: 80px !important;
            }
        }

        @media (max-width: 480px) {
            .school-logo {
                width: 60px !important;
                height: 60px !important;
            }
        }

        .scanner-frame {
            position: relative;
            background: linear-gradient(45deg, #000 0%, #111 100%);
            border: 2px solid #00ffff;
            box-shadow:
                0 0 20px rgba(0, 255, 255, 0.3),
                inset 0 0 20px rgba(0, 255, 255, 0.1);
        }

        .scanner-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ff00, transparent);
            top: 50%;
            left: 0;
            animation: scanLine 2s ease-in-out infinite;
        }

        @keyframes scanLine {
            0%, 100% { transform: translateY(-50px); opacity: 0; }
            50% { transform: translateY(50px); opacity: 1; }
        }

        .status-online {
            animation: pulseGlow 2s infinite;
            position: relative;
        }

        .status-online::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: inherit;
            border-radius: inherit;
            transform: translate(-50%, -50%);
            animation: ripple 2s infinite;
        }

        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(0, 255, 0, 0.7),
                           0 0 10px rgba(0, 255, 0, 0.3);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(0, 255, 0, 0),
                           0 0 20px rgba(0, 255, 0, 0.5);
            }
        }

        @keyframes ripple {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(3); opacity: 0; }
        }

        /* ... (Spinner, Popup, Time Display tetap sama) ... */
        .cyber-spinner {
            width: 60px;
            height: 60px;
            position: relative;
        }

        .cyber-spinner::before,
        .cyber-spinner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 3px solid transparent;
            animation: spin 1.5s linear infinite;
        }

        .cyber-spinner::before {
            width: 100%;
            height: 100%;
            border-top-color: #00ffff;
            border-right-color: #00ffff;
        }

        .cyber-spinner::after {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            border-bottom-color: #ff00ff;
            border-left-color: #ff00ff;
            animation-direction: reverse;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .popup-enter-active {
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .popup-leave-active {
            transition: all 0.4s cubic-bezier(0.55, 0.085, 0.68, 0.53);
        }

        .popup-enter-from {
            opacity: 0;
            transform: scale(0.3) rotateX(90deg);
        }

        .popup-leave-to {
            opacity: 0;
            transform: scale(1.1) rotateY(90deg);
        }

        /* Success/Error/Warning states */
        .success-glow {
            box-shadow: 0 0 30px rgba(0, 255, 0, 0.5) !important;
            border-color: #00ff00 !important;
        }

        /* PERUBAHAN: Glow Kuning untuk Warning */
        .warning-glow {
            box-shadow: 0 0 30px rgba(255, 255, 0, 0.5) !important;
            border-color: #ffff00 !important;
        }

        .error-glow {
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.5) !important;
            border-color: #ff0000 !important;
        }

        .time-display {
            background: linear-gradient(45deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
            border: 1px solid rgba(0, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .time-display::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: timeSweep 4s infinite;
        }

        @keyframes timeSweep {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }

        @keyframes progressFill {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    <div class="floating-particles">
        <!-- ... (particle divs) ... -->
    </div>

    <div id="app" @click="focusInput" class="relative z-10 min-h-screen flex items-center justify-center p-4 lg:p-6 cursor-pointer">

        <input type="text" id="rfid_input" ref="rfidInput" v-model="rfidInput" @input="handleRfidInput" class="absolute top-[-9999px] left-[-9999px]">

        <div class="w-full max-w-7xl mx-auto">
            <!-- ... (Header tetap sama) ... -->
            <header class="text-center mb-8 lg:mb-12">
                <div class="holo-card rounded-3xl p-6 lg:p-8 mb-8 relative">
                    <div class="absolute top-4 right-4 flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full status-online"></div>
                        <span class="text-green-400 text-xs lg:text-sm orbitron font-bold">SYSTEM ONLINE</span>
                    </div>

                    <!-- Header dengan Logo dan Informasi Sekolah -->
                    <div class="header-content mb-6">
                        <img src="{{ asset('images/logo-smk-mahardhika.png') }}"
                             alt="Logo SMK Mahardika"
                             class="school-logo w-24 h-24 lg:w-32 lg:h-32 xl:w-36 xl:h-36 object-contain">

                        <div class="school-info">
                            <h1 class="orbitron text-2xl lg:text-4xl xl:text-5xl font-black neon-cyan mb-2">
                                SMK MAHARDHIKA SURABAYA
                            </h1>
                            <h2 class="orbitron text-lg lg:text-2xl xl:text-3xl font-bold neon-pink mb-2">
                                SISTEM ABSENSI DIGITAL
                            </h2>
                            <div class="text-cyan-300 text-sm lg:text-base font-semibold">
                                REAL-TIME ATTENDANCE TRACKING
                            </div>
                        </div>
                    </div>

                    <div class="text-cyan-400 text-xs lg:text-sm opacity-75">
                        SURABAYA DIVISION • AUTOMATED SYSTEM • DEVELOPED BY KYYSOLUTIONS
                    </div>
                </div>
            </header>

            <main class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- ... (Scanner dan Time Display tetap sama) ... -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="holo-card rounded-3xl p-6 lg:p-8 relative">
                        <div class="text-center">
                            <h3 class="orbitron text-xl lg:text-2xl font-bold neon-orange mb-6">RFID SCANNER INTERFACE</h3>
                            <div class="scanner-frame w-full max-w-md h-48 lg:h-60 mx-auto rounded-2xl flex items-center justify-center relative" :class="scannerStatus">
                                <div class="scanner-line" v-if="!isSending"></div>

                                <div class="text-center z-10">
                                    <div v-if="!isSending" class="w-16 h-16 mx-auto mb-4 border-2 border-green-400 rounded-full flex items-center justify-center relative">
                                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>

                                    <div v-if="isSending" class="cyber-spinner mx-auto mb-4"></div>

                                    <p class="orbitron text-lg lg:text-xl font-bold mb-2"
                                       :class="isSending ? 'text-orange-400' : 'text-green-400'"
                                       v-text="isSending ? 'PROCESSING DATA...' : 'SCANNER ACTIVE'"></p>
                                    <p class="text-cyan-300 text-sm" v-text="isSending ? 'Please wait...' : 'Scan your RFID card'"></p>

                                    <div v-if="lastScanTime" class="mt-4 text-xs text-gray-400">
                                        <span>Last scan: </span><span v-text="lastScanTime"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 order-1 lg:order-2">
                    <div class="holo-card time-display rounded-3xl p-6">
                        <div class="text-center">
                            <p class="text-cyan-300 text-sm orbitron mb-2 font-semibold">SYSTEM TIME</p>
                            <div class="orbitron text-2xl lg:text-4xl font-black neon-cyan mb-2" v-text="currentTime"></div>
                            <div class="text-pink-300 text-xs lg:text-sm font-medium" v-text="currentDate"></div>
                        </div>
                    </div>

                    <div class="holo-card rounded-3xl p-6">
                        <div class="text-center">
                            <p class="text-orange-300 text-sm orbitron mb-3 font-semibold">CONNECTION STATUS</p>
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full status-online"></div>
                                <span class="text-green-400 text-sm font-bold">CONNECTED</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- PERUBAHAN BESAR: Popup Notification dengan Logika Terlambat -->
        <transition name="popup">
            <div v-if="showPopup" class="fixed inset-0 bg-black/90 backdrop-blur-lg flex items-center justify-center z-50 p-4">
                <div class="holo-card rounded-3xl p-8 max-w-lg w-full text-center relative transform">

                    <!-- Indikator Ikon (3 Kondisi) -->
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center"
                             :class="{
                                'bg-yellow-500/20 border-2 border-yellow-400': lastAttendance.is_late,
                                'bg-green-500/20 border-2 border-green-400': !lastAttendance.is_late && lastAttendance.status === 'in',
                                'bg-red-500/20 border-2 border-red-400': !lastAttendance.is_late && lastAttendance.status === 'out'
                             }">

                            <!-- Ikon Terlambat (Warning) -->
                            <svg v-if="lastAttendance.is_late" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <!-- Ikon Masuk (Sukses) -->
                            <svg v-else-if="lastAttendance.status === 'in'" class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <!-- Ikon Pulang (Info) -->
                            <svg v-else class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </div>
                    </div>

                    <!-- Header Teks (3 Kondisi) -->
                    <div class="orbitron text-2xl lg:text-3xl font-black mb-6 mt-4"
                         :class="{
                            'neon-yellow': lastAttendance.is_late,
                            'neon-green': !lastAttendance.is_late && lastAttendance.status === 'in',
                            'neon-orange': !lastAttendance.is_late && lastAttendance.status === 'out'
                         }">
                         <span v-if="lastAttendance.is_late">WARNING: TERLAMBAT</span>
                         <span v-else-if="lastAttendance.status === 'in'">ACCESS GRANTED</span>
                         <span v-else>EXIT LOGGED</span>
                    </div>

                    <!-- Info Siswa -->
                    <div class="mb-8">
                        <div class="relative mb-4">
                            <img :src="lastAttendance.photo_url" alt="Student Photo"
                                 class="w-32 h-32 lg:w-40 lg:h-40 mx-auto rounded-full object-cover border-4 shadow-2xl"
                                 :class="{
                                    'border-yellow-400 shadow-yellow-400/50': lastAttendance.is_late,
                                    'border-green-400 shadow-green-400/50': !lastAttendance.is_late && lastAttendance.status === 'in',
                                    'border-red-400 shadow-red-400/50': !lastAttendance.is_late && lastAttendance.status === 'out'
                                 }">

                            <!-- Badge Status (3 Kondisi) -->
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                <div class="px-3 py-1 rounded-full text-xs font-bold text-white"
                                     :class="{
                                        'bg-yellow-500': lastAttendance.is_late,
                                        'bg-green-500': !lastAttendance.is_late && lastAttendance.status === 'in',
                                        'bg-red-500': !lastAttendance.is_late && lastAttendance.status === 'out'
                                     }">
                                     <span v-if="lastAttendance.is_late">TERLAMBAT</span>
                                     <span v-else-if="lastAttendance.status === 'in'">CHECK IN</span>
                                     <span v-else>CHECK OUT</span>
                                </div>
                            </div>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2" v-text="lastAttendance.student_name"></h2>
                        <p class="text-pink-400 font-semibold text-lg" v-text="lastAttendance.class"></p>
                    </div>

                    <!-- Timestamp -->
                    <div class="bg-black/60 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-purple-500/10"></div>
                        <p class="text-cyan-400 text-sm orbitron mb-2 font-semibold relative z-10">TIMESTAMP</p>
                        <p class="orbitron text-3xl lg:text-4xl font-black neon-cyan relative z-10" v-text="lastAttendance.time"></p>
                    </div>

                    <!-- Progress bar -->
                    <div class="mt-6">
                        <div class="w-full bg-gray-700 rounded-full h-1 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-purple-400 rounded-full animate-pulse" style="width: 100%; animation: progressFill 3s ease-in-out;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>

    <script>
        // Blade variables
        const pusherKey = @json(config('broadcasting.connections.pusher.key'));
        const pusherCluster = @json(config('broadcasting.connections.pusher.options.cluster'));
        const apiKey = @json(config('app.device_api_key'));
        const apiUrl = @json(route('api.attendance.receive'));

        const { createApp } = Vue

        createApp({
            data() {
                return {
                    currentTime: '',
                    currentDate: '',
                    showPopup: false,
                    lastAttendance: {}, // Akan berisi 'is_late' => true/false
                    popupTimer: null,
                    rfidInput: '',
                    rfidTimer: null,
                    isSending: false,
                    scannerStatus: '', // success-glow, warning-glow, error-glow
                    lastScanTime: null,
                    connectionRetries: 0,
                    maxRetries: 3
                }
            },
            methods: {
                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }).replace(/\./g, ':');

                    this.currentDate = now.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                },

                listenForEvents() {
                    try {
                        window.Echo = new Echo({
                            broadcaster: 'pusher',
                            key: pusherKey,
                            cluster: pusherCluster,
                            forceTLS: true,
                            disableStats: true,
                            enabledTransports: ['ws', 'wss']
                        });

                        window.Echo.channel('attendance-channel')
                            .listen('.new-attendance-event', (e) => {
                                this.lastAttendance = e.attendanceData;
                                this.showPopup = true;

                                // PERUBAHAN: Set scanner glow berdasarkan status terlambat
                                if (this.lastAttendance.is_late) {
                                    this.scannerStatus = 'warning-glow';
                                } else {
                                    // Sukses untuk masuk atau pulang
                                    this.scannerStatus = 'success-glow';
                                }

                                if (this.popupTimer) clearTimeout(this.popupTimer);

                                // Durasi popup 3.5 detik
                                this.popupTimer = setTimeout(() => {
                                    this.showPopup = false;
                                    this.resetState();
                                }, 3500);
                            })
                            .error((error) => {
                                console.error('Echo error:', error);
                                this.handleConnectionError();
                            });

                    } catch (error) {
                        console.error('Echo setup error:', error);
                        this.handleConnectionError();
                    }
                },

                handleConnectionError() {
                    if (this.connectionRetries < this.maxRetries) {
                        this.connectionRetries++;
                        setTimeout(() => this.listenForEvents(), 3000 * this.connectionRetries);
                    }
                },

                handleRfidInput() {
                    if (this.rfidTimer) clearTimeout(this.rfidTimer);
                    this.rfidTimer = setTimeout(() => this.submitRfid(), 30);
                },

                async submitRfid() {
                    if (this.isSending) return;

                    const identifier = this.rfidInput.trim();
                    if (identifier === '') return;

                    this.isSending = true;
                    this.scannerStatus = '';
                    this.lastScanTime = new Date().toLocaleTimeString('id-ID');

                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 5000);

                    try {
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-API-KEY': apiKey
                            },
                            body: JSON.stringify({
                                identifier: identifier,
                                device_id: 'MONITOR-GERBANG',
                                method: 'rfid',
                                timestamp: Date.now()
                            }),
                            signal: controller.signal
                        });

                        clearTimeout(timeoutId);

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }

                        // PERUBAHAN: Baca 'is_late' dari response JSON
                        const result = await response.json();

                        if (result.is_late) {
                            this.scannerStatus = 'warning-glow';
                        } else {
                            this.scannerStatus = 'success-glow';
                        }

                        // Reset setelah 1 detik (jika popup tidak muncul)
                        setTimeout(() => {
                            if (!this.showPopup) {
                                this.resetState();
                            }
                        }, 1000);

                    } catch (error) {
                        clearTimeout(timeoutId);

                        if (error.name === 'AbortError') {
                            console.error('Request timeout');
                        } else {
                            console.error('Fetch Error:', error);
                        }

                        // Error feedback
                        this.scannerStatus = 'error-glow';

                        // Reset setelah 1.5 detik
                        setTimeout(() => {
                            this.resetState();
                        }, 1500);
                    }
                },

                resetState() {
                    this.rfidInput = '';
                    this.isSending = false;
                    this.scannerStatus = '';
                    this.focusInput();
                },

                focusInput() {
                    this.$nextTick(() => {
                        if (this.$refs.rfidInput) {
                            this.$refs.rfidInput.focus();
                        }
                    });
                }
            },

            mounted() {
                this.updateTime();
                setInterval(this.updateTime, 1000);
                this.listenForEvents();
                this.focusInput();

                document.addEventListener('click', () => {
                    if (!this.showPopup) {
                        this.focusInput();
                    }
                });

                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        this.focusInput();
                    }
                });
            }
        }).mount('#app')
    </script>
</body>
</html>
