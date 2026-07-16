{{-- Auth Modal (Login/Signup) --}}
<div id="authModal" class="fixed inset-0 z-[100] hidden">
    {{-- Backdrop --}}
    <div id="authModalBackdrop" class="absolute inset-0 bg-primary/60 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal Container --}}
    <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="authModalContent"
            class="relative bg-surface rounded-3xl shadow-2xl w-full max-w-md transform transition-all scale-95 opacity-0 max-h-[90vh] overflow-y-auto my-4 border border-secondary-100">

            {{-- Close Button --}}
            <button onclick="closeAuthModal()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-light hover:bg-secondary-100 flex items-center justify-center transition-colors tap-effect z-10">
                <i class="fas fa-times text-secondary-500"></i>
            </button>

            {{-- Modal Header --}}
            <div class="pt-8 pb-4 px-6 text-center border-b border-secondary-100">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1 mb-4">
                    <span class="text-2xl font-extrabold logo-text tracking-tight text-primary">Spinner</span>
                    <span class="text-2xl font-extrabold logo-accent tracking-tight text-primary">Fashion</span>
                </a>

                {{-- Tab Switcher --}}
                <div class="flex bg-light rounded-xl p-1 mt-4 border border-secondary-100">
                    <button id="loginTab" onclick="switchAuthTab('login')"
                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all bg-surface-elevated text-primary shadow-sm border border-secondary-100">
                        Login
                    </button>
                    <button id="signupTab" onclick="switchAuthTab('signup')"
                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all text-secondary-500 hover:text-secondary-700">
                        Sign Up
                    </button>
                </div>
            </div>

            {{-- Login Form --}}
            <div id="loginForm" class="p-6">
                <form id="loginFormElement" action="#" method="POST" class="space-y-4">
                    @csrf

                    {{-- Phone Number --}}
                    <div>
                        <label for="login_phone" class="block text-sm font-medium text-secondary-600 mb-1.5">Phone
                            Number</label>
                        <div class="relative">
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5 text-secondary-500 border-r border-secondary-200 pr-2">
                                <span class="text-lg">🇧🇩</span>
                                <span class="text-sm font-medium">+88</span>
                            </div>
                            <input type="tel" id="login_phone" name="phone" placeholder="01XXXXXXXXX" maxlength="11"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 pl-24 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                pattern="[0-9]{11}" inputmode="numeric" required>
                        </div>
                        <p class="mt-1 text-xs text-secondary-400">Enter 11 digit number without +88</p>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="login_password"
                            class="block text-sm font-medium text-secondary-600 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="login_password" name="password" placeholder="Enter your password"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 px-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                required>
                            <button type="button" onclick="togglePassword('login_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary-400 hover:text-secondary-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-secondary-300 text-primary focus:ring-primary-300">
                            <span class="text-secondary-500">Remember me</span>
                        </label>
                        <a href="#" class="text-primary-500 hover:text-primary-700 font-medium transition-colors">Forgot Password?</a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-primary text-surface-elevated py-3.5 rounded-xl font-semibold text-sm hover:bg-primary-700 transition tap-effect shadow-lg shadow-primary-200/50">
                        Login to Your Account
                    </button>
                </form>

                {{-- Switch to Signup --}}
                <p class="mt-6 text-center text-sm text-secondary-500">
                    Don't have an account?
                    <button onclick="switchAuthTab('signup')" class="text-primary-500 font-semibold hover:text-primary-700 transition-colors">Sign up
                        now</button>
                </p>
            </div>

            {{-- Signup Form --}}
            <div id="signupForm" class="p-6 hidden">
                <form id="signupFormElement" action="#" method="POST" class="space-y-4">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label for="signup_name" class="block text-sm font-medium text-secondary-600 mb-1.5">Full
                            Name</label>
                        <div class="relative">
                            <input type="text" id="signup_name" name="name" placeholder="Enter your full name"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 px-4 pl-11 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                required>
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-secondary-400"></i>
                        </div>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="signup_phone" class="block text-sm font-medium text-secondary-600 mb-1.5">Phone
                            Number</label>
                        <div class="relative">
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5 text-secondary-500 border-r border-secondary-200 pr-2">
                                <span class="text-lg">🇧🇩</span>
                                <span class="text-sm font-medium">+88</span>
                            </div>
                            <input type="tel" id="signup_phone" name="phone" placeholder="01XXXXXXXXX" maxlength="11"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 pl-24 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                pattern="[0-9]{11}" inputmode="numeric" required>
                        </div>
                        <p class="mt-1 text-xs text-secondary-400">Enter 11 digit number without +88 (e.g., 01712345678)</p>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="signup_password"
                            class="block text-sm font-medium text-secondary-600 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="signup_password" name="password"
                                placeholder="Create a password (min 6 characters)"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 px-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                minlength="6" required>
                            <button type="button" onclick="togglePassword('signup_password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary-400 hover:text-secondary-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="signup_password_confirmation"
                            class="block text-sm font-medium text-secondary-600 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="signup_password_confirmation" name="password_confirmation"
                                placeholder="Confirm your password"
                                class="w-full bg-light border border-secondary-200 rounded-xl py-3 px-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300/50 focus:border-primary-300 focus:bg-surface transition-all text-primary placeholder-secondary-300"
                                minlength="6" required>
                            <button type="button" onclick="togglePassword('signup_password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary-400 hover:text-secondary-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Terms & Conditions --}}
                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="terms" name="terms"
                            class="w-4 h-4 mt-0.5 rounded border-secondary-300 text-primary focus:ring-primary-300" required>
                        <label for="terms" class="text-sm text-secondary-500">
                            I agree to the <a href="#" class="text-primary-500 hover:text-primary-700 transition-colors">Terms of Service</a> and <a
                                href="#" class="text-primary-500 hover:text-primary-700 transition-colors">Privacy Policy</a>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-primary text-surface-elevated py-3.5 rounded-xl font-semibold text-sm hover:bg-primary-700 transition tap-effect shadow-lg shadow-primary-200/50">
                        Create Account
                    </button>
                </form>

                {{-- Switch to Login --}}
                <p class="mt-6 text-center text-sm text-secondary-500">
                    Already have an account?
                    <button onclick="switchAuthTab('login')" class="text-primary-500 font-semibold hover:text-primary-700 transition-colors">Login
                        here</button>
                </p>
            </div>

        </div>
    </div>
</div>

<script>
    function openAuthModal(tab = 'login') {
        const modal = document.getElementById('authModal');
        const content = document.getElementById('authModalContent');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animate in
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        switchAuthTab(tab);
    }

    function closeAuthModal() {
        const modal = document.getElementById('authModal');
        const content = document.getElementById('authModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function switchAuthTab(tab) {
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');

        if (tab === 'login') {
            loginTab.classList.add('bg-surface-elevated', 'text-primary', 'shadow-sm', 'border', 'border-secondary-100');
            loginTab.classList.remove('text-secondary-500');
            signupTab.classList.remove('bg-surface-elevated', 'text-primary', 'shadow-sm', 'border', 'border-secondary-100');
            signupTab.classList.add('text-secondary-500');

            loginForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
        } else {
            signupTab.classList.add('bg-surface-elevated', 'text-primary', 'shadow-sm', 'border', 'border-secondary-100');
            signupTab.classList.remove('text-secondary-500');
            loginTab.classList.remove('bg-surface-elevated', 'text-primary', 'shadow-sm', 'border', 'border-secondary-100');
            loginTab.classList.add('text-secondary-500');

            signupForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('authModalBackdrop').addEventListener('click', closeAuthModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAuthModal();
        }
    });

    // Phone number validation - only allow numbers
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        input.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>