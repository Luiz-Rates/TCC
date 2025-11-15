<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-800/70 bg-slate-900/80 p-6 shadow-2xl shadow-slate-950/70 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800/70 bg-slate-900/80 p-6 shadow-2xl shadow-slate-950/70 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800/70 bg-slate-900/80 p-6 shadow-2xl shadow-slate-950/70 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
