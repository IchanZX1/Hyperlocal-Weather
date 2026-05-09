<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ZoomWeather — <?= htmlspecialchars($kota) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            background: #000;
        }

        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            -webkit-font-smoothing: antialiased;
            background: transparent;
        }

        .bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: #000;
        }

        .bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.6);
            transition: opacity 1s ease-in-out;
        }

        .bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0, 0, 0, 0.7));
        }


        .app {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 30px 50px;
            flex-shrink: 0;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            background: linear-gradient(135deg, #fff 0%, #f5c842 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .loc-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .9rem;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 10px 24px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .loc-pill:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            border-color: #f5c842;
            box-shadow: 0 8px 25px rgba(245, 200, 66, 0.2);
        }

        .mid {
            flex: 1;
            display: flex;
            align-items: flex-end;
            padding: 0 50px 50px;
            gap: 60px;
            overflow: hidden;
        }

        .weather-main {
            flex: 0 0 320px;
        }

        .wi-big {
            font-size: 6rem;
            margin-bottom: 5px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        .w-temp {
            font-size: 7.5rem;
            font-weight: 300;
            line-height: 0.9;
            margin: 15px 0;
            letter-spacing: -2px;
        }

        .w-cond {
            font-size: 1.5rem;
            color: #f5c842;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 1px;
        }

        .vdiv {
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.2), transparent);
            align-self: stretch;
            margin: 40px 0;
        }

        .w-detail {
            flex: 0 0 280px;
        }

        .w-detail h4 {
            font-size: 0.75rem;
            letter-spacing: 0.25em;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            margin-bottom: 25px;
        }

        .drow {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .drow:hover {
            border-bottom-color: rgba(245, 200, 66, 0.3);
            transform: translateX(5px);
        }

        .drow .val {
            color: #f5c842;
            font-weight: 600;
        }

        .w-hourly {
            flex: 1;
        }

        .hcols {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 15px;
        }

        .hc {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(5px);
            padding: 20px 10px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .hc:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-10px) scale(1.05);
            border-color: rgba(245, 200, 66, 0.4);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .btm {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(30px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 50px;
        }

        .weekly {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 20px;
        }

        .wd {
            background: rgba(255, 255, 255, 0.03);
            padding: 20px;
            border-radius: 18px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }

        .wd:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255,255,255,0.1);
        }

        /* Premium Modal Style */
        #locModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(15px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(25px);
            padding: 40px;
            border-radius: 30px;
            width: 440px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: scale(1);
            animation: slideUp 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(30px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .modal-content h2 {
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 1.8rem;
            text-align: center;
            background: linear-gradient(to right, #fff, #f5c842);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .form-group select, .form-group input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px 18px;
            border-radius: 14px;
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
            transition: 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(255,255,255,0.5)'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 18px;
        }

        .form-group select:focus, .form-group input:focus {
            outline: none;
            border-color: #f5c842;
            background-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(245, 200, 66, 0.1);
        }

        .form-group select:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-set {
            width: 100%;
            background: linear-gradient(135deg, #f5c842 0%, #e0ac10 100%);
            color: #000;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(245, 200, 66, 0.2);
        }

        .btn-set:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(245, 200, 66, 0.3);
        }

        .btn-set:active {
            transform: translateY(0);
        }

        .btn-cancel {
            width: 100%;
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.4);
            margin-top: 15px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            color: #fff;
        }

        /* Responsiveness & Mobile Optimization */
        @media(max-width: 1024px) {
            .mid { 
                flex-direction: column; 
                align-items: center; 
                overflow-y: auto; 
                padding: 20px 30px; 
                text-align: center; 
                gap: 40px;
            }
            .vdiv { display: none; }
            .hcols { 
                display: grid;
                grid-template-columns: repeat(4, 1fr); 
                gap: 15px;
                width: 100%;
            }
            .weather-main, .w-detail, .w-hourly { flex: none; width: 100%; }
        }

        @media (max-width: 768px) {
            /* Let the page scroll naturally on mobile */
            html, body {
                overflow: auto;
                height: auto;
                background: transparent;
            }
            .app {
                height: auto;
                min-height: 100vh;
                display: block; /* Remove flex to let content flow */
            }
            .bg {
                position: fixed; /* Keep background fixed while scrolling */
            }
            
            /* Topbar adjustments */
            .topbar { 
                padding: 20px 25px; 
                flex-direction: column;
                gap: 15px;
                align-items: center;
                background: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent);
            }
            .logo { font-size: 1.2rem; }
            .topbar > div:last-child { display: none; } /* Hide live clock on mobile to save space */
            
            .loc-pill {
                width: 100%;
                justify-content: center;
                padding: 12px 20px;
            }

            /* Main Weather area */
            .mid { 
                padding: 10px 20px 30px; 
                gap: 30px;
                overflow: visible; /* Let parent handle scroll */
            }
            .w-temp { 
                font-size: 6rem; 
                margin: 10px 0;
            }
            .wi-big { font-size: 5rem; }

            /* Details adjustments */
            .w-detail {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 20px;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .w-detail h4 { text-align: left; margin-bottom: 15px; }
            .drow { padding: 12px 0; }

            /* Horizontal Scroll for Hourly & Weekly */
            .w-hourly { width: 100vw; margin-left: -20px; padding: 0 20px; } /* Break out of padding */
            .hcols { 
                display: flex; 
                overflow-x: auto; 
                scroll-snap-type: x mandatory;
                scrollbar-width: none; 
                padding-bottom: 10px;
                gap: 15px;
            }
            .hcols::-webkit-scrollbar { display: none; }
            .hc { 
                flex: 0 0 90px; 
                scroll-snap-align: center;
                border-radius: 18px;
            }

            /* Bottom section */
            .btm { 
                padding: 25px 20px; 
                background: rgba(10, 10, 10, 0.8);
            }
            .weekly { 
                display: flex; 
                overflow-x: auto; 
                scroll-snap-type: x mandatory;
                scrollbar-width: none; 
                gap: 15px;
                padding-bottom: 10px;
                margin: 0 -5px; /* Adjust for smooth scroll edge */
            }
            .weekly::-webkit-scrollbar { display: none; }
            .wd { 
                flex: 0 0 110px; 
                scroll-snap-align: center;
                padding: 15px;
            }

            /* Modal adjustments */
            .modal-content { 
                width: 92%; 
                padding: 30px 25px; 
                border-radius: 24px;
            }
            .modal-content h2 { font-size: 1.5rem; }
        }
    </style>
</head>
