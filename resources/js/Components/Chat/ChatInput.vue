<script setup>
import { ref, reactive } from 'vue'
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const chatUserId = sessionStorage.getItem('chatUserId');
const message = ref('');
const attachments = null;


const checkMessage = usePage().props.flash.message;
// const submitdata = () => {
// Inertia.post(route('chat.storeMessage', chatId), {
//     content: 'test here',
//     other: 'other tata',
//     newtext: 'new work',
//     message: message.value
// }, {
//     preserveScroll: true,  // This will prevent the page from reloading or scrolling
//     preserveState: true,   // This will keep the page's state intact
//     onSuccess: () => {
//         message.value = '';  // Clear the message field after success
//     },
//     onError: (errors) => {
//         console.error(errors);  // Optional: Handle validation or server errors
//     }
// });
// }
// const user = usePage().props.auth.user;
// console.log(user);
const submitMessage = () => {
    // Check if the message is not empty before submitting
    if (message.value.trim() !== '') {
        // Here you can handle the submission logic
        // After submission logic, clear the input field
        message.value = '';
    }
};

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
                <input autofocus v-model="message" placeholder="Enter Text ...." autocomplete="off"
                    class="w-full block focus:outline-none outline-none py-4 px-4 bg-transparent border-none focus:border-none focus:ring-0 dark:text-white" />

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
                    <!-- <button @click="submitdata" class="bg-blue-400 w-10 h-10 rounded-full inline-block"> -->
                    <Link :href="route('chat.storeMessage', chatUserId)"
                        :data="{ message: message, attachments: attachments }" method="post" as="button" preserveScroll
                        @click.prevent="submitMessage">
                    <span class="inline-block align-text-bottom">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    </Link>
                    <!-- </button> -->
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
