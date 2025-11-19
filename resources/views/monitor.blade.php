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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 25%, #2d1b4e 50%, #1a1f3a 75%, #0a0e27 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Animated particles background */
        .particles-container {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(100, 200, 255, 0.6);
            border-radius: 50%;
            animation: floatParticle 20s infinite ease-in-out;
        }

        @keyframes floatParticle {
            0%, 100% {
                transform: translate(0, 0) scale(1);
                opacity: 0;
            }
            10%, 90% {
                opacity: 1;
            }
            50% {
                transform: translate(var(--tx), var(--ty)) scale(1.5);
            }
        }

        /* Grid overlay */
        .cyber-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            z-index: 1;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Glowing orbs */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: orbFloat 15s ease-in-out infinite;
            z-index: 1;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.4) 0%, transparent 70%);
            bottom: -250px;
            right: -250px;
            animation-delay: 5s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.3) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
        }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -50px) scale(1.1); }
            66% { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Main container - Full screen */
        #app {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        /* Glass card effect */
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Header - Fixed height */
        .header-section {
            height: 15vh;
            min-height: 100px;
            max-height: 150px;
            display: flex;
            align-items: center;
            padding: 0 3vw;
        }

        /* Main content - Responsive grid */
        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2vw;
            padding: 0 3vw 2vh;
            height: calc(85vh - 100px);
        }

        /* Scanner section */
        .scanner-section {
            grid-column: span 2;
            display: flex;
            flex-direction: column;
        }

        /* Info section */
        .info-section {
            grid-column: span 1;
            display: flex;
            flex-direction: column;
            gap: 2vh;
        }

        /* Responsive adjustments */
        @media (max-width: 1400px) {
            .main-content {
                grid-template-columns: 2fr 1fr;
            }
            .scanner-section {
                grid-column: span 1;
            }
        }

        @media (max-width: 900px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 2vh;
            }
            .scanner-section,
            .info-section {
                grid-column: span 1;
            }
        }

        /* Scanner animation */
        .scanner-container {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 1.5vw;
            border: 2px solid rgba(59, 130, 246, 0.3);
            overflow: hidden;
        }

        .scanner-beam {
            position: absolute;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 1), transparent);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
            animation: scanBeam 2s ease-in-out infinite;
        }

        @keyframes scanBeam {
            0%, 100% {
                top: 0;
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            50% {
                top: 50%;
            }
            90% {
                opacity: 1;
            }
            100% {
                top: 100%;
                opacity: 0;
            }
        }

        /* Robot/Character animation */
        .robot-container {
            position: relative;
            width: 15vw;
            height: 15vw;
            min-width: 120px;
            min-height: 120px;
            max-width: 200px;
            max-height: 200px;
        }

        .robot-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: robotFloat 3s ease-in-out infinite;
            filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.5));
        }

        @keyframes robotFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        /* Status indicators */
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: statusPulse 2s ease-in-out infinite;
        }

        @keyframes statusPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.7;
            }
        }

        /* Time display - Responsive */
        .time-display {
            font-size: clamp(2rem, 5vw, 4rem);
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(96, 165, 250, 0.3);
        }

        .date-display {
            font-size: clamp(0.875rem, 1.5vw, 1.25rem);
        }

        /* Scanner states */
        .scanner-ready {
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.2);
        }

        .scanner-success {
            border-color: rgba(16, 185, 129, 0.8);
            box-shadow: 0 0 50px rgba(16, 185, 129, 0.4);
            animation: successFlash 0.5s ease-out;
        }

        @keyframes successFlash {
            0%, 100% { background: rgba(0, 0, 0, 0.3); }
            50% { background: rgba(16, 185, 129, 0.2); }
        }

        .scanner-warning {
            border-color: rgba(245, 158, 11, 0.8);
            box-shadow: 0 0 50px rgba(245, 158, 11, 0.4);
            animation: warningFlash 0.5s ease-out;
        }

        @keyframes warningFlash {
            0%, 100% { background: rgba(0, 0, 0, 0.3); }
            50% { background: rgba(245, 158, 11, 0.2); }
        }

        .scanner-error {
            border-color: rgba(239, 68, 68, 0.8);
            box-shadow: 0 0 50px rgba(239, 68, 68, 0.4);
            animation: errorFlash 0.5s ease-out;
        }

        @keyframes errorFlash {
            0%, 100% { background: rgba(0, 0, 0, 0.3); }
            50% { background: rgba(239, 68, 68, 0.2); }
        }

        /* Loading spinner */
        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: #60a5fa;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Popup overlay */
        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 2vw;
        }

        .popup-card {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 2vw;
            padding: 3vw;
            max-width: 600px;
            width: 90vw;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: popupBounce 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes popupBounce {
            0% {
                opacity: 0;
                transform: scale(0.7) translateY(30px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .popup-leave-active {
            animation: popupOut 0.3s ease-in;
        }

        @keyframes popupOut {
            to {
                opacity: 0;
                transform: scale(0.9);
            }
        }

        /* Student photo */
        .student-photo {
            width: clamp(120px, 20vw, 200px);
            height: clamp(120px, 20vw, 200px);
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid;
            animation: photoZoom 0.6s ease-out;
        }

        @keyframes photoZoom {
            0% {
                transform: scale(0.5) rotate(-10deg);
                opacity: 0;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        /* Progress bar */
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #60a5fa, #a78bfa, #ec4899);
            animation: progressSlide 3.5s ease-in-out;
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.5);
        }

        @keyframes progressSlide {
            from { width: 0%; }
            to { width: 100%; }
        }

        /* Responsive text sizing */
        .text-responsive-xl {
            font-size: clamp(1.5rem, 3vw, 2.5rem);
        }

        .text-responsive-lg {
            font-size: clamp(1.25rem, 2vw, 2rem);
        }

        .text-responsive-md {
            font-size: clamp(1rem, 1.5vw, 1.5rem);
        }

        .text-responsive-sm {
            font-size: clamp(0.875rem, 1.2vw, 1.125rem);
        }

        .text-responsive-xs {
            font-size: clamp(0.75rem, 1vw, 0.875rem);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>
    <div class="cyber-grid"></div>

    <!-- Particles -->
    <div class="particles-container">
        <div class="particle" style="left: 10%; --tx: 100px; --ty: -200px; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; --tx: -150px; --ty: -250px; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; --tx: 200px; --ty: -180px; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; --tx: -100px; --ty: -220px; animation-delay: 6s;"></div>
        <div class="particle" style="left: 50%; --tx: 150px; --ty: -200px; animation-delay: 8s;"></div>
        <div class="particle" style="left: 60%; --tx: -180px; --ty: -240px; animation-delay: 10s;"></div>
        <div class="particle" style="left: 70%; --tx: 120px; --ty: -190px; animation-delay: 12s;"></div>
        <div class="particle" style="left: 80%; --tx: -160px; --ty: -210px; animation-delay: 14s;"></div>
        <div class="particle" style="left: 90%; --tx: 140px; --ty: -230px; animation-delay: 16s;"></div>
    </div>

    <div id="app" @click="focusInput">

        <!-- Hidden RFID Input -->
        <input type="text"
               id="rfid_input"
               ref="rfidInput"
               v-model="rfidInput"
               @input="handleRfidInput"
               style="position: absolute; left: -9999px; top: -9999px; opacity: 0;">

        <!-- Header -->
        <header class="header-section">
            <div class="glass-card rounded-3xl w-full flex items-center justify-between px-8 py-4">

                <!-- Logo & Title -->
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/logo-smk-mahardhika.png') }}"
                         alt="Logo SMK Mahardika"
                         style="width: clamp(60px, 8vw, 100px); height: clamp(60px, 8vw, 100px);"
                         class="object-contain transition-transform hover:scale-110">

                    <div>
                        <h1 class="text-responsive-lg font-bold text-white mb-1">
                            SMK MAHARDHIKA SURABAYA
                        </h1>
                        <p class="text-responsive-sm text-blue-400 font-semibold">
                            TAPIN - Real-time Attendance System
                        </p>
                    </div>
                </div>

                <!-- Status & Time -->
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="status-dot bg-green-400"></div>
                        <span class="text-responsive-sm font-semibold text-green-400">ONLINE</span>
                    </div>
                    <div class="text-right">
                        <div class="text-responsive-md font-bold text-blue-400" v-text="currentTime"></div>
                        <div class="text-responsive-xs text-gray-400" v-text="currentDate"></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Scanner Section -->
            <div class="scanner-section">
                <div class="glass-card rounded-3xl p-6 h-full flex flex-col">
                    <h2 class="text-responsive-md font-bold text-white mb-4 text-center">
                        🔍 RFID Scanner Interface
                    </h2>

                    <!-- Scanner Display -->
                    <div class="scanner-container"
                         :class="scannerClass">

                        <!-- Scanning beam -->
                        <div class="scanner-beam" v-if="!isSending"></div>

                        <!-- Robot Character -->
                        <div class="robot-container" v-if="!isSending">
                            <img src="{{ asset('images/robot.png') }}"
                                 alt="Robot Scanner"
                                 class="robot-image">
                        </div>

                        <!-- Loading State -->
                        <div v-if="isSending" class="flex flex-col items-center">
                            <div class="spinner mb-6"></div>
                            <p class="text-responsive-md font-bold text-blue-400">Processing...</p>
                            <p class="text-responsive-sm text-gray-400 mt-2">Please wait</p>
                        </div>
                    </div>

                    <!-- Scanner Status Text -->
                    <div class="mt-6 text-center">
                        <p class="text-responsive-md font-bold mb-2"
                           :class="statusTextClass"
                           v-text="statusMessage">
                        </p>
                        <p v-if="lastScanTime" class="text-responsive-xs text-gray-500">
                            Last scan: <span v-text="lastScanTime"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">

                <!-- Time Display -->
                <div class="glass-card rounded-3xl p-6 flex-1 flex flex-col justify-center">
                    <p class="text-responsive-xs font-semibold text-gray-400 text-center mb-3">SYSTEM TIME</p>
                    <div class="text-center">
                        <div class="time-display mb-2" v-text="currentTime"></div>
                        <p class="date-display text-blue-300 font-medium" v-text="currentDate"></p>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="glass-card rounded-3xl p-6">
                    <p class="text-responsive-xs font-semibold text-gray-400 text-center mb-3">CONNECTION</p>
                    <div class="flex items-center justify-center gap-3">
                        <div class="status-dot bg-green-400"></div>
                        <span class="text-responsive-sm font-bold text-green-400">CONNECTED</span>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-responsive-xs text-gray-500">Device ID</p>
                        <p class="text-responsive-sm font-mono text-blue-400 font-semibold">MONITOR-GERBANG</p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="glass-card rounded-3xl p-6">
                    <p class="text-responsive-xs font-semibold text-gray-400 text-center mb-3">SYSTEM INFO</p>
                    <div class="space-y-2 text-center">
                        <p class="text-responsive-xs text-gray-400">Developed by</p>
                        <p class="text-responsive-sm font-bold text-purple-400">KYYSOLUTIONS</p>
                    </div>
                </div>
            </div>

        </main>

        <!-- Popup Notification -->
        <transition name="popup">
            <div v-if="showPopup" class="popup-overlay">
                <div class="popup-card">

                    <!-- Status Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center"
                             :style="popupIconStyle">

                            <!-- Warning Icon -->
                            <svg v-if="lastAttendance.is_late" class="w-12 h-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.664-1.333-2.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>

                            <!-- Check In Icon -->
                            <svg v-else-if="lastAttendance.status === 'in'" class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                            <!-- Check Out Icon -->
                            <svg v-else class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Text -->
                    <h2 class="text-responsive-xl font-bold text-center mb-2"
                        :class="popupTitleClass"
                        v-text="popupTitle">
                    </h2>

                    <!-- Photo -->
                    <div class="flex justify-center my-6">
                        <div class="relative">
                            <img :src="lastAttendance.photo_url"
                                 alt="Student Photo"
                                 class="student-photo"
                                 :style="photoStyle">

                            <!-- Badge -->
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 px-4 py-1 rounded-full text-sm font-bold text-white"
                                 :style="badgeStyle"
                                 v-text="badgeText">
                            </div>
                        </div>
                    </div>

                    <!-- Student Info -->
                    <div class="text-center mb-6">
                        <h3 class="text-responsive-lg font-bold text-white mb-1" v-text="lastAttendance.student_name"></h3>
                        <p class="text-responsive-md text-blue-400 font-semibold" v-text="lastAttendance.class"></p>
                    </div>

                    <!-- Time Info -->
                    <div class="glass-card rounded-2xl p-6 mb-6">
                        <p class="text-responsive-xs text-gray-400 text-center mb-2">TIMESTAMP</p>
                        <p class="text-responsive-xl font-bold text-center bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent"
                           style="-webkit-background-clip: text; -webkit-text-fill-color: transparent;"
                           v-text="lastAttendance.time"></p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
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

        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    currentTime: '',
                    currentDate: '',
                    showPopup: false,
                    lastAttendance: {},
                    popupTimer: null,
                    rfidInput: '',
                    rfidTimer: null,
                    isSending: false,
                    scannerStatus: '',
                    lastScanTime: null,
                    connectionRetries: 0,
                    maxRetries: 3
                }
            },
            computed: {
                scannerClass() {
                    const baseClass = 'scanner-ready';
                    if (this.scannerStatus === 'success') return 'scanner-success';
                    if (this.scannerStatus === 'warning') return 'scanner-warning';
                    if (this.scannerStatus === 'error') return 'scanner-error';
                    return baseClass;
                },
                statusTextClass() {
                    if (this.isSending) return 'text-blue-400';
                    if (this.scannerStatus === 'success') return 'text-green-400';
                    if (this.scannerStatus === 'warning') return 'text-yellow-400';
                    if (this.scannerStatus === 'error') return 'text-red-400';
                    return 'text-blue-400';
                },
                statusMessage() {
                    if (this.isSending) return '⏳ Processing Data...';
                    if (this.scannerStatus === 'success') return '✅ Scan Successful!';
                    if (this.scannerStatus === 'warning') return '⚠️ Late Entry Detected';
                    if (this.scannerStatus === 'error') return '❌ Scan Failed';
                    return '👋 Ready to Scan - Tap Your Card';
                },
                popupIconStyle() {
                    if (this.lastAttendance.is_late) {
                        return 'background: rgba(245, 158, 11, 0.2); border: 3px solid rgb(245, 158, 11);';
                    } else if (this.lastAttendance.status === 'in') {
                        return 'background: rgba(16, 185, 129, 0.2); border: 3px solid rgb(16, 185, 129);';
                    } else {
                        return 'background: rgba(239, 68, 68, 0.2); border: 3px solid rgb(239, 68, 68);';
                    }
                },
                popupTitleClass() {
                    if (this.lastAttendance.is_late) return 'text-yellow-400';
                    if (this.lastAttendance.status === 'in') return 'text-green-400';
                    return 'text-red-400';
                },
                popupTitle() {
                    if (this.lastAttendance.is_late) return '⚠️ TERLAMBAT!';
                    if (this.lastAttendance.status === 'in') return '✅ CHECK IN BERHASIL';
                    return '👋 CHECK OUT BERHASIL';
                },
                photoStyle() {
                    if (this.lastAttendance.is_late) {
                        return 'border-color: rgb(245, 158, 11); box-shadow: 0 0 30px rgba(245, 158, 11, 0.5);';
                    } else if (this.lastAttendance.status === 'in') {
                        return 'border-color: rgb(16, 185, 129); box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);';
                    } else {
                        return 'border-color: rgb(239, 68, 68); box-shadow: 0 0 30px rgba(239, 68, 68, 0.5);';
                    }
                },
                badgeStyle() {
                    if (this.lastAttendance.is_late) return 'background: rgb(245, 158, 11);';
                    if (this.lastAttendance.status === 'in') return 'background: rgb(16, 185, 129);';
                    return 'background: rgb(239, 68, 68);';
                },
                badgeText() {
                    if (this.lastAttendance.is_late) return '⚠️ TERLAMBAT';
                    if (this.lastAttendance.status === 'in') return '✓ CHECK IN';
                    return '→ CHECK OUT';
                }
            },
            methods: {
                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });

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

                                if (this.lastAttendance.is_late) {
                                    this.scannerStatus = 'warning';
                                } else {
                                    this.scannerStatus = 'success';
                                }

                                if (this.popupTimer) clearTimeout(this.popupTimer);

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

                        const result = await response.json();

                        if (result.is_late) {
                            this.scannerStatus = 'warning';
                        } else {
                            this.scannerStatus = 'success';
                        }

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

                        this.scannerStatus = 'error';

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
        }).mount('#app');
    </script>
</body>
</html>
