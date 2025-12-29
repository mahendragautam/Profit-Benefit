/* ========== Production-Ready Base CSS (Refined & Enhanced – 2025 Edition) ========== */

/* ========== Design Tokens ========== */
:root {
  /* Brand Colors */
  --primary: #1a2e4a;
  --primary-hover: #152538;
  --primary-light: #2d4a6e;
  --secondary: #2d6a4f;
  --secondary-hover: #246049;
  --accent: #40916c;
  --accent-hover: #52a47c;
  --accent-light: #95d5b2;

  /* Neutral Palette */
  --white: #ffffff;
  --light: #f8f9fa;
  --border: #dee2e6;
  --border-hover: #adb5bd;
  --text: #2c3e50;
  --text-light: #495057;
  --muted: #6c757d;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-600: #6c757d;
  --gray-800: #343a40;
  --gray-900: #212529;

  /* Semantic Colors */
  --success: #52b788;
  --success-light: #d4edda;
  --warning: #f4a261;
  --warning-light: #fdebd0;
  --error: #e63946;
  --error-light: #fad8d8;
  --info: #457b9d;
  --info-light: #d1e7f0;

  /* Shadows (soft, modern) */
  --shadow-xs: 0 1px 3px rgba(26, 46, 74, 0.08);
  --shadow-sm: 0 4px 12px rgba(26, 46, 74, 0.08);
  --shadow-md: 0 8px 24px rgba(26, 46, 74, 0.12);
  --shadow-lg: 0 16px 32px rgba(26, 46, 74, 0.16);
  --shadow-xl: 0 24px 48px rgba(26, 46, 74, 0.20);

  /* Spacing Scale (consistent 4px base) */
  --space-1: 0.25rem;  /* 4px */
  --space-2: 0.5rem;   /* 8px */
  --space-3: 0.75rem;  /* 12px */
  --space-4: 1rem;     /* 16px */
  --space-5: 1.5rem;   /* 24px */
  --space-6: 2rem;     /* 32px */
  --space-8: 3rem;     /* 48px */
  --space-10: 4rem;    /* 64px */
  --space-12: 5rem;    /* 80px */

  /* Border Radius */
  --radius-sm: 6px;
  --radius: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --radius-full: 9999px;

  /* Typography */
  --font-xs: 0.75rem;     /* 12px */
  --font-sm: 0.875rem;    /* 14px */
  --font-base: 1rem;      /* 16px */
  --font-lg: 1.125rem;    /* 18px */
  --font-xl: 1.25rem;     /* 20px */
  --font-2xl: 1.5rem;     /* 24px */
  --font-3xl: 1.875rem;   /* 30px */
  --font-4xl: 2.25rem;    /* 36px */

  /* Transitions */
  --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: 400ms cubic-bezier(0.4, 0, 0.2, 1);

  /* Z-Index */
  --z-base: 1;
  --z-dropdown: 100;
  --z-sticky: 200;
  --z-fixed: 300;
  --z-modal-backdrop: 400;
  --z-modal: 500;
  --z-tooltip: 600;
  --z-toast: 700;
}

/* ========== Dark Mode (prefers-color-scheme) ========== */
@media (prefers-color-scheme: dark) {
  :root {
    --light: #1e1e1e;
    --border: #333333;
    --border-hover: #4a4a4a;
    --text: #e8e8e8;
    --text-light: #b8b8b8;
    --muted: #888888;
    --gray-100: #2a2a2a;
    --gray-200: #333333;
    --gray-300: #404040;
    --gray-400: #525252;
    --gray-600: #888888;
    --gray-800: #e8e8e8;
    --gray-900: #f5f5f5;

    --shadow-xs: 0 1px 3px rgba(0, 0, 0, 0.4);
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.5);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.6);
    --shadow-lg: 0 16px 32px rgba(0, 0, 0, 0.7);
    --shadow-xl: 0 24px 48px rgba(0, 0, 0, 0.8);
  }

  body {
    background: #121212;
    color: var(--text);
  }

  .card,
  input:not([type="checkbox"]):not([type="radio"]),
  textarea,
  select {
    background: var(--light);
  }
}

