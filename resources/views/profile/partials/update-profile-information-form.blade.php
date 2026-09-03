<section class="space-y-6">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-slate-900">
            Profile Information & Avatar
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Update your account's display name, profile picture, and primary contact email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Avatar Upload -->
        <div class="flex items-center gap-6 p-4 bg-slate-50 border border-slate-200 rounded-xl" x-data="{ avatarPreview: null }">
            <div class="shrink-0">
                <template x-if="avatarPreview">
                    <img :src="avatarPreview" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-500 shadow">
                </template>
                <template x-if="!avatarPreview">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-500 shadow">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-xl font-bold text-white shadow font-mono border-2 border-slate-700">
                            {{ $user->initials }}
                        </div>
                    @endif
                </template>
            </div>

            <div class="flex-1 space-y-1">
                <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Profile Picture</label>
                <input id="avatar" name="avatar" type="file" accept="image/*"
                       @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file); }"
                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 rounded-lg cursor-pointer bg-white">
                <p class="text-[11px] text-slate-400">JPG, PNG, or WEBP (Max 2MB).</p>
                <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
            <input id="name" name="name" type="text" required autocomplete="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
            <input id="email" name="email" type="email" required autocomplete="username"
                   value="{{ old('email', $user->email) }}"
                   class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-xs text-amber-700 font-medium">
                        Your email address is unverified.
                        <button form="send-verification" class="underline text-xs text-slate-600 hover:text-slate-900">
                            Click here to re-send verification.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Assigned System Role</label>
            <input type="text" disabled value="{{ $user->role_type }}" class="w-full text-sm bg-slate-100 border-slate-200 text-slate-500 rounded-lg font-mono">
            <p class="text-xs text-slate-400 mt-1">Role assignments are governed by the System Administrator.</p>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                Save Profile Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-xs font-semibold text-emerald-600">
                    Profile updated successfully.
                </p>
            @endif
        </div>
    </form>
</section>
