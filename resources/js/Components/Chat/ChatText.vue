<script setup>
import { ref, reactive, defineProps, defineEmits, onMounted, watch, nextTick } from 'vue'
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
const hoverMessageId = ref(null);
const chatInfo = ref(false);

const chatInfor = ()=>{
    chatInfo.value = !chatInfo.value;
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

const toggleDropdown = (message) => {
    if (visibleDropdown.value === message.id) {
        visibleDropdown.value = null;
    } else {
        visibleDropdown.value = message.id;
    }
};

const dropdownClass = (message) => {
    return message.sender_id === user.id ? 'right-[8rem] top-[-0.5rem]' : 'left-[8rem] top-[-0.5rem]';
};

const verticalDropdownClass = (messageId, isLastMessage) => {
    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
    if (messageElement) {
        const rect = messageElement.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (isLastMessage || rect.bottom > windowHeight - 150) {
            return 'bottom-full mb-2';
        } else {
            return 'top-full mt-2';
        }
    }
    return 'top-full mt-2';
};



const editMessage = (message) => {
    isEditing.value = message.id;
    visibleDropdown.value = null;
};

function scrollToBottom() {
    const chatBox = document.querySelector('.messages');
    chatBox.scrollTop = chatBox.scrollHeight;
}

onMounted(() => {
    nextTick(() => {
        scrollToBottom(); // মাউন্ট হবার পর স্ক্রল করবে
    });
});

watch(props.messageData, async () => {
    await nextTick();  // DOM আপডেটের পর নিশ্চিত করে
    scrollToBottom();  // এরপর সবার নিচে স্ক্রল করবে
});

</script>

<template>
    <div class="flex justify-between z-20">
        <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200 dark:text-white">
            <span class="lg:hidden">
                <i @click="$emit('toggleChatAllUserSmallDisplay')"
                    class="fa-solid fa-bars  mr-5 cursor-pointer w-5 h-5"></i>
            </span>
            Chatting with
            <b>{{ chat_user_name.name }}</b>
        </h2>
        <div class="relative">
            <i class="fas fa-ellipsis-v dark:text-white text-2xl cursor-pointer" @click="chatInfor"></i>
            <ul class="absolute top-0 right-7  bg-gray-800 shadow-md shadow-indigo-300 " v-if="chatInfo">
                <li>
                    <Link href="#" class="dark:text-white px-[2rem] hover:bg-indigo-300 inline-block capitalize">clear chat</Link>
                </li>
                <li>
                    <Link :href="route('chat.inviteStatus', props.userStatus.id)" :data="{ status: 'blocked' }"
                    method="post" preserveScroll as="button" @success="handleSuccess" @error="handleError" class="dark:text-white px-[2rem] hover:bg-indigo-300 inline-block capitalize">Block</Link>
                </li>
            </ul>
        </div>
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
                    <Link :href="route('chat.inviteStatus', props.userStatus.id)"
                        :data="{ status: 'delete', chatUserId: chatUserId }" method="post" preserveScroll as="button"
                        @success="handleSuccess" @error="handleError"
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
                <Link :href="route('chat.inviteStatus', props.userStatus.id)"
                    :data="{ status: 'delete', chatUserId: chatUserId }" method="post" preserveScroll as="button"
                    @success="handleSuccess" @error="handleError"
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
            v-for="(message, index) in messageData" :key="index.id" @mouseover="hoverMessageId = message.id"
            :data-message-id="message.id" @mouseleave="hoverMessageId = null">
            <!-- Avatar for received messages -->
            <div class="flex-2" v-if="message.sender_id != user.id">
                <div class="w-12 h-12 relative">
                    <img class="w-12 h-12 rounded-full mx-auto" src="https://readymadeui.com/team-1.webp"
                        alt="chat-user" />
                    <span
                        class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white"></span>
                </div>
            </div>

            <!-- Message Content and Actions -->
            <div class="flex-1 px-2 group">
                <span class="cursor-pointer" v-if="message.sender_id == user.id" v-show="hoverMessageId === message.id">
                    <i class="fa-solid fa-ellipsis-vertical dark:text-white mr-2" @click="toggleDropdown(message)"></i>
                </span>

                <div class="inline-block relative rounded-full p-2 px-6"
                    :class="message.sender_id == user.id ? 'bg-gray-300 text-gray-700' : 'bg-blue-600 text-white'"
                    @click="toggleDropdown(message)">
                    <span v-html="formatContent(message.content)"></span>
                </div>

                <span class="cursor-pointer" v-if="message.sender_id != user.id" v-show="hoverMessageId === message.id">
                    <i class="fa-solid fa-ellipsis-vertical dark:text-white ml-2" @click="toggleDropdown(message)"></i>
                </span>

                <!-- Message timestamp -->
                <div class="pl-4">
                    <small class="text-gray-500 dark:text-gray-300">{{ message.sent_at }}</small>
                </div>
            </div>

            <!-- Dropdown Menu -->
            <div v-if="visibleDropdown === message.id"
                :class="['absolute', dropdownClass(message), verticalDropdownClass(message.id, index === messageData.length - 1)]"
                class="bg-white shadow-lg rounded-md p-2 border dark:bg-gray-800 dark:text-white z-50">

                <ul class="space-y-1">
                    <li>
                        <button class="block py-2 hover:bg-gray-100 dark:hover:bg-gray-600 px-9">reply</button>
                    </li>
                    <li v-if="message.sender_id == user.id">
                        <button @click="editMessage(message)"
                            class="block py-2 hover:bg-gray-100 dark:hover:bg-gray-600 px-9">
                            Edit
                        </button>
                    </li>
                    <li>
                        <Link :href="route('chat.messageDelete', message.id)" @success="handleSuccess"
                            @error="handleError" method="DELETE" as="button"
                            :data="{ chatUserId: chatUserId, action: 'delete' }"
                            class="block py-2 px-9 hover:bg-gray-100 dark:hover:bg-gray-600">
                        Delete
                        </Link>
                    </li>
                    <li v-if="message.sender_id == user.id">
                        <Link :href="route('chat.messageDelete', message.id)" @success="handleSuccess"
                            @error="handleError" method="DELETE" as="button"
                            :data="{ chatUserId: chatUserId, action: 'unsend' }"
                            class="block py-2 px-9 hover:bg-gray-100 dark:hover:bg-gray-600">
                        Unsend
                        </Link>
                    </li>
                </ul>
            </div>


            <div class="fixed xl:right-[8rem] right-0 top-1/2 transform -translate-y-1/2 xl:w-1/2 w-full bg-slate-500 z-20 p-4 rounded-md"
                v-if="isEditing === message.id">
                <textarea class="w-full px-2 rounded-md" v-model="message.content"></textarea>
                <div class="mt-4 py-3 flex justify-around">
                    <Link :href="route('chat.messageUpdate', message.id)" method="POST" as="button"
                        :data="{ updateContent: message.content, chatUserId: chatUserId }" preserveScroll
                        @success="handleSuccess" @error="handleError"
                        class="bg-gray-400 hover:bg-gray-700 hover:text-white transition-all capitalize font-bold py-3 rounded-md px-8">
                    Update
                    </Link>
                    <button @click="isEditing = false"
                        class="bg-red-400 hover:bg-red-700 hover:text-white transition-all capitalize font-bold py-3 rounded-md px-8">
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped></style>
