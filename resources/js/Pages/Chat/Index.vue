<script setup>
import { ref, reactive } from 'vue'
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const chatAllUser = ref(false);
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex-1 dark:bg-[#111827] bg-gray-100 w-full h-full mt-5">
            <div class="main-body container m-auto w-11/12 h-full flex flex-col">
                <div class="main flex-1 flex flex-col">
                    <div class="flex-1 flex h-full relative lg:gap-3">
                        <div :class="{ 'hidden': !chatAllUser }"  class="sidebar  absolute w-[95%] left-[2rem] top-0 z-50 lg:flex lg:static lg:w-[35%] min-h-[88vh] overflow-y-auto flex-2 flex-col  bg-gray-700 p-3 lg:bg-transparent ">
                            <div class="search flex-2 pb-6 px-2">
                                <input type="text"
                                    class="outline-none py-2 block w-full bg-transparent border-b-2 "
                                    placeholder="Search User">
                            </div>

                            <!-- User All -->
                            <div class="flex-1 h-full overflow-auto px-2">
                                <div
                                    class="entry cursor-pointer transform hover:bg-gray-700 hover:shadow-md hover:shadow-indigo-400 transition-all duration-300  bg-white dark:bg-gray-600  mb-4 rounded p-4 flex shadow-md border-l-4 border-red-500">
                                    <div class="flex-2">
                                        <div class="w-12 h-12 relative">
                                            <img class="w-12 h-12 rounded-full mx-auto"
                                                src="https://raw.githubusercontent.com/huzgrx/Messaging-APP/refs/heads/main/src/assets/images/clouds.png"
                                                alt="chat-user" />
                                            <span
                                                class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="truncate w-32"><span class="text-gray-800 dark:text-white">Karp Bonolo</span></div>
                                        <div><small class="text-gray-600 dark:text-white">Yea, Sure!</small></div>
                                    </div>
                                    <div class="flex-2 text-right">
                                        <div><small class="text-gray-500 dark:text-white">15 April</small></div>
                                        <div>
                                            <small
                                                class="text-xs bg-red-500 text-white rounded-full h-6 w-6 leading-6 text-center inline-block">
                                                10
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- User All -->

                        </div>
                        <div class="chat-area flex-1 flex flex-col">
                            <div class="flex-3">
                                <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200 dark:text-white">
                                        <span class="lg:hidden">
                                            <i @click="chatAllUser = !chatAllUser" class="fa-solid fa-bars  mr-5 cursor-pointer w-5 h-5" ></i>
                                        </span>
                                    Chatting with
                                    <b>Mercedes Yemelyan</b>
                                </h2>
                            </div>
                            <div class="messages flex-1 overflow-auto">

                                <!-- Chat Text -->
                                <div class="message mb-4 flex">
                                    <div class="flex-2">
                                        <div class="w-12 h-12 relative">
                                            <img class="w-12 h-12 rounded-full mx-auto"
                                                src="https://tailwindcss-chat.vercel.app/resources/profile-image.png"
                                                alt="chat-user" />
                                            <span
                                                class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white"></span>
                                        </div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="inline-block bg-gray-300 rounded-full p-2 px-6 text-gray-700">
                                            <span>All travel expenses are covered by us of course :D</span>
                                        </div>
                                        <div class="pl-4"><small class="text-gray-500 dark:text-gray-300">15 April</small></div>
                                    </div>
                                </div>
                                <div class="message me mb-4 flex text-right">
                                    <div class="flex-1 px-2">
                                        <div class="inline-block bg-blue-600 rounded-full p-2 px-6 text-white">
                                            <span>I accept. Thank you very much.</span>
                                        </div>
                                        <div class="pr-4"><small class="text-gray-500 dark:text-gray-300">15 April</small></div>
                                    </div>
                                </div>
                                <!-- Chat Text -->

                            </div>

                            <!-- Chat filed Content -->
                            <div class="flex-2 pt-4 pb-10 ">
                                <div class="write bg-white dark:bg-[#253350] shadow flex rounded-lg">
                                    <div class="flex-3 flex content-center items-center text-center p-4 pr-0">
                                        <span
                                            class="block text-center text-gray-400 hover:text-gray-500 cursor-pointer">
                                            <svg fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                                                class="h-6 w-6">
                                                <path
                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" name="message" autofocus
                                            class="w-full block focus:outline-none outline-none py-4 px-4 bg-transparent border-none focus:border-none focus:ring-0 dark:text-white"
                                            placeholder="Enter Text ...." autocomplete="off">

                                    </div>
                                    <div class="flex-2 w-32 p-2 flex content-center items-center">
                                        <div class="flex-1 text-center">
                                            <span class="text-gray-400 hover:text-gray-800">
                                                <span class="inline-block align-text-bottom cursor-pointer ">
                                                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"
                                                        class="w-6 h-6">
                                                        <path
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <button class="bg-blue-400 w-10 h-10 rounded-full inline-block">
                                                <span class="inline-block align-text-bottom">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        class="w-4 h-4 text-white">
                                                        <path d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Chat filed Content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
