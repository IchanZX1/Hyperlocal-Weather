<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Synoptic — <?= htmlspecialchars($kota) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        body {
            font-family: 'Barlow', sans-serif;
            background: #121212;
            color: #fff;
        }

        .bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            /* penting supaya selalu di belakang */
            overflow: hidden;
        }

        .bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.4);
        }

        .bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8));
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
            padding: 20px 40px;
            flex-shrink: 0;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #f5c842;
        }

        .loc-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 8px 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .loc-pill:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .mid {
            flex: 1;
            display: flex;
            align-items: flex-end;
            padding: 0 40px 30px;
            gap: 40px;
            overflow: hidden;
        }

        .weather-main {
            flex: 0 0 300px;
        }

        .wi-big {
            font-size: 5rem;
            margin-bottom: 10px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .w-temp {
            font-size: 6rem;
            font-weight: 200;
            line-height: 1;
            margin: 10px 0;
        }

        .w-cond {
            font-size: 1.2rem;
            color: #f5c842;
            font-weight: 500;
        }

        .vdiv {
            width: 1px;
            background: rgba(255, 255, 255, 0.1);
            align-self: stretch;
            margin: 20px 0;
        }

        .w-detail {
            flex: 0 0 250px;
        }

        .w-detail h4 {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .drow {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.9rem;
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
            gap: 10px;
        }

        .hc {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px 10px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }

        .hc:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .btm {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 40px;
        }

        .weekly {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 15px;
        }

        .wd {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Modal Style */
        #locModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 100;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 20px;
            width: 400px;
            border: 1px solid #333;
        }

        .modal-content h2 {
            margin-bottom: 20px;
            font-weight: 300;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            background: #2a2a2a;
            border: 1px solid #444;
            padding: 12px;
            border-radius: 8px;
            color: #fff;
        }

        .btn-set {
            width: 100%;
            background: #f5c842;
            color: #000;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Mobile & Tablet Responsiveness */
        @media(max-width: 1024px) {
            .mid {
                flex-direction: column;
                align-items: flex-start;
                overflow-y: auto;
            }

            .vdiv {
                display: none;
            }

            .hcols,
            .weekly {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {

            html,
            body {
                overflow-y: auto;
                height: auto;
            }

            .app {
                height: auto;
                min-height: 100vh;
            }

            .topbar {
                padding: 15px 20px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .logo {
                font-size: 1rem;
            }

            .loc-pill {
                order: 3;
                width: 100%;
                justify-content: center;
                font-size: 0.8rem;
            }

            .mid {
                padding: 20px;
                gap: 30px;
            }

            .weather-main {
                flex: none;
                width: 100%;
                text-align: center;
            }

            .wi-big {
                font-size: 4rem;
            }

            .w-temp {
                font-size: 5rem;
            }

            .w-detail {
                flex: none;
                width: 100%;
            }

            .w-hourly {
                width: 100%;
            }

            .hcols {
                display: flex;
                overflow-x: auto;
                gap: 12px;
                padding-bottom: 15px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .hcols::-webkit-scrollbar {
                display: none;
            }

            .hc {
                flex: 0 0 85px;
            }

            .btm {
                padding: 20px;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(10px);
            }

            .weekly {
                display: flex;
                overflow-x: auto;
                gap: 12px;
                padding-bottom: 15px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .weekly::-webkit-scrollbar {
                display: none;
            }

            .wd {
                flex: 0 0 95px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
