{{-- Bottom Navigation Bar --}}
<footer class="fixed bottom-0 left-0 right-0 max-w-xl mx-auto z-10 p-2"
        style="background-color: transparent;">
    <nav class="flex justify-around py-3 rounded-t-2xl border border-opacity-10 shadow-2xl"
         style="background-color: #1a1a2e; border-color: rgba(255, 255, 255, 0.1);">
        
        {{-- Home --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
            <span class="text-xs mt-1">Home</span>
        </a>
        {{-- Workout --}}
        <a href="{{ route('muscleGroupsPage') }}" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span class="text-xs mt-1">Workout</span>
        </a>
        {{-- Meals --}}
        <a href="{{ route('meals.index') }}" class="flex flex-col items-center text-center transition" style="color: #d58548;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 1.343 3 3v5H9v-5c0-1.657 1.343-3 3-3zM7 21h10a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
            <span class="text-xs mt-1 font-semibold">Meals</span>
        </a>
        {{-- Profile --}}
        <a href="#" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </nav>
</footer>


