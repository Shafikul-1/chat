<script setup>
//import { ref, reactive } from 'vue'

</script>

<template>
    <div class="message mb-4 flex">
        <div class="flex-2">
            <div class="w-12 h-12 relative">
                <img class="w-12 h-12 rounded-full mx-auto"
                    src="https://tailwindcss-chat.vercel.app/resources/profile-image.png" alt="chat-user" />
                <span class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white"></span>
            </div>
        </div>
        <div class="flex-1 px-2">
            <div class="inline-block bg-gray-300 rounded-full p-2 px-6 text-gray-700">
                <span>All travel expenses are covered by us of course :D</span>
            </div>
            <div class="pl-4"><small class="text-gray-500 dark:text-gray-300">15
                    April</small></div>
        </div>
    </div>
    <div class="message me mb-4 flex text-right">
        <div class="flex-1 px-2">
            <div class="inline-block bg-blue-600 rounded-full p-2 px-6 text-white">
                <span>I accept. Thank you very much.</span>
            </div>
            <div class="pr-4"><small class="text-gray-500 dark:text-gray-300">15
                    April</small></div>
        </div>
    </div>
</template>

<style scoped></style>
