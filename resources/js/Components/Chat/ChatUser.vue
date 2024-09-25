<script setup>
import { ref, reactive, watch, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
const props = defineProps({
    allFriends: Array,
});

const limitedMessages = computed(() => {
    return props.allFriends.map(friend => {
        if (friend.messages && friend.messages.content) {
            return friend.messages.content.split(" ").slice(0, 20).join(" ") +
                (friend.messages.content.split(" ").length > 20 ? "..." : "");
        }
        return '';
    });
});
</script>

<template>
    <div class="flex-1 h-full overflow-auto px-2">
        <Link :href="route('chat.index', friend.user.id)" v-for="(friend, index) in props.allFriends" :key="index.id"
         class="entry cursor-pointer transform hover:bg-gray-700 hover:shadow-md text-black hover:shadow-indigo-400 transition-all duration-300  bg-white dark:bg-gray-600  mb-4 rounded p-4 flex shadow-md border-l-4 border-red-500">
        <div class="flex-2">
            <div class="w-12 h-12 relative">
                <img class="w-12 h-12 rounded-full mx-auto" src="http://localhost:8000/public/107.jpg"
                    alt="chat-user" />
                <span class="absolute w-4 h-4 bg-gray-400 rounded-full right-0 bottom-0 border-2 border-white"></span>
            </div>
        </div>
        <div class="flex-1 px-2">
            <div class="truncate w-32">
                <span class="text-gray-800 dark:text-white">
                    <span>
                        {{ friend.user.name }}
                    </span>
                </span>
            </div>
            <div>
                <small class="text-gray-600 dark:text-white">
                    <span>
                        {{ limitedMessages[index] }}
                    </span>
                </small>
            </div>
        </div>
        <div class="flex-2 text-right">
            <div>
                <small class="text-gray-500 dark:text-white">15 April</small>
            </div>
            <div>
                <small class="text-xs bg-red-500 text-white rounded-full h-6 w-6 leading-6 text-center inline-block">
                    {{ friend.unreadMessage }}
                </small>
            </div>
        </div>
        </Link>
    </div>
</template>

<style scoped></style>