/* ========== Global Reset & Base ========== */
*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  font-size: 100%; /* 16px base */
  line-height: 1.5;
  scroll-behavior: smooth;
  -webkit-text-size-adjust: 100%;
  text-size-adjust: 100%;
}

body {
  background: var(--white);
  color: var(--text);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Media Elements */
img,
picture,
video,
canvas,
svg {
  display: block;
  max-width: 100%;
  height: auto;
}

/* ========== Layout ========== */
.container {
  width: 100%;
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--space-4);
}

@media (min-width: 768px) {
  .container {
    padding: 0 var(--space-6);
  }
}

.container-sm { max-width: 640px; }
.container-lg { max-width: 1536px; }
.container-fluid { max-width: 100%; padding: 0 var(--space-4); }

/* ========== Typography ========== */
h1, h2, h3, h4, h5, h6 {
  margin-bottom: var(--space-3);
  font-weight: 600;
  line-height: 1.2;
  color: var(--text);
}

h1 { font-size: clamp(2.5rem, 5vw, 4rem); }
h2 { font-size: clamp(2rem, 4vw, 3rem); }
h3 { font-size: clamp(1.5rem, 3vw, 2.25rem); }
h4 { font-size: var(--font-2xl); }
h5 { font-size: var(--font-xl); }
h6 { font-size: var(--font-lg); }

p {
  margin-bottom: var(--space-4);
  color: var(--text-light);
}

a {
  color: var(--accent);
  text-decoration: none;
  transition: color var(--transition-fast);
}

a:hover {
  color: var(--accent-hover);
  text-decoration: underline;
}

strong { font-weight: 600; }
small, .text-sm { font-size: var(--font-sm); }

/* ========== Buttons ========== */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5ch;
  padding: 0.65rem 1.25rem;
  font-family: inherit;
  font-size: var(--font-base);
  font-weight: 500;
  line-height: 1.2;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid transparent;
  border-radius: var(--radius);
  background: var(--accent);
  color: var(--white);
  transition: 
    background var(--transition-fast),
    border-color var(--transition-fast),
    transform var(--transition-base),
    box-shadow var(--transition-base);
  user-select: none;
}

