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
const visibleDropdown = ref(null);
const isEditing = ref(false);
const editedContent = ref('');

const otherAction = (messageId) => {
    if (visibleDropdown.value === messageId) {
        visibleDropdown.value = null;
    } else {
        visibleDropdown.value = messageId;
    }
};

function edit(message) {
    isEditing.value = message.id;
    editedContent.value = message.content;
    visibleDropdown.value = null;
}
function formatContent(message) {
    return message.replace(/\n/g, '<br>');
}

function handleSuccess(response) {
    if (response.props.flash.status == 'success') {
        visibleDropdown.value = null;
    }
    if (response.props.flash.status == 1) {
        isEditing.value = false;
    }

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
    <template v-if="props.userStatus">

        <!-- request user accept delete blocked -->
        <template v-if="user.id != props.userStatus.user_id">
            <div class=" bg-gray-500 w-full py-2 mb-4 rounded-md shadow-md shadow-indigo-400 text-center"
                v-if="props.userStatus.status != 'accepted'">
                <h1 class="font-bold capitalize text-center text-2xl ">
                    user Status {{ props.userStatus.status }}
                </h1>
                <div class=" flex justify-evenly">
                    <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'blocked' }"
                        method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError"
                        v-if="props.userStatus.status != 'blocked'"
                        class="capitalize font-bold bg-red-500 rounded-md py-2 px-7 hover:bg-red-800 hover:text-white transition-all">
                    Block</Link>
                    <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'delete' }"
                        method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError"
                        class="capitalize font-bold bg-blue-500 rounded-md py-2 px-7 hover:bg-blue-800 hover:text-white transition-all">
                    Delete</Link>
                    <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'accepted' }"
                        method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError"
                        class="capitalize font-bold bg-green-500 rounded-md py-2 px-7 hover:bg-green-800 hover:text-white transition-all">
                    Accept</Link>
                </div>
            </div>
        </template>

        <!-- user status or delete -->
        <div v-else>
            <div class="bg-gray-500 w-full py-2 mb-4 rounded-md shadow-md shadow-indigo-400 text-center"
                v-if="props.userStatus.status != 'accepted'">
                <h1 class="font-bold capitalize text-center text-2xl ">
                    user Status {{ props.userStatus.status }}
                </h1>
                <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'delete' }" method="post"
                    preserveScroll as="button" @success="handleSuccess" @error="handleError"
                    class="capitalize font-bold bg-blue-500 rounded-md py-2 px-7 hover:bg-blue-800 hover:text-white transition-all mt-3">
                Delete</Link>
            </div>
        </div>
    </template>

    <!-- invite freind if already no exists -->
    <template v-else>
        <div class="bg-gray-500 w-full py-2 mb-4 rounded-md shadow-md shadow-indigo-400 text-center">
            <h2 class="font-bold capitalize text-center text-3xl text-black">invite Your friend</h2>
            <Link :href="route('chat.invite', chatUserId)" method="post" preserveScroll as="button"
                @success="handleSuccess" @error="handleError"
                class="capitalize font-bold bg-blue-500 rounded-md py-2 px-7 hover:bg-blue-800 hover:text-white transition-all mt-4">
            invite</Link>
        </div>
    </template>

    <!-- chat message show -->
    <div class="messages flex-1 overflow-auto">
        <div class="message dropdownshow mb-4 flex relative" :class="{ 'text-right': message.sender_id == user.id }"
            v-for="(message, index) in messageData" :key="index.id">
            <div class="flex-2" v-if="message.sender_id != user.id">
                <div class="w-12 h-12 relative">
                    <img class="w-12 h-12 rounded-full mx-auto" src="https://readymadeui.com/team-1.webp"
                        alt="chat-user" />
                    <span class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white">
                    </span>
                </div>
            </div>
            <div class="flex-1 px-2 group">
                <span class="cursor-pointer hidden group-hover:block" v-if="message.sender_id == user.id">
                    <i class="fa-solid fa-ellipsis-vertical dark:text-white mr-2" @click="otherAction(message.id)"></i>
                </span>
                <div class="inline-block relative rounded-full p-2 px-6 "
                    :class="message.sender_id == user.id ? 'bg-gray-300 text-gray-700' : 'bg-blue-600 text-white'">
                    <span v-html="formatContent(message.content)"></span>
                </div>
                <span class="cursor-pointer" v-if="message.sender_id != user.id" @click="otherAction(message.id)">
                    <i class="fa-solid fa-ellipsis-vertical dark:text-white ml-2"></i>
                </span>
                <div class="pl-4">
                    <small class="text-gray-500 dark:text-gray-300">{{ message.sent_at }}</small>
                </div>
            </div>


            <template v-if="message.is_deleted_by !== 'sender' && message.is_deleted_by !== 'unsend' && message.is_deleted_by !== 'reciver'">

                <div class="absolute top-0 left-0 w-full bg-slate-500 z-50 p-4 rounded-md"
                    v-if="isEditing === message.id">
                    <textarea class="w-full px-2 rounded-md" v-model="message.content"></textarea>
                    <div class=" mt-4 py-3 flex justify-around">
                        <Link :href="route('chat.messageUpdate', message.id)" method="POST" as="button"
                            :data="{ updateContent: message.content, chatUserId: chatUserId }" preserveScroll
                            @success="handleSuccess" @error="handleError"
                            class="bg-gray-400 hover:bg-gray-700 hover:text-white transition-all capitalize font-bold py-3 rounded-md px-8 ">
                        update</Link>
                        <button @click="isEditing = false"
                            class="bg-red-400 hover:bg-red-700 hover:text-white transition-all capitalize font-bold py-3 rounded-md px-8 ">cancel</button>
                    </div>
                </div>

                <div v-if="visibleDropdown === message.id"
                    class="absolute left-1/3 top-0 mt-1 bg-white shadow-lg rounded-md p-2 border dark:bg-gray-400 z-50">
                    <ul class="space-y-1">
                        <li>
                            <Link :href="route('chat.messageDelete', message.id)" @success="handleSuccess"
                                @error="handleError" method="DELETE" as="button"
                                :data="{ chatUserId: chatUserId, action: 'delete' }"
                                class="block py-2 px-9 hover:bg-gray-100 dark:hover:bg-gray-600">
                            Delete</Link>
                        </li>
                        <li v-if="message.sender_id == user.id">
                            <Link :href="route('chat.messageDelete', message.id)" @success="handleSuccess"
                                @error="handleError" method="DELETE" as="button"
                                :data="{ chatUserId: chatUserId, action: 'unsend' }"
                                class="block py-2 px-9 hover:bg-gray-100 dark:hover:bg-gray-600">
                            Unsend</Link>
                        </li>
                        <li v-if="message.sender_id == user.id">
                            <button @click="edit(message)"
                                class="block py-2 hover:bg-gray-100 dark:hover:bg-gray-600 px-9">Edit</button>
                        </li>
                        <li>
                            <button class="block py-2 hover:bg-gray-100 dark:hover:bg-gray-600 px-9">reply</button>
                        </li>
                    </ul>
                </div>
            </template>
        </div>
    </div>

</template>

<style scoped></style>
