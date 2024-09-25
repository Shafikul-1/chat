<script setup>
import { ref, reactive, defineProps, defineEmits } from 'vue'
import { usePage, Link } from '@inertiajs/vue3';
defineEmits(['toggleChatAllUserSmallDisplay']);
const props = defineProps({
    messageData: Array,
    chat_user_name: Object,
    userStatus: Object,
    chatUserId: String,
});
const user = usePage().props.auth.user;

function handleSuccess(response) {
    // console.log(response);
}
function handleError(error) {
    console.error('Error:', error);
}
</script>

<template>
    <div class="flex-3">
        <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200 dark:text-white">
            <span class="lg:hidden">
                <i @click="$emit('toggleChatAllUserSmallDisplay')"
                    class="fa-solid fa-bars  mr-5 cursor-pointer w-5 h-5"></i>
            </span>
            Chatting with
            <b>{{ chat_user_name.name }}</b>
        </h2>
    </div>
    <div class="bg-gray-500 w-full py-2 mb-4 rounded-md shadow-md shadow-indigo-400 text-center">
        <h2 class="font-bold capitalize text-center text-3xl text-green-600">invite Your friend</h2>
        <template v-if="props.userStatus">
            <div class="flex justify-evenly mt-3" v-if="props.userStatus.status != 'accepted'">
                <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'blocked' }"
                    method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError"
                    v-if="props.userStatus.status != 'blocked'"
                    class="capitalize font-bold bg-red-500 rounded-md py-2 px-7 hover:bg-red-800 hover:text-white transition-all">
                Block</Link>
                <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'delete' }" method="post"
                    preserveScroll as="button" @success="handleSuccess" @error="handleError"
                    class="capitalize font-bold bg-blue-500 rounded-md py-2 px-7 hover:bg-blue-800 hover:text-white transition-all">
                Delete</Link>
                <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'accepted' }"
                    method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError"
                    class="capitalize font-bold bg-green-500 rounded-md py-2 px-7 hover:bg-green-800 hover:text-white transition-all">
                Accept</Link>
            </div>
        </template>
        <template v-else>
            <Link :href="route('chat.invite', chatUserId)" method="post" preserveScroll
                as="button" @success="handleSuccess" @error="handleError"
                class="capitalize font-bold bg-blue-500 rounded-md py-2 px-7 hover:bg-blue-800 hover:text-white transition-all mt-4">
            invite</Link>
        </template>
    </div>

    <div class="messages flex-1 overflow-auto">
        <div class="message mb-4 flex " :class="{ 'text-right': message.sender_id == user.id }"
            v-for="(message, index) in messageData" :key="index.id">
            <div class="flex-2" v-if="message.sender_id != user.id">
                <div class="w-12 h-12 relative">
                    <img class="w-12 h-12 rounded-full mx-auto" src="https://readymadeui.com/team-1.webp"
                        alt="chat-user" />
                    <span class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white">
                    </span>
                </div>
            </div>
            <div class="flex-1 px-2">
                <div class="inline-block  rounded-full p-2 px-6 "
                    :class="message.sender_id == user.id ? 'bg-gray-300 text-gray-700' : 'bg-blue-600 text-white'">
                    <span>{{ message.content }}</span>
                </div>
                <div class="pl-4">
                    <small class="text-gray-500 dark:text-gray-300">{{ message.sent_at }}</small>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped></style>
