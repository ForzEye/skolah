@extends('layouts.app')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --ink: #101a35;
    --navy: #17264b;
    --blue: #3158e7;
    --blue-dark: #234add;
    --purple: #8057ce;
    --teal: #169d91;
    --muted: #6b7790;
    --line: #e5eaf3;
    --soft: #f5f7fb;
    --surface: #fff;
    --shadow: 0 12px 36px rgba(18,38,77,.055);
}

.corporate-courses {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    color: var(--ink);
    background: #fff;
    -webkit-font-smoothing: antialiased;
}
.corporate-courses * { box-sizing: border-box; }
.corporate-courses a { text-decoration: none; color: inherit; }
.corporate-courses svg { flex: none; }
.corporate-courses .container { max-width: 1200px; width: calc(100% - 48px); margin-inline: auto; }

/* HERO / PAGE INTRO */
.page-intro {
    background: radial-gradient(ellipse at 76% 15%, #e8efff 0%, transparent 43%), linear-gradient(118deg, #f8faff 0%, #fff 59%, #f8f9ff 100%);
    border-bottom: 1px solid var(--line);
    overflow: hidden;
}
.breadcrumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #8691a7;
    font-size: 11px;
    padding-top: 18px;
    font-weight: 600;
}
.breadcrumbs svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; }
.breadcrumbs a:hover { color: var(--blue); }
.breadcrumbs strong { color: #455573; }
.intro-grid {
    display: grid;
    grid-template-columns: 1.17fr .83fr;
    gap: 50px;
    align-items: center;
    padding: 16px 0 28px;
}
.eyebrow {
    font-size: 11px;
    color: var(--blue);
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 9px;
}
.eyebrow:before {
    content: "";
    height: 7px;
    width: 7px;
    background: var(--teal);
    border-radius: 50%;
}
.intro-copy h1 {
    font-family: 'Manrope', sans-serif;
    font-size: clamp(34px, 3.6vw, 48px);
    line-height: 1.18;
    letter-spacing: -.055em;
    font-weight: 800;
    margin: 0 0 14px;
    max-width: 670px;
    color: var(--ink);
}
.intro-copy h1 .accent { color: var(--blue); }
.intro-copy > p {
    max-width: 580px;
    color: #66758d;
    line-height: 1.8;
    font-size: 14px;
    margin: 0;
}
.intro-actions {
    margin-top: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.btn-primary-corp {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    border-radius: 10px;
    padding: 13px 20px;
    font-weight: 800;
    font-size: 13px;
    background: var(--blue);
    color: white !important;
    transition: transform .2s, background .2s, box-shadow .2s;
    box-shadow: 0 8px 20px rgba(49,88,231,.18);
    border: none;
    cursor: pointer;
}
.btn-primary-corp:hover {
    background: var(--blue-dark);
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(49,88,231,.25);
}
.btn-primary-corp svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
.intro-inline {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #6e7891;
    font-weight: 600;
}
.intro-inline svg { width: 16px; height: 16px; color: var(--teal); stroke: currentColor; fill: none; stroke-width: 2.2; }

/* VISUAL BOARD */
.intro-visual {
    position: relative;
    isolation: isolate;
    min-height: 290px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.intro-visual:before {
    position: absolute;
    content: "";
    border-radius: 50%;
    height: 360px;
    width: 360px;
    right: 4px;
    top: -28px;
    border: 1px solid #d8e4fb;
    box-shadow: 0 0 0 36px rgba(225,232,255,.35), 0 0 0 75px rgba(237,241,255,.45);
    z-index: -1;
    pointer-events: none;
}
.visual-board {
    background: #fff;
    border: 1px solid #e1e8f4;
    box-shadow: 0 25px 58px rgba(23,43,91,.12);
    border-radius: 20px;
    padding: 18px;
    width: min(100%, 390px);
    transform: rotate(-2deg);
}
.visual-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #edf1f8;
    padding-bottom: 14px;
    margin-bottom: 14px;
}
.visual-head span { font-size: 11px; font-weight: 800; color: var(--ink); }
.visual-head small { font-size: 9px; color: #8491a8; font-weight: 700; }
.visual-head .dots { display: flex; gap: 4px; }
.visual-head .dots i { width: 6px; height: 6px; border-radius: 50%; background: #d6deed; }
.visual-head .dots i:first-child { background: #8aa1f9; }
.visual-lines {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.mini-course {
    background: #f9faff;
    border: 1px solid #e9ecf5;
    border-radius: 12px;
    overflow: hidden;
}
.mini-art {
    height: 76px;
    background: linear-gradient(135deg, #1a376e, #356bef);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #c6d6ff;
    position: relative;
}
.mini-art:after {
    content: "";
    position: absolute;
    width: 85px;
    height: 85px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.25);
    right: -20px;
    top: -33px;
}
.mini-art svg { width: 34px; height: 34px; stroke: currentColor; fill: none; stroke-width: 1.4; }
.mini-course:nth-child(2) .mini-art { background: linear-gradient(135deg, #3d2b74, #8360cd); color: #ebdcff; }
.mini-course:nth-child(3) .mini-art { background: linear-gradient(135deg, #0e5558, #22a699); color: #c9f7e9; }
.mini-text { padding: 10px 8px; }
.mini-text span {
    display: block;
    height: 5px;
    border-radius: 5px;
    background: #d8dfec;
    margin-bottom: 6px;
    width: 90%;
}
.mini-text span:last-child { width: 55%; background: #e9edf6; margin: 0; }
.visual-footer {
    margin-top: 13px;
    background: #f2f6ff;
    border-radius: 9px;
    padding: 10px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    font-weight: 700;
    color: #60728e;
}
.visual-footer b { color: var(--blue); font-weight: 800; letter-spacing: .02em; }
.float-stat {
    position: absolute;
    bottom: -6px;
    left: -20px;
    border: 1px solid #e3eaf7;
    border-radius: 12px;
    padding: 12px 15px;
    box-shadow: 0 14px 35px rgba(20,37,71,.10);
    background: #fff;
    display: flex;
    gap: 11px;
    align-items: center;
    transform: rotate(3deg);
    z-index: 2;
}
.float-stat .stat-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: #e8f8f2;
    color: #169a81;
}
.float-stat .stat-icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.9; }
.float-stat b { display: block; font-size: 12px; font-weight: 800; color: var(--ink); }
.float-stat span { display: block; font-size: 10px; color: #748199; margin-top: 2px; }

/* AUDIENCE STRIP */
.audience-strip { background: #fff; border-bottom: 1px solid #ecf0f6; }
.audience-row {
    display: flex;
    align-items: center;
    gap: 30px;
    justify-content: space-between;
    min-height: 56px;
}
.audience-row > p {
    font-size: 12px;
    font-weight: 800;
    margin: 0;
    color: #606f85;
    white-space: nowrap;
}
.audience-list { display: flex; gap: 24px; justify-content: flex-end; flex-wrap: wrap; }
.audience-list span {
    display: flex;
    gap: 8px;
    align-items: center;
    color: #516078;
    font-weight: 700;
    font-size: 12px;
}
.audience-list svg {
    height: 17px;
    width: 17px;
    color: var(--blue);
    background: #edf2ff;
    border-radius: 6px;
    padding: 3px;
    box-sizing: content-box;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.8;
}

/* FLASH SALE BANNER */
.flashsale-banner {
    background: linear-gradient(135deg, #101c3d 0%, #1e3a8a 50%, #4338ca 100%);
    border-radius: 20px;
    padding: 24px 28px;
    margin-bottom: 34px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(30,58,138,.16);
    border: 1px solid rgba(255,255,255,.12);
}
.flashsale-banner:after {
    content: "";
    position: absolute;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    right: -70px;
    top: -120px;
    background: radial-gradient(circle, rgba(255,255,255,.15), transparent 70%);
    pointer-events: none;
}

/* CATALOG SECTION */
.catalog-section {
    padding: 32px 0 72px;
    background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
}
.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 22px;
    margin-bottom: 20px;
}
.section-heading h2 {
    font-family: 'Manrope', sans-serif;
    letter-spacing: -.045em;
    font-size: 30px;
    line-height: 1.22;
    margin: 9px 0 7px;
    color: var(--ink);
    font-weight: 800;
}
.section-heading p { color: var(--muted); font-size: 13px; margin: 0; line-height: 1.7; }

/* CATALOG LAYOUT */
.catalog-layout {
    display: grid;
    grid-template-columns: 256px minmax(0, 1fr);
    gap: 30px;
    align-items: start;
}

/* FILTER PANEL */
.filter-panel {
    position: sticky;
    top: 96px;
    border: 1px solid #e5eaf2;
    background: #fff;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 7px 18px rgba(34,54,90,.025);
}
.filter-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.filter-title h3 {
    font: 800 15px 'Manrope', sans-serif;
    margin: 0;
    letter-spacing: -.02em;
    color: var(--ink);
}
.filter-title .reset-link {
    font-size: 11px;
    font-weight: 800;
    color: var(--blue);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}
.filter-title .reset-link:hover { text-decoration: underline; }
.field-label {
    display: block;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: #617087;
    margin-bottom: 9px;
}
.search-field {
    display: flex;
    align-items: center;
    background: #f8faff;
    border: 1px solid #e2e8f2;
    gap: 9px;
    border-radius: 9px;
    padding: 0 12px;
    height: 42px;
    transition: border-color .2s, box-shadow .2s;
}
.search-field:focus-within {
    outline: 2px solid #d6e0ff;
    border-color: #aebff8;
}
.search-field svg { width: 15px; height: 15px; color: #9aa6b9; stroke: currentColor; fill: none; stroke-width: 2; }
.search-field input {
    background: transparent;
    border: 0;
    outline: 0;
    width: 100%;
    min-width: 0;
    font-size: 12px;
    color: var(--ink);
}
.filter-group {
    padding: 19px 0;
    border-bottom: 1px solid #edf0f6;
}
.filter-group:first-of-type { padding-top: 0; }
.filter-group:last-of-type { border: 0; padding-bottom: 0; }
.filter-group-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0 0 11px;
}
.filter-group-title h4 {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .09em;
    font-weight: 800;
    color: #627087;
    margin: 0;
}
.filter-group-title svg { width: 13px; height: 13px; color: #9ea8bb; stroke: currentColor; fill: none; stroke-width: 2; }
.filter-scrollable {
    max-height: 210px;
    overflow-y: auto;
    padding-right: 4px;
}
.filter-scrollable::-webkit-scrollbar { width: 4px; }
.filter-scrollable::-webkit-scrollbar-thumb { background: #dce3ed; border-radius: 4px; }
.filter-option {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    margin: 9px 0;
    font-size: 12px;
    color: #526078;
    cursor: pointer;
    transition: color .15s;
}
.filter-option input {
    margin: 0;
    appearance: none;
    width: 15px;
    height: 15px;
    border: 1.5px solid #b8c4d6;
    border-radius: 50%;
    background: #fff;
    position: relative;
    flex: none;
    cursor: pointer;
    transition: all .2s;
}
.filter-option input:checked {
    border-color: var(--blue);
    border-width: 5px;
}
.filter-option span { flex: 1; }
.filter-option small { color: #a0adc1; font-size: 10px; font-weight: 700; }
.filter-option:hover span { color: var(--blue); }
.filter-option:has(input:checked) span { font-weight: 800; color: var(--ink); }
.filter-footer {
    margin-top: 22px;
    display: grid;
    gap: 8px;
}
.filter-footer .btn { width: 100%; }
.filter-mobile-top, .filter-open, .filter-scrim { display: none; }

/* COURSE AREA */
.course-area { min-width: 0; }
.category-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.category-pill {
    border: 1px solid #e5eaf3;
    background: white;
    border-radius: 8px;
    padding: 8px 13px;
    color: #67748c;
    font-size: 11px;
    font-weight: 800;
    transition: all .2s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.category-pill:hover {
    border-color: #b7c6f7;
    color: var(--blue);
    background: #f8faff;
}
.category-pill.active {
    background: #eaf0ff;
    border-color: #d4e0ff;
    color: #2d56da;
    box-shadow: 0 2px 6px rgba(45,86,218,.08);
}
.toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e8edf5;
    margin-bottom: 22px;
}
.toolbar-left { font-size: 12px; color: #748096; }
.toolbar-left strong { color: var(--ink); font-weight: 800; }
.sort-area {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sort-area label { font-size: 11px; color: #7a869b; font-weight: 700; }
.sort-area select {
    padding: 8px 28px 8px 11px;
    border: 1px solid #e0e5ee;
    border-radius: 8px;
    color: #516078;
    background: #fff;
    font-size: 12px;
    font-weight: 700;
    outline: none;
    cursor: pointer;
}
.view-switch {
    display: flex;
    align-items: center;
    gap: 3px;
    background: #f1f4fa;
    border: 1px solid #e7ebf2;
    border-radius: 8px;
    padding: 3px;
}
.view-switch button {
    border: 0;
    width: 28px;
    height: 28px;
    background: transparent;
    color: #929cb0;
    border-radius: 6px;
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: all .15s;
}
.view-switch button.active {
    background: #fff;
    color: var(--blue);
    box-shadow: 0 2px 5px rgba(22,39,70,.075);
}
.view-switch svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
.active-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: -6px 0 20px;
}
.active-filters a {
    border: 1px solid #dce5fb;
    background: #f5f8ff;
    color: #4d67a6;
    border-radius: 6px;
    padding: 5px 9px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
}
.active-filters a:hover {
    background: #e9effd;
    color: var(--blue);
}
.active-filters a svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2.2; }

/* COURSE GRID */
.course-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
}
.course-card {
    min-width: 0;
    border: 1px solid #e6ebf3;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(20,37,70,.025);
    transition: transform .22s, box-shadow .22s, border-color .22s;
    display: flex;
    flex-direction: column;
}
.course-card:hover {
    transform: translateY(-4px);
    border-color: #cfdaef;
    box-shadow: 0 18px 32px rgba(22,42,81,.085);
}

/* MEDIA */
.course-media {
    height: 160px;
    position: relative;
    overflow: hidden;
    background: #132c62;
}
.course-media img.course-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .35s ease;
}
.course-card:hover .course-media img.course-thumb {
    transform: scale(1.04);
}
.course-media:before {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255,255,255,.20);
    border-radius: 50%;
    right: -45px;
    top: -80px;
    box-shadow: 0 0 0 35px rgba(255,255,255,.04), 0 0 0 70px rgba(255,255,255,.025);
    pointer-events: none;
    z-index: 2;
}
.course-media.export { background: linear-gradient(115deg, #0c2047, #16448a 70%, #3d82d1); }
.course-media.konglomerat { background: linear-gradient(115deg, #17203c, #3e366c 73%, #8864ca); }
.course-media.koperasi { background: linear-gradient(115deg, #0b414e, #096b78 75%, #26a7a5); }
.course-media.general { background: linear-gradient(115deg, #122a59, #2950b8 70%, #6857cb); }
.media-label {
    position: absolute;
    top: 13px;
    left: 14px;
    color: rgba(255,255,255,.93);
    font-size: 8.5px;
    font-weight: 800;
    letter-spacing: .15em;
    z-index: 3;
    text-shadow: 0 1px 4px rgba(0,0,0,.4);
}
.media-number {
    font: 800 56px 'Manrope', sans-serif;
    letter-spacing: -.09em;
    color: rgba(255,255,255,.07);
    position: absolute;
    right: 14px;
    bottom: 5px;
    z-index: 1;
    pointer-events: none;
}
.art-svg {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}
.art-svg * { vector-effect: non-scaling-stroke; }
.media-theme {
    position: absolute;
    left: 14px;
    bottom: 14px;
    color: white;
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    line-height: 1.25;
    letter-spacing: -.04em;
    font-size: 15px;
    max-width: 165px;
    text-shadow: 0 1px 6px rgba(0,0,0,.35);
    z-index: 3;
}
.media-theme small {
    display: block;
    text-transform: uppercase;
    letter-spacing: .10em;
    font: 700 8px 'DM Sans', sans-serif;
    margin-bottom: 5px;
    color: #a7d1ff;
}
.level-tag {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 5px 8px;
    border-radius: 6px;
    background: rgba(255,255,255,.95);
    color: #355070;
    font-size: 9px;
    font-weight: 800;
    line-height: 1;
    z-index: 3;
    backdrop-filter: blur(4px);
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
}
.featured-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 5px 8px;
    border-radius: 6px;
    background: #f59e0b;
    color: #fff;
    font-size: 9px;
    font-weight: 800;
    line-height: 1;
    z-index: 4;
}

/* CARD CONTENT */
.course-content {
    padding: 16px 16px 0;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.course-category {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    align-items: center;
    margin-bottom: 8px;
    color: #536eab;
    font-size: 10px;
    letter-spacing: .075em;
    font-weight: 800;
    text-transform: uppercase;
}
.course-category .type {
    font-size: 10px;
    color: #a2adbf;
    letter-spacing: 0;
    font-weight: 600;
    text-transform: none;
}
.course-card h3 {
    font-size: 14px;
    line-height: 1.5;
    min-height: 42px;
    margin: 0 0 9px;
    letter-spacing: -.022em;
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: var(--ink);
}
.course-card h3 a:hover { color: var(--blue); }
.instructor {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #7a8699;
    font-size: 11px;
    margin-bottom: 14px;
}
.instructor .avatar {
    width: 24px;
    height: 24px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    font-size: 9px;
    font-weight: 800;
    color: var(--blue);
    background: #eaf0ff;
    overflow: hidden;
    flex: none;
}
.instructor .avatar img { width: 100%; height: 100%; object-fit: cover; }
.course-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #8b97aa;
    font-size: 10.5px;
    margin-top: auto;
    padding-bottom: 15px;
}
.course-meta svg { width: 13px; height: 13px; color: #899bbd; stroke: currentColor; fill: none; stroke-width: 2; }
.course-meta .sep { height: 3px; width: 3px; background: #adb6c6; border-radius: 50%; }

/* CARD BOTTOM */
.course-card-bottom {
    border-top: 1px solid #eef1f6;
    padding: 13px 16px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #fff;
}
.course-price {
    font-family: 'Manrope', sans-serif;
    font-weight: 800;
    font-size: 15px;
    letter-spacing: -.035em;
    color: #2348cc;
}
.course-price.free { color: #168a73; }
.course-price small {
    font-family: 'DM Sans', sans-serif;
    display: block;
    font-size: 10px;
    color: #99a4b8;
    font-weight: 500;
    letter-spacing: 0;
    margin-bottom: 2px;
}
.course-price .strike {
    font-size: 11px;
    color: #9da8ba;
    text-decoration: line-through;
    font-weight: 500;
    margin-left: 5px;
}
.course-actions { display: flex; align-items: center; gap: 6px; }
.cart-quick-btn {
    border: 1px solid #e5eaf3;
    background: white;
    border-radius: 9px;
    display: grid;
    place-items: center;
    width: 35px;
    height: 35px;
    color: #516078;
    cursor: pointer;
    transition: all .2s;
}
.cart-quick-btn:hover {
    border-color: #b7c8ff;
    color: var(--blue);
    background: #eff3ff;
}
.cart-quick-btn.loading { opacity: .6; pointer-events: none; }
.cart-quick-btn.success { border-color: #86efac; background: #f0fdf4; color: #16a34a; }
.cart-quick-btn svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
.toast {
    position: fixed;
    bottom: 28px;
    right: 24px;
    z-index: 9999;
    background: #101a35;
    color: #fff;
    box-shadow: 0 16px 42px rgba(10,24,46,.25);
    padding: 13px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    max-width: min(90vw, 380px);
    display: flex;
    align-items: center;
    gap: 10px;
    opacity: 0;
    transform: translateY(16px);
    pointer-events: none;
    transition: opacity .25s ease, transform .25s ease;
    border: 1px solid rgba(255,255,255,.1);
}
.toast.show {
    opacity: 1;
    transform: translateY(0);
}
.toast.toast-success { border-left: 4px solid #16a34a; }
.toast.toast-info { border-left: 4px solid #3158e7; }
.toast.toast-error { border-left: 4px solid #ef4444; }
.detail-btn {
    background: #ecf1ff;
    color: var(--blue);
    border: 0;
    border-radius: 9px;
    padding: 10px 14px;
    font-weight: 800;
    font-size: 11px;
    transition: background .2s;
    cursor: pointer;
    display: inline-block;
    text-align: center;
}
.detail-btn:hover { background: #dfe8ff; }

/* LIST VIEW MODE */
.course-grid.list-view {
    grid-template-columns: 1fr;
    gap: 14px;
}
.list-view .course-card {
    flex-direction: row;
    min-height: 185px;
}
.list-view .course-media {
    height: auto;
    min-height: 180px;
    width: 240px;
    flex: none;
}
.list-view .course-content {
    padding: 18px 20px;
    justify-content: center;
}
.list-view .course-card h3 {
    min-height: 0;
    font-size: 16px;
    max-width: 520px;
}
.list-view .course-card-bottom {
    width: 180px;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    border-left: 1px solid #eef1f6;
    border-top: 0;
    padding: 18px;
    flex: none;
}
.list-view .course-meta { margin-top: 8px; padding-bottom: 0; }
.list-view .instructor { margin-bottom: 0; }
.list-view .course-actions { width: 100%; }
.list-view .detail-btn { flex: 1; text-align: center; }

/* PAGINATION */
.pagination-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 32px;
    gap: 16px;
    padding-top: 20px;
    border-top: 1px solid #edf1f7;
}
.pagination-row > span { font-size: 12px; color: #8a96aa; font-weight: 600; }
.corp-pagination { display: flex; gap: 5px; align-items: center; }
.corp-pagination a, .corp-pagination span {
    height: 36px;
    min-width: 36px;
    padding: 0 10px;
    border: 1px solid #e6eaf2;
    border-radius: 8px;
    background: white;
    color: #526079;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
}
.corp-pagination a:hover {
    border-color: #aebff7;
    color: var(--blue);
    background: #f8faff;
}
.corp-pagination .active span, .corp-pagination span[aria-current="page"] {
    border-color: var(--blue);
    background: var(--blue);
    color: white;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 70px 20px;
    background: white;
    border: 1px dashed #cdd8e8;
    border-radius: 16px;
    grid-column: 1 / -1;
}
.empty-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 18px;
    border-radius: 16px;
    background: #edf2ff;
    color: var(--blue);
    display: grid;
    place-items: center;
}
.empty-icon svg { width: 28px; height: 28px; stroke: currentColor; fill: none; stroke-width: 2; }
.empty-state h3 { font-family: 'Manrope', sans-serif; margin: 0 0 8px; font-size: 19px; color: var(--ink); }
.empty-state p { color: var(--muted); line-height: 1.7; max-width: 420px; margin: 0 auto 20px; font-size: 13px; }

/* NEWSLETTER */
.newsletter-corp {
    background: linear-gradient(120deg, #1e388d, #3158e7 60%, #684fca);
    color: white;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}
.newsletter-corp:before {
    content: "";
    position: absolute;
    width: 400px;
    height: 400px;
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 50%;
    right: -100px;
    top: -180px;
    box-shadow: 0 0 0 60px rgba(255,255,255,.035), 0 0 0 130px rgba(255,255,255,.025);
    pointer-events: none;
}
.newsletter-corp-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 40px;
    position: relative;
    z-index: 1;
}
.newsletter-corp h2 {
    font: 800 clamp(24px, 2.5vw, 32px) 'Manrope', sans-serif;
    line-height: 1.3;
    letter-spacing: -.04em;
    margin: 10px 0;
}
.newsletter-corp p { font-size: 13px; line-height: 1.7; color: #e1e8ff; max-width: 480px; margin: 0; }
.newsletter-corp .eyebrow { color: #ccd9ff; }
.newsletter-corp .eyebrow:before { background: #5eead4; }
.newsletter-form-corp { display: flex; gap: 8px; width: min(100%, 420px); }
.newsletter-form-corp input {
    flex: 1;
    min-width: 0;
    background: #fff;
    border: 0;
    border-radius: 10px;
    padding: 14px 16px;
    color: var(--ink);
    outline: none;
    font-size: 12px;
}
.newsletter-form-corp button {
    border: 0;
    border-radius: 10px;
    padding: 0 20px;
    font-weight: 800;
    background: #101b37;
    color: white;
    font-size: 12px;
    cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
}
.newsletter-form-corp button:hover { background: #080f24; }

/* RESPONSIVE */
@media(max-width: 1120px) {
    .intro-grid { gap: 30px; }
    .course-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .catalog-layout { grid-template-columns: 240px minmax(0, 1fr); gap: 24px; }
    .intro-visual:before { right: -60px; }
}
@media(max-width: 860px) {
    .corporate-courses .container { width: calc(100% - 32px); }
    .intro-grid { grid-template-columns: 1fr; padding-top: 20px; gap: 32px; }
    .intro-copy h1 { font-size: 38px; }
    .intro-visual { min-height: 240px; max-width: 440px; width: 100%; margin: auto; }
    .visual-board { width: 360px; }
    .breadcrumbs { padding-top: 22px; }
    .audience-row { display: block; padding: 20px 0; }
    .audience-list { justify-content: flex-start; margin-top: 12px; gap: 12px 20px; }
    .catalog-section { padding-top: 38px; }
    .catalog-layout { display: block; }
    .filter-panel {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        bottom: 0;
        width: min(340px, 86vw);
        border-radius: 0;
        overflow-y: auto;
        padding: 24px;
        transform: translateX(-110%);
        transition: transform .25s ease;
        box-shadow: 0 16px 36px rgba(0,0,0,.25);
    }
    .filter-panel.open { transform: translateX(0); }
    .filter-mobile-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid #e6ebf4;
    }
    .filter-mobile-top h3 { margin: 0; font: 800 16px 'Manrope', sans-serif; color: var(--ink); }
    .filter-mobile-top button { border: 0; background: transparent; cursor: pointer; color: #64748b; }
    .filter-mobile-top svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2; }
    .filter-scrim.show {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(4,14,34,.5);
        backdrop-filter: blur(3px);
        z-index: 999;
    }
    .filter-open {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 13px;
        border: 1px solid #dce4f3;
        border-radius: 8px;
        background: #fff;
        color: #2c4580;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }
    .filter-open svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; }
    .newsletter-corp-content { flex-direction: column; align-items: flex-start; }
    .newsletter-form-corp { width: 100%; max-width: 440px; }
}
@media(max-width: 580px) {
    .course-grid { grid-template-columns: 1fr; gap: 14px; }
    .course-media { height: 170px; }
    .toolbar { flex-direction: column; align-items: stretch; gap: 12px; }
    .sort-area { justify-content: space-between; }
    .pagination-row { flex-direction: column; align-items: center; gap: 12px; }
    .newsletter-form-corp { flex-direction: column; }
    .newsletter-form-corp button { padding: 13px; }
}
</style>
@endpush

@section('content')

{{-- SVG ICONS SYMBOLS --}}
<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" aria-hidden="true" style="position:absolute;overflow:hidden">
<defs>
<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7.5"/><path d="m16.5 16.5 5 5"/></symbol>
<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h16m-7-7 7 7-7 7"/></symbol>
<symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
<symbol id="i-home" viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v11H3zM9 21v-7h6v7"/></symbol>
<symbol id="i-book" viewBox="0 0 24 24"><path d="M4 4.5C7 3 10 3.3 12 5v15c-2-1.7-5-2-8-.5zm16 0c-3-1.5-6-1.2-8 .5v15c2-1.7 5-2 8-.5z"/></symbol>
<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></symbol>
<symbol id="i-list" viewBox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h1M3 12h1M3 18h1"/></symbol>
<symbol id="i-sliders" viewBox="0 0 24 24"><path d="M4 7h16M4 17h16M9 4v6M16 14v6"/></symbol>
<symbol id="i-x" viewBox="0 0 24 24"><path d="M5 5 19 19M19 5 5 19"/></symbol>
<symbol id="i-check" viewBox="0 0 24 24"><path d="m4 12 5 5L20 6"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M17 5a3 3 0 0 1 0 6M20 21v-3a6 6 0 0 0-3-5.2"/></symbol>
<symbol id="i-building" viewBox="0 0 24 24"><path d="M4 21V8l7-4v17M11 21V11l9-4v14M3 21h19M7 11h1M7 15h1M15 13h1M15 17h1"/></symbol>
<symbol id="i-briefcase" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 13h18"/></symbol>
<symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l4 2"/></symbol>
<symbol id="i-cap" viewBox="0 0 24 24"><path d="m2 9 10-5 10 5-10 5zM6 11v5c3 3 9 3 12 0v-5M22 10v6"/></symbol>
<symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c-5 5-5 13 0 18M12 3c5 5 5 13 0 18"/></symbol>
<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
</defs>
</svg>

<div class="corporate-courses">

    {{-- HERO / PAGE INTRO --}}
    <section class="page-intro" aria-labelledby="pageTitle">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ url('/') }}" aria-label="Beranda"><svg><use href="#i-home"/></svg></a>
                <svg><use href="#i-chevron"/></svg>
                <strong>Kursus Online</strong>
            </div>

            <div class="intro-grid">
                <div class="intro-copy">
                    <h1 id="pageTitle">Kompetensi baru untuk <span class="accent">kemajuan karier &amp; bisnis.</span></h1>
                    <p>Temukan pembelajaran praktis untuk individu, profesional, dan perusahaan. Eksplorasi program keahlian, pengembangan bisnis, serta sertifikasi kompetensi dalam satu platform terintegrasi.</p>
                    <div class="intro-actions">
                        <a href="#katalog" class="btn-primary-corp">Eksplor Kursus <svg><use href="#i-arrow"/></svg></a>
                        <span class="intro-inline"><svg><use href="#i-check"/></svg> Fleksibel dan relevan dengan praktik industri</span>
                    </div>
                </div>

                <div class="intro-visual" aria-hidden="true">
                    <div class="visual-board">
                        <div class="visual-head">
                            <span>Explore Learning</span>
                            <div class="dots"><i></i><i></i><i></i></div>
                            <small>Catalog / {{ date('Y') }}</small>
                        </div>
                        <div class="visual-lines">
                            <div class="mini-course">
                                <div class="mini-art"><svg><use href="#i-globe"/></svg></div>
                                <div class="mini-text"><span></span><span></span></div>
                            </div>
                            <div class="mini-course">
                                <div class="mini-art"><svg><use href="#i-building"/></svg></div>
                                <div class="mini-text"><span></span><span></span></div>
                            </div>
                            <div class="mini-course">
                                <div class="mini-art"><svg><use href="#i-users"/></svg></div>
                                <div class="mini-text"><span></span><span></span></div>
                            </div>
                        </div>
                        <div class="visual-footer">
                            <span>Program pengembangan kompetensi</span>
                            <b>EXPLORE →</b>
                        </div>
                    </div>
                    <div class="float-stat">
                        <div class="stat-icon"><svg><use href="#i-book"/></svg></div>
                        <div>
                            <b>{{ number_format($totalCount) }}+ kursus</b>
                            <span>{{ count($categories) }} kategori di katalog</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- AUDIENCE STRIP --}}
    <section class="audience-strip">
        <div class="container audience-row">
            <p>Pembelajaran untuk berbagai kebutuhan</p>
            <div class="audience-list">
                <span><svg><use href="#i-cap"/></svg> Pelajar &amp; Profesional</span>
                <span><svg><use href="#i-briefcase"/></svg> Perusahaan &amp; Tim</span>
                <span><svg><use href="#i-building"/></svg> UMKM &amp; Organisasi</span>
                <span><svg><use href="#i-globe"/></svg> Wirausaha &amp; Praktisi</span>
            </div>
        </div>
    </section>

    {{-- FLASH SALE BANNER (JIKA ADA FLASH SALE AKTIF) --}}
    @if($activeFlashSale && $activeFlashSale->isRunning && $activeFlashSale->items->count())
    <section class="container" style="padding-top: 36px;"
             x-data="{ 
                endTime: new Date('{{ $activeFlashSale->end_at->toIso8601String() }}').getTime(),
                days: 0, hours: 0, minutes: 0, seconds: 0,
                updateTimer() {
                    let now = new Date().getTime();
                    let distance = this.endTime - now;
                    if (distance < 0) {
                        this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                        return;
                    }
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                }
             }" 
             x-init="updateTimer(); setInterval(() => updateTimer(), 1000)">
        <div class="flashsale-banner">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px; position:relative; z-index:2;">
                <div>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.2); padding:4px 10px; border-radius:100px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; margin-bottom:8px;">
                        ⚡ PROMO FLASH SALE
                    </span>
                    <h3 style="margin:0 0 6px; font:800 22px 'Manrope',sans-serif; letter-spacing:-.03em;">{{ $activeFlashSale->title }}</h3>
                    <p style="margin:0; font-size:12px; opacity:.85; max-width:500px;">{{ $activeFlashSale->description ?: 'Dapatkan potongan harga spesial sebelum waktu habis!' }}</p>
                </div>
                <div style="display:flex; gap:10px; align-items:center;">
                    <div style="background:rgba(255,255,255,.12); border-radius:10px; padding:8px 12px; text-align:center; min-width:50px;">
                        <span style="font-weight:800; font-size:18px; display:block;" x-text="String(hours).padStart(2, '0')">00</span>
                        <small style="font-size:8px; opacity:.75; text-transform:uppercase;">Jam</small>
                    </div>
                    <span style="font-weight:800;">:</span>
                    <div style="background:rgba(255,255,255,.12); border-radius:10px; padding:8px 12px; text-align:center; min-width:50px;">
                        <span style="font-weight:800; font-size:18px; display:block;" x-text="String(minutes).padStart(2, '0')">00</span>
                        <small style="font-size:8px; opacity:.75; text-transform:uppercase;">Menit</small>
                    </div>
                    <span style="font-weight:800;">:</span>
                    <div style="background:rgba(255,255,255,.12); border-radius:10px; padding:8px 12px; text-align:center; min-width:50px;">
                        <span style="font-weight:800; font-size:18px; display:block;" x-text="String(seconds).padStart(2, '0')">00</span>
                        <small style="font-size:8px; opacity:.75; text-transform:uppercase;">Detik</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- MAIN CATALOG SECTION --}}
    <section id="katalog" class="catalog-section" aria-labelledby="catalogTitle">
        <div class="container">
            <div class="section-heading">
                <div>
                    <div class="eyebrow">JELAJAHI PROGRAM</div>
                    <h2 id="catalogTitle">Katalog Kursus Online</h2>
                    <p>Pilih keterampilan yang sesuai dengan tujuan dan kebutuhan pengembangan Anda.</p>
                </div>
            </div>

            <div class="catalog-layout">
                {{-- Scrim for Mobile Filter Drawer --}}
                <div id="filterScrim" class="filter-scrim" onclick="document.getElementById('filterPanel').classList.remove('open'); this.classList.remove('show');"></div>

                {{-- FILTER ASIDE --}}
                <aside class="filter-panel" id="filterPanel" aria-label="Filter kursus">
                    <form id="courseFilterForm" method="GET" action="{{ route('courses.index') }}">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="filter-mobile-top">
                            <h3>Filter Kursus</h3>
                            <button type="button" id="mobileFilterClose" onclick="document.getElementById('filterPanel').classList.remove('open'); document.getElementById('filterScrim').classList.remove('show');" aria-label="Tutup filter">
                                <svg><use href="#i-x"/></svg>
                            </button>
                        </div>

                        <div class="filter-title">
                            <h3>Filter Kursus</h3>
                            @if(request()->hasAny(['q', 'category', 'level', 'price', 'rating']))
                                <a href="{{ route('courses.index') }}" class="reset-link">Reset semua</a>
                            @endif
                        </div>

                        {{-- Search Filter --}}
                        <div class="filter-group">
                            <label class="field-label" for="courseSearch">Cari kursus</label>
                            <div class="search-field">
                                <svg><use href="#i-search"/></svg>
                                <input id="courseSearch" type="search" name="q" value="{{ request('q', '') }}" placeholder="Nama kursus atau topik..." autocomplete="off">
                            </div>
                        </div>

                        {{-- Category Filter --}}
                        <div class="filter-group">
                            <div class="filter-group-title">
                                <h4>Kategori</h4>
                                <svg><use href="#i-chevron"/></svg>
                            </div>
                            <div class="filter-scrollable">
                                <label class="filter-option">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>Semua Kategori</span>
                                    <small>{{ number_format($totalCount) }}</small>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="filter-option">
                                        <input type="radio" name="category" value="{{ $cat['slug'] }}" {{ request('category') === $cat['slug'] ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span>{{ $cat['name'] }}</span>
                                        <small>{{ $cat['courses_count'] }}</small>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Level Filter --}}
                        <div class="filter-group">
                            <div class="filter-group-title">
                                <h4>Level pembelajaran</h4>
                                <svg><use href="#i-chevron"/></svg>
                            </div>
                            <label class="filter-option">
                                <input type="radio" name="level" value="" {{ !request('level') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Semua Level</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="level" value="beginner" {{ request('level') === 'beginner' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Pemula</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="level" value="intermediate" {{ request('level') === 'intermediate' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Menengah</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="level" value="advanced" {{ request('level') === 'advanced' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Mahir</span>
                            </label>
                        </div>

                        {{-- Price Filter --}}
                        <div class="filter-group">
                            <div class="filter-group-title">
                                <h4>Biaya kursus</h4>
                                <svg><use href="#i-chevron"/></svg>
                            </div>
                            <label class="filter-option">
                                <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Semua Harga</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="free" {{ request('price') === 'free' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Gratis</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="paid" {{ request('price') === 'paid' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Berbayar</span>
                            </label>
                        </div>

                        {{-- Rating Filter --}}
                        <div class="filter-group">
                            <div class="filter-group-title">
                                <h4>Rating Minimum</h4>
                                <svg><use href="#i-chevron"/></svg>
                            </div>
                            <label class="filter-option">
                                <input type="radio" name="rating" value="" {{ !request('rating') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Semua Rating</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="rating" value="4.5" {{ request('rating') === '4.5' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>⭐ 4.5 ke atas</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="rating" value="4" {{ request('rating') === '4' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>⭐ 4.0 ke atas</span>
                            </label>
                        </div>

                        <div class="filter-footer">
                            <button type="submit" class="btn-primary-corp" style="width:100%; border-radius:10px;">
                                Terapkan Filter <svg><use href="#i-arrow"/></svg>
                            </button>
                            @if(request()->hasAny(['q', 'category', 'level', 'price', 'rating']))
                                <a href="{{ route('courses.index') }}" class="detail-btn" style="text-align:center; padding:11px; background:#f4f6fb; color:#5b6882;">
                                    Hapus Semua Filter
                                </a>
                            @endif
                        </div>
                    </form>
                </aside>

                {{-- COURSE CONTENT AREA --}}
                <div class="course-area">
                    {{-- Quick Category Pills --}}
                    <div class="category-pills" id="categoryPills" aria-label="Pilih kategori kursus">
                        <a href="{{ route('courses.index', request()->except('category', 'page')) }}" 
                           class="category-pill {{ !request('category') ? 'active' : '' }}">
                           Semua Kursus
                        </a>
                        @foreach(collect($categories)->take(8) as $cat)
                            <a href="{{ route('courses.index', array_merge(request()->except('page'), ['category' => $cat['slug']])) }}" 
                               class="category-pill {{ request('category') === $cat['slug'] ? 'active' : '' }}">
                               {{ $cat['name'] }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Toolbar: count, mobile trigger, sorting, view switch --}}
                    <div class="toolbar">
                        <div class="toolbar-left" id="resultText" aria-live="polite">
                            Menampilkan <strong>{{ number_format($courses->total()) }}</strong> kursus
                            @if(request('q'))
                                untuk kata kunci "<strong>{{ request('q') }}</strong>"
                            @endif
                        </div>

                        <div class="sort-area">
                            <button type="button" class="filter-open" onclick="document.getElementById('filterPanel').classList.add('open'); document.getElementById('filterScrim').classList.add('show');">
                                <svg><use href="#i-sliders"/></svg> Filter
                            </button>

                            <label for="courseSort">Urutkan</label>
                            <form id="sortForm" method="GET" action="{{ route('courses.index') }}" style="margin:0;">
                                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                                @if(request('level'))<input type="hidden" name="level" value="{{ request('level') }}">@endif
                                @if(request('price'))<input type="hidden" name="price" value="{{ request('price') }}">@endif
                                @if(request('rating'))<input type="hidden" name="rating" value="{{ request('rating') }}">@endif
                                <select id="courseSort" name="sort" onchange="this.form.submit()" aria-label="Urutkan kursus">
                                    <option value="popular" {{ request('sort', 'popular') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                </select>
                            </form>

                            <div class="view-switch" role="group" aria-label="Mode tampilan">
                                <button type="button" id="gridMode" class="active" aria-label="Tampilan grid" aria-pressed="true" onclick="toggleCourseView('grid')">
                                    <svg><use href="#i-grid"/></svg>
                                </button>
                                <button type="button" id="listMode" aria-label="Tampilan daftar" aria-pressed="false" onclick="toggleCourseView('list')">
                                    <svg><use href="#i-list"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Active Filter Chips --}}
                    @if(request()->hasAny(['q', 'category', 'level', 'price', 'rating']))
                        <div class="active-filters">
                            @if(request('q'))
                                <a href="{{ request()->fullUrlWithoutQuery(['q', 'page']) }}">Cari: {{ request('q') }} <svg><use href="#i-x"/></svg></a>
                            @endif
                            @if(request('category'))
                                @php
                                    $curCat = collect($categories)->firstWhere('slug', request('category'));
                                    $catName = $curCat ? $curCat['name'] : request('category');
                                @endphp
                                <a href="{{ request()->fullUrlWithoutQuery(['category', 'page']) }}">Kategori: {{ $catName }} <svg><use href="#i-x"/></svg></a>
                            @endif
                            @if(request('level'))
                                <a href="{{ request()->fullUrlWithoutQuery(['level', 'page']) }}">Level: {{ ['beginner'=>'Pemula', 'intermediate'=>'Menengah', 'advanced'=>'Mahir'][request('level')] ?? request('level') }} <svg><use href="#i-x"/></svg></a>
                            @endif
                            @if(request('price'))
                                <a href="{{ request()->fullUrlWithoutQuery(['price', 'page']) }}">{{ request('price') === 'free' ? 'Gratis' : 'Berbayar' }} <svg><use href="#i-x"/></svg></a>
                            @endif
                            @if(request('rating'))
                                <a href="{{ request()->fullUrlWithoutQuery(['rating', 'page']) }}">⭐ ≥ {{ request('rating') }} <svg><use href="#i-x"/></svg></a>
                            @endif
                        </div>
                    @endif

                    {{-- COURSE GRID --}}
                    @if($courses->count() > 0)
                        <div class="course-grid" id="courseGrid">
                            @php
                                $themeGradients = ['export', 'konglomerat', 'koperasi', 'general'];
                            @endphp
                            @foreach($courses as $idx => $course)
                                @php
                                    $catName = $course->category->name ?? 'Program';
                                    $themeClass = $themeGradients[$idx % count($themeGradients)];
                                    $instructorName = $course->instructor->name ?? 'Skolah Expert';
                                    $instructorInitials = strtoupper(substr($instructorName, 0, 2));
                                    $levelLabel = ['beginner' => 'Pemula', 'intermediate' => 'Menengah', 'advanced' => 'Mahir'][$course->level] ?? 'Semua Level';
                                    $isFree = ($course->price == 0);
                                    $hasDiscount = (($course->discount_price ?? 0) > 0);
                                @endphp
                                <article class="course-card" aria-label="{{ $course->title }}">
                                    {{-- Media Header --}}
                                    <div class="course-media {{ $themeClass }}">
                                        @if($course->thumbnail)
                                            <img src="{{ storageUrl($course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumb" loading="lazy">
                                        @else
                                            <span class="media-label">SKOLAH.COM / LEARNING</span>
                                            <span class="media-number">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <div class="media-theme">
                                                <small>{{ $catName }}</small>
                                                {{ Str::limit($course->title, 28) }}
                                            </div>
                                        @endif

                                        <span class="level-tag">{{ $levelLabel }}</span>
                                        @if($course->is_featured)
                                            <span class="featured-tag">⭐ Unggulan</span>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="course-content">
                                        <div class="course-category">
                                            <span>{{ $catName }}</span>
                                            <span class="type">E-learning</span>
                                        </div>

                                        <h3>
                                            <a href="{{ route('courses.show', $course->slug) }}" title="{{ $course->title }}">
                                                {{ $course->title }}
                                            </a>
                                        </h3>

                                        <div class="instructor">
                                            <span class="avatar">
                                                @if(isset($course->instructor) && !empty($course->instructor->avatar))
                                                    <img src="{{ avatarUrl($course->instructor) }}" alt="{{ $instructorName }}">
                                                @else
                                                    {{ $instructorInitials }}
                                                @endif
                                            </span>
                                            <span>oleh <strong>{{ $instructorName }}</strong></span>
                                        </div>

                                        <div class="course-meta">
                                            <svg><use href="#i-clock"/></svg>
                                            <span>Belajar mandiri</span>
                                            <span class="sep"></span>
                                            <svg><use href="#i-book"/></svg>
                                            <span>⭐ {{ number_format($course->rating ?? 5.0, 1) }} ({{ number_format($course->rating_count ?? 0) }})</span>
                                            <span class="sep"></span>
                                            <span>{{ number_format($course->total_students ?? 0) }} siswa</span>
                                        </div>
                                    </div>

                                    {{-- Bottom Actions --}}
                                    <div class="course-card-bottom">
                                        <div class="course-price {{ $isFree ? 'free' : '' }}">
                                            <small>Biaya kursus</small>
                                            @if($isFree)
                                                Gratis
                                            @elseif($hasDiscount)
                                                {{ rupiah((int)$course->discount_price) }}
                                                <span class="strike">{{ rupiah((int)$course->price) }}</span>
                                            @else
                                                {{ rupiah((int)$course->price) }}
                                            @endif
                                        </div>

                                        <div class="course-actions">
                                            <button type="button" 
                                                    class="cart-quick-btn" 
                                                    title="Tambahkan ke keranjang" 
                                                    aria-label="Tambah ke keranjang"
                                                    onclick="addCourseToCart(this, {{ $course->id }})">
                                                <svg class="cart-icon"><use href="#i-cart"/></svg>
                                                <svg class="check-icon" style="display:none; color:#16a34a;"><use href="#i-check"/></svg>
                                            </button>
                                            <a href="{{ route('courses.show', $course->slug) }}" class="detail-btn">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination Row --}}
                        <div class="pagination-row">
                            <span>
                                Menampilkan {{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} dari {{ number_format($courses->total()) }} kursus
                            </span>
                            <nav class="corp-pagination" aria-label="Navigasi halaman kursus">
                                @if($courses->hasPages())
                                    @if($courses->onFirstPage())
                                        <span aria-disabled="true" style="opacity:.4; cursor:not-allowed;">‹</span>
                                    @else
                                        <a href="{{ $courses->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">‹</a>
                                    @endif

                                    @php
                                        $startPage = max(1, $courses->currentPage() - 2);
                                        $endPage = min($courses->lastPage(), $courses->currentPage() + 2);
                                    @endphp
                                    @for($p = $startPage; $p <= $endPage; $p++)
                                        @if($p == $courses->currentPage())
                                            <span class="active" aria-current="page" style="border-color:var(--blue); background:var(--blue); color:white;">{{ $p }}</span>
                                        @else
                                            <a href="{{ $courses->url($p) }}">{{ $p }}</a>
                                        @endif
                                    @endfor

                                    @if($courses->hasMorePages())
                                        <a href="{{ $courses->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya">›</a>
                                    @else
                                        <span aria-disabled="true" style="opacity:.4; cursor:not-allowed;">›</span>
                                    @endif
                                @endif
                            </nav>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon"><svg><use href="#i-search"/></svg></div>
                            <h3>Tidak ada kursus yang sesuai</h3>
                            <p>Coba gunakan kata kunci atau pilihan kategori lain, atau reset filter untuk menampilkan seluruh kursus yang tersedia.</p>
                            <a href="{{ route('courses.index') }}" class="btn-primary-corp">
                                Reset Filter Kursus
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER CORPORATE CTA --}}
    <section class="newsletter-corp" id="newsletter">
        <div class="container newsletter-corp-content">
            <div>
                <div class="eyebrow">SKOLAH.COM INSIGHTS</div>
                <h2>Dapatkan inspirasi belajar berikutnya.</h2>
                <p>Ikuti kabar kursus baru, wawasan pengembangan talenta, dan informasi program pelatihan untuk pengembangan karier &amp; organisasi Anda.</p>
            </div>
            <div style="width: min(100%, 440px);">
                <form class="newsletter-form-corp" onsubmit="event.preventDefault(); alert('Terima kasih! Anda telah terdaftar untuk menerima update berkala.');">
                    <input type="email" placeholder="Email pribadi atau perusahaan" required>
                    <button type="submit">Berlangganan</button>
                </form>
                <small style="display:block; color:#cad8f7; font-size:10px; margin-top:8px;">
                    Kami menghormati privasi Anda. Anda dapat berhenti berlangganan kapan saja.
                </small>
            </div>
        </div>
    </section>

    {{-- TOAST NOTIFICATION --}}
    <div class="toast" id="toast" role="status" aria-live="polite"></div>

</div>

@push('scripts')
<script>
let toastTimer;
function showToast(message, type = 'success') {
    const t = document.getElementById('toast');
    if (!t) return;
    t.className = 'toast toast-' + type;
    t.innerHTML = (type === 'success' ? '✓ ' : (type === 'error' ? '⚠ ' : 'ℹ ')) + message;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
}

function addCourseToCart(btn, courseId) {
    @guest
        showToast('Silakan masuk (login) terlebih dahulu untuk menambah ke keranjang.', 'info');
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 1200);
        return;
    @endguest

    btn.classList.add('loading');
    const originalSvg = btn.querySelector('.cart-icon');
    const checkSvg = btn.querySelector('.check-icon');

    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'course',
            id: courseId
        })
    })
    .then(async res => {
        if (res.status === 401 || res.status === 403) {
            throw new Error('UNAUTHORIZED');
        }
        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
        return data;
    })
    .then(data => {
        btn.classList.remove('loading');
        if (data.success) {
            btn.classList.add('success');
            if (originalSvg) originalSvg.style.display = 'none';
            if (checkSvg) checkSvg.style.display = 'block';

            showToast(data.message || 'Item berhasil ditambahkan ke keranjang!', data.already_exists ? 'info' : 'success');

            // Dispatch event to Livewire so navbar cart badge increments immediately!
            if (window.Livewire) {
                window.Livewire.dispatch('cart-updated');
            }

            setTimeout(() => {
                btn.classList.remove('success');
                if (originalSvg) originalSvg.style.display = 'block';
                if (checkSvg) checkSvg.style.display = 'none';
            }, 2000);
        } else {
            showToast(data.message || 'Gagal menambahkan ke keranjang.', 'error');
        }
    })
    .catch(err => {
        btn.classList.remove('loading');
        if (err.message === 'UNAUTHORIZED') {
            showToast('Silakan masuk terlebih dahulu untuk menambah ke keranjang.', 'info');
            setTimeout(() => { window.location.href = "{{ route('login') }}"; }, 1000);
        } else {
            showToast(err.message || 'Terjadi kesalahan saat menambahkan ke keranjang.', 'error');
        }
    });
}

function toggleCourseView(mode) {
    const grid = document.getElementById('courseGrid');
    const gridBtn = document.getElementById('gridMode');
    const listBtn = document.getElementById('listMode');
    if (!grid) return;

    if (mode === 'list') {
        grid.classList.add('list-view');
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        localStorage.setItem('skolah_course_view', 'list');
    } else {
        grid.classList.remove('list-view');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        localStorage.setItem('skolah_course_view', 'grid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('skolah_course_view');
    if (savedView === 'list') {
        toggleCourseView('list');
    }
});
</script>
@endpush

@endsection