let deferredPrompt;
let installPopup = document.getElementById('pwa-install-popup');
let instructionsModal = document.getElementById('install-instructions-modal');
let installSteps = document.getElementById('install-steps');

// Detect device type
function getDeviceType() {
    const userAgent = navigator.userAgent.toLowerCase();
    if (/iphone|ipad|ipod/.test(userAgent)) return 'ios';
    if (/android/.test(userAgent)) return 'android';
    return 'desktop';
}

// Check if device is mobile
function isMobile() {
    return window.innerWidth <= 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

// Check if app is already installed
function isAppInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;
}

// Check if user recently dismissed the popup
function wasRecentlyDismissed() {
    const dismissed = localStorage.getItem('pwa-install-dismissed');
    if (!dismissed) return false;

    const daysSinceDismissed = (Date.now() - parseInt(dismissed)) / (1000 * 60 * 60 * 24);
    return daysSinceDismissed < 3; // Show again after 3 days
}

// Listen for the beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('PWA install prompt available');
    e.preventDefault();
    deferredPrompt = e;
});

// Show install popup
function showInstallPopup() {
    if (isAppInstalled()) {
        console.log('App already installed');
        return;
    }

    installPopup.classList.add('show');

    // Track popup shown
    localStorage.setItem('pwa-popup-shown', Date.now());
}

// Hide install popup
function hideInstallPopup() {
    installPopup.classList.remove('show');
    localStorage.setItem('pwa-install-dismissed', Date.now());
}

// Install app function
async function installApp() {
    if (deferredPrompt) {
        // Native browser install
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log('Install outcome:', outcome);

        if (outcome === 'accepted') {
            hideInstallPopup();
            deferredPrompt = null;
        }
    } else {
        // Show manual instructions
        showInstallInstructions();
    }
}

// Show install instructions based on device
function showInstallInstructions() {
    const deviceType = getDeviceType();
    let steps = [];

    if (deviceType === 'ios') {
        steps = [
            'Tap the Share button <strong>⎋</strong> at the bottom of the screen',
            'Scroll down and tap <strong>"Add to Home Screen"</strong>',
            'Tap <strong>"Add"</strong> in the top right corner',
            'The XGHRM app will appear on your home screen'
        ];
    } else if (deviceType === 'android') {
        steps = [
            'Tap the menu button <strong>⋮</strong> in your browser',
            'Look for <strong>"Add to Home screen"</strong> or <strong>"Install app"</strong>',
            'Tap <strong>"Install"</strong> or <strong>"Add"</strong>',
            'The XGHRM app will be added to your home screen'
        ];
    } else {
        steps = [
            'Look for an install icon in your browser\'s address bar',
            'Or check your browser menu for <strong>"Install"</strong> option',
            'Click <strong>"Install"</strong> when prompted',
            'The app will be added to your computer'
        ];
    }

    // Populate steps
    installSteps.innerHTML = steps.map((step, index) => `
                <div class="modal-step">
                    <div class="step-number">${index + 1}</div>
                    <div class="step-text">${step}</div>
                </div>
            `).join('');

    hideInstallPopup();
    instructionsModal.classList.add('show');
}

// Hide instructions modal
function hideInstructionsModal() {
    instructionsModal.classList.remove('show');
}

// Auto-show popup logic
function shouldShowPopup() {
    // Don't show if already installed
    if (isAppInstalled()) return false;

    // Don't show if recently dismissed
    if (wasRecentlyDismissed()) return false;

    // Only show on mobile devices
    if (!isMobile()) return false;

    // Don't show if already shown in this session
    if (sessionStorage.getItem('popup-shown-this-session')) return false;

    return true;
}

// Initialize popup behavior
window.addEventListener('load', () => {
    console.log('Device type:', getDeviceType());
    console.log('Is mobile:', isMobile());
    console.log('Is installed:', isAppInstalled());

    if (shouldShowPopup()) {
        // Show popup after user interaction (3 seconds)
        setTimeout(() => {
            showInstallPopup();
            sessionStorage.setItem('popup-shown-this-session', 'true');
        }, 3000);
    }
});

// Handle app installed event
window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    hideInstallPopup();
    hideInstructionsModal();

    // Optional: Show success message
    alert('XGHRM app installed successfully! 🎉');
});

// Close modal when clicking outside
instructionsModal.addEventListener('click', (e) => {
    if (e.target === instructionsModal) {
        hideInstructionsModal();
    }
});
