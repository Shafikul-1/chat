<script setup>
import { ref, reactive, defineProps } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatInput from '@/Components/Chat/ChatInput.vue';
import ChatText from '@/Components/Chat/ChatText.vue';
import AllUser from '@/Components/Chat/AllUser.vue';
import ChatUser from '@/Components/Chat/ChatUser.vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
const props = defineProps({
    messageData: Array,
    allFriends: Array,
});



const chatAllUser = ref(false);

const allUsers = ref([]);
const showModal = ref(false);
const waitLoad = ref(true);
const openModal = () => {
    showModal.value = true;
    axios.get(route('chat.allUsers'))
        .then(response => {
            allUsers.value = response.data.allUsers;
            waitLoad.value = false;
        }).catch(error => {
            console.log('error -- ' + error);

        })
};


</script>

<template>
    <AuthenticatedLayout>
        <!-- Modal All User-->
        <AllUser :showModal="showModal" :allUser="allUsers" :waitLoad="waitLoad" @closeModal="showModal = false"/>
        <!-- Modal All User-->
        <div class="flex-1 dark:bg-[#111827] bg-gray-100 w-full h-full mt-5">
            <div class="main-body container m-auto w-11/12 h-full flex flex-col">
                <div class="main flex-1 flex flex-col">
                    <div class="flex-1 flex h-full relative lg:gap-3">
                        <div :class="{ 'hidden': !chatAllUser }"
                            class="sidebar border-r-indigo-300 border-r absolute w-[95%] left-[2rem] top-0 z-50 lg:flex lg:static lg:w-[35%] min-h-[88vh] overflow-y-auto flex-2 flex-col  bg-gray-700 p-3 lg:bg-transparent ">
                            <div class="search flex-2 pb-6 px-2">
                                <input @click="openModal" type="text"
                                    class="outline-none py-2 block w-full bg-transparent border-b-2 "
                                    placeholder="Search User">
                            </div>

                            <!-- User All -->
                            <template v-if="props.allFriends != null">

                                <ChatUser :allFriends="props.allFriends" />

                            </template>
                            <div class="text-center dark:text-white font-bold text-4xl" v-else>No Added User</div>
                            <!-- User All -->

                        </div>
                        <template v-if="props.messageData != null">
                            <div class="chat-area flex-1 flex flex-col">
                                <!-- Chat Text -->
                                <div class="flex-3">
                                    <h2 class="text-xl py-1 mb-8 border-b-2 border-gray-200 dark:text-white">
                                        <span class="lg:hidden">
                                            <i @click="chatAllUser = !chatAllUser"
                                                class="fa-solid fa-bars  mr-5 cursor-pointer w-5 h-5"></i>
                                        </span>
                                        Chatting with
                                        <b>Mercedes Yemelyan</b>
                                    </h2>
                                </div>
                                <ChatText :messageData="props.messageData" />
                                <!-- Chat Text -->


                                <!-- Chat filed Content -->
                                <ChatInput />
                                <!-- Chat filed Content -->
                            </div>
                        </template>
                        <div v-else>
                            <h1 class="font-bold text-xl dark:text-white text-center">No Chat here</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
