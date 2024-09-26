<script setup>
import { ref, reactive, defineProps } from 'vue'
import CustomModal from '../CustomModal.vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
const props = defineProps({
    waitLoad: Boolean,
    showModal: Boolean,
    allUser: Array
});

const emit = defineEmits(['closeModal']);
const hideModal = () => {
    emit('closeModal');
}

const searchTerm = ref('');
const searchResults = ref([]);
const isLoading = ref(false);
let timeout = null;

const searchUser = () => {
    if (!searchTerm.value.trim()) {
        searchResults.value = [];
        return;
    }

    isLoading.value = true;

    clearTimeout(timeout);
    timeout = setTimeout(() => {
        axios.get(route('chat.allUsers'), { params: { search: searchTerm.value } })
            .then(response => {
                searchResults.value = response.data.allUsers;
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                isLoading.value = false;
            });
    }, 300);

}

</script>

<template>
    <CustomModal class="absolute top-0 left-0" v-if="props.showModal">
        <div class="flex items-start">
            <div class="flex-1">
                <h3 class="text-gray-800 text-2xl font-bold">Members</h3>
                <p class="text-gray-500 text-sm mt-1">Manage and control who has access to this workspace.</p>
            </div>

            <i class="fa-solid fa-xmark cursor-pointer text-2xl" @click="hideModal"> </i>
        </div>

        <div class="flex flex-wrap gap-4 mt-6">
            <div
                class="flex items-center flex-1 px-4 py-2.5 rounded-full border border-gray-300 focus-within:border-blue-600 hover:border-blue-500 transition-all duration-300 ease-in-out min-w-[220px]">
                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                <input @input="searchUser" v-model="searchTerm" type="text" placeholder="Search by name or email"
                    class="ml-2 w-full outline-none bg-transparent text-gray-600 text-sm focus:outline-none border-none focus:ring-0 focus:border-none" />
            </div>
        </div>
        <template v-if="waitLoad">
            <h3 class="text-center font-bold text-4xl">Loding ....</h3>
        </template>
        <!-- All User -->
        <!-- <div v-for="(user, index) in searchResults" :key="user.id" class="flex flex-wrap items-center gap-4 py-3"> -->

        <!-- Display Users -->
        <div v-else class="mt-6 divide-y">
            <!-- Check if there are search results -->
            <template v-if="searchResults.length > 0">
                <div v-for="(user, index) in searchResults" :key="user.id"
                    class="flex flex-wrap items-center gap-4 py-3">
                    <img src='https://readymadeui.com/team-1.webp' class="w-11 h-11 rounded-full" />
                    <div>
                        <p class="text-sm text-gray-800 font-bold">{{ user.name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ user.email }}</p>
                    </div>
                    <Link :href="route('chat.invite', user.id)" as="button" method="post"
                        class="text-xs mt-0.5 ml-auto bg-indigo-500 px-4 py-1 rounded-md hover:bg-indigo-300 text-white hover:text-black cursor-pointer">
                    Invite
                    </Link>
                </div>
            </template>

            <!-- Show default users if no search results -->
            <template v-else>
                <div v-for="(user, index) in props.allUser" :key="user.id"
                    class="flex flex-wrap items-center gap-4 py-3">
                    <img src='https://readymadeui.com/team-1.webp' class="w-11 h-11 rounded-full" />
                    <div>
                        <p class="text-sm text-gray-800 font-bold">{{ user.name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ user.email }}</p>
                    </div>
                    <Link :href="route('chat.invite', user.id)" as="button" method="post"
                        class="text-xs mt-0.5 ml-auto bg-indigo-500 px-4 py-1 rounded-md hover:bg-indigo-300 text-white hover:text-black cursor-pointer">
                    Invite
                    </Link>
                </div>
            </template>
        </div>
        <!-- All User -->
    </CustomModal>
</template>

<style scoped></style>