.btn:hover {
  background: var(--accent-hover);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.btn:active {
  transform: translateY(0);
  box-shadow: var(--shadow-sm);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* Button Variants */
.btn-primary { background: var(--primary); border-color: var(--primary); }
.btn-primary:hover { background: var(--primary-hover); }

.btn-secondary {
  background: transparent;
  color: var(--primary);
  border-color: var(--border);
}

.btn-secondary:hover {
  background: var(--gray-100);
  border-color: var(--border-hover);
}

.btn-outline {
  background: transparent;
  color: var(--accent);
  border-color: var(--accent);
}

.btn-outline:hover {
  background: var(--accent);
  color: var(--white);
}

/* Sizes */
.btn-sm { padding: 0.45rem 0.9rem; font-size: var(--font-sm); }
.btn-lg { padding: 0.85rem 1.75rem; font-size: var(--font-lg); }

/* ========== Form Controls ========== */
label {
  display: block;
  margin-bottom: var(--space-2);
  font-size: var(--font-sm);
  font-weight: 500;
  color: var(--text);
}

input:not([type="checkbox"]):not([type="radio"]),
textarea,
select {
  width: 100%;
  padding: 0.65rem 0.9rem;
  font-family: inherit;
  font-size: var(--font-base);
  color: var(--text);
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

input:focus-visible,
textarea:focus-visible,
select:focus-visible {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 4px color-mix(in srgb, var(--accent) 15%, transparent);
}

input:disabled,
textarea:disabled,
select:disabled {
  background: var(--gray-100);
  opacity: 0.7;
  cursor: not-allowed;
}

input::placeholder,
textarea::placeholder {
  color: var(--muted);
}

textarea { resize: vertical; min-height: 120px; }

/* ========== Card Component ========== */
.card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  padding: var(--space-6);
  box-shadow: var(--shadow-sm);
  transition: box-shadow var(--transition-base), transform var(--transition-base);
}

.card:hover {
  box-shadow: var(--shadow-md);
}

.card-interactive {
  cursor: pointer;
}

.card-interactive:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}

.card-header {
  padding-bottom: var(--space-4);
  margin-bottom: var(--space-4);
  border-bottom: 1px solid var(--border);
}

.card-footer {
  padding-top: var(--space-4);
  margin-top: var(--space-4);
  border-top: 1px solid var(--border);
}

/* ========== Utility Classes ========== */
/* Text */
.text-center { text-align: center; }
.text-left   { text-align: left; }
.text-right  { text-align: right; }
.text-muted  { color: var(--muted); }
.text-primary { color: var(--primary); }
.text-accent  { color: var(--accent); }
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
.text-error   { color: var(--error); }

/* Font Weight */
.fw-medium { font-weight: 500; }
.fw-semibold { font-weight: 600; }
.fw-bold { font-weight: 700; }

/* Flex & Grid Helpers */
.flex { display: flex; }
.flex-col { flex-direction: column; }
.items-center { align-items: center; }
.items-start { align-items: flex-start; }
.items-end { align-items: flex-end; }
.justify-center { justify-content: center; }
.justify-between { justify-content: space-between; }
.justify-end { justify-content: flex-end; }

.gap-2 { gap: var(--space-2); }
.gap-4 { gap: var(--space-4); }
.gap-6 { gap: var(--space-6); }
.gap-8 { gap: var(--space-8); }

.stack { display: flex; flex-direction: column; gap: var(--space-4); }
.stack-sm { gap: var(--space-2); }
.stack-lg { gap: var(--space-8); }

/* Spacing */
.m-0 { margin: 0; }
.mt-4 { margin-top: var(--space-4); }
.mb-4 { margin-bottom: var(--space-4); }
.my-4 { margin-block: var(--space-4); }
.mx-auto { margin-inline: auto; }

.p-4 { padding: var(--space-4); }
.px-4 { padding-inline: var(--space-4); }
.py-4 { padding-block: var(--space-4); }

/* Width & Display */
.w-full { width: 100%; }
.hidden { display: none !important; }

/* ========== Responsive Grid ========== */
.grid { display: grid; gap: var(--space-6); }

@media (min-width: 640px) {
  .sm\:grid-2 { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 768px) {
  .md\:grid-2 { grid-template-columns: repeat(2, 1fr); }
  .md\:grid-3 { grid-template-columns: repeat(3, 1fr); }
}

@media (min-width: 1024px) {
  .lg\:grid-3 { grid-template-columns: repeat(3, 1fr); }
  .lg\:grid-4 { grid-template-columns: repeat(4, 1fr); }
}

.grid-sidebar {
  grid-template-columns: 1fr 360px;
  gap: var(--space-8);
}

/* ========== Accessibility & Preferences ========== */
/* Skip Link */
.skip-link {
  position: absolute;
  top: -9999px;
  left: -9999px;
  background: var(--gray-900);
  color: var(--white);
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius);
  z-index: var(--z-modal);
}

.skip-link:focus {
  top: var(--space-4);
  left: var(--space-4);
}

/* Screen Reader Only */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* Focus Visible */
:focus:not(:focus-visible) { outline: none; }
:focus-visible {
  outline: 3px solid var(--accent);
  outline-offset: 3px;
  border-radius: var(--radius-sm);
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* ========== Print Styles ========== */
@media print {
  *,
  *::before,
  *::after {
    background: transparent !important;
    color: #000 !important;
    box-shadow: none !important;
  }

  a::after {
    content: " (" attr(href) ")";
    font-size: 0.8em;
  }

  .no-print { display: none; }
}