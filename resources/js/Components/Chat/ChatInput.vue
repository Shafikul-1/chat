<script setup>
//import { ref, reactive } from 'vue'

</script>

<template>
    <div class="flex-2 pt-4 pb-10 ">
        <div class="write bg-white dark:bg-[#253350] shadow flex rounded-lg">
            <div class="flex-3 flex content-center items-center text-center p-4 pr-0">
                <span class="block text-center text-gray-400 hover:text-gray-500 cursor-pointer">
                    <i class="fa-solid fa-face-smile"></i>
                </span>
            </div>
            <div class="flex-1">
                <input v-model="message" autofocus
                    class="w-full block focus:outline-none outline-none py-4 px-4 bg-transparent border-none focus:border-none focus:ring-0 dark:text-white"
                    placeholder="Enter Text ...." autocomplete="off" />

            </div>
            <div class="flex-2 w-32 p-2 flex content-center items-center">
                <div class="flex-1 text-center">
                    <span class="text-gray-400 hover:text-gray-500">
                        <span class="inline-block align-text-bottom cursor-pointer ">
                            <input type="file" name="" id="" class="hidden">
                            <i class="fa-solid fa-paperclip"></i>
                        </span>
                    </span>
                </div>
                <div class="flex-1">
                    <button @click="sentMessage" class="bg-blue-400 w-10 h-10 rounded-full inline-block">
                        <span class="inline-block align-text-bottom">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
